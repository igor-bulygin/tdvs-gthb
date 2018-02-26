<?php

namespace app\controllers;

use app\helpers\CAccessRule;
use app\helpers\CController;
use app\helpers\EmailsHelper;
use app\helpers\Utils;
use app\models\Category;
use app\models\Country;
use app\models\Currency;
use app\models\Invitation;
use app\models\MetricType;
use app\models\OldProduct;
use app\models\Order;
use app\models\Person;
use app\models\PostmanEmail;
use app\models\SizeChart;
use app\models\Tag;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\mongodb\Collection;
use yii\web\NotFoundHttpException;

class AdminController extends CController {
	public $defaultAction = "index";

	public function behaviors () {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'ruleConfig' => [
					'class' => CAccessRule::className(),
				],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function actionIndex() {
		/*
		var_dump( (new Currency("EUR", 2))->convert("EUR") );
		var_dump( (new Currency("EUR", 10))->convert("USD") );
		var_dump( (new Currency("JPY", 130))->convert("EUR") );
		var_dump( (new Currency("JPY", 130))->convert("USD") );
		die();
		*/

		return $this->render("index");
	}

	public function actionDevisers($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::DEVISER];

		$devisers = new ActiveDataProvider([
			'query' => Person::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$countries = Country::find()->select(["_id" => 0])->asArray()->all();
		Utils::l_collection($countries, "country_name");

		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = $country["country_name"];
		}

		$data = [
			'devisers' => $devisers,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("devisers", $data) : $this->render("devisers", $data);
	}

	public function actionInfluencers($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::INFLUENCER];

		$influencers = new ActiveDataProvider([
			'query' => Person::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'influencers' => $influencers,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("influencers", $data) : $this->render("influencers", $data);
	}

	public function actionClients($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::CLIENT];

		$clients = new ActiveDataProvider([
			'query' => Person::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'clients' => $clients,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("clients", $data) : $this->render("clients", $data);
	}

	public function actionAdmins($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::ADMIN];

		$admins = new ActiveDataProvider([
			'query' => Person::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'admins' => $admins
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("admins", $data) : $this->render("admins", $data);
	}

	public function actionInvitations($filters = null) {
//		$filters = Utils::stringToFilter($filters);
//		$filters["type"]['$in'] = [Person::ADMIN];

		$invitations = new ActiveDataProvider([
			'query' => Invitation::find()->orderBy(["created_at" => SORT_DESC]),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'invitations' => $invitations
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("invitations", $data) : $this->render("invitations", $data);
	}

	public function actionPostmanEmails($filters = null) {

		$emails = new ActiveDataProvider([
			'query' => PostmanEmail::find()->orderBy(["created_at" => SORT_DESC]),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'emails' => $emails
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("postman-emails", $data) : $this->render("postman-emails", $data);
	}


	public function actionBasicStats($filters = null) {

		$devisers = Person::findSerialized([
			'type' => Person::DEVISER,
			'account_state' => Person::ACCOUNT_STATE_ACTIVE,
		]);

		$influencers = Person::findSerialized([
			'type' => Person::INFLUENCER,
			'account_state' => Person::ACCOUNT_STATE_ACTIVE,
		]);

		$clients = Person::findSerialized([
			'type' => Person::CLIENT,
			'account_state' => Person::ACCOUNT_STATE_ACTIVE,
		]);

		$orders = Order::findSerialized([
			'order_state' => Order::ORDER_STATE_PAID,
			'order_by' => [
				'order_date' => SORT_DESC,
			]
		]);

		$amount = 0;
		$todeviseFees = 0;
		foreach ($orders as $order) {
			$amount += $order->subtotal;
			$packs = $order->getPacks();
			foreach ($packs as $pack) {
				$todeviseFees += $pack->pack_total_fee_todevise;
			}

		}

		$stats = array(
			'Total number of published devisers' => count($devisers),
			'Total number of published influencers' => count($influencers),
			'Total number of registered users' => count($clients),
			'Total number of sales' => count($orders),
			'Gross amount from the sales, in EUROS' => $amount,
			'Total amount in Todevise commission' => $todeviseFees,
		);

		$data = [
			'stats' => $stats,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("basic-stats", $data) : $this->render("basic-stats", $data);
	}


	public function actionSalesHistory($filters = null) {

		$orders = Order::findSerialized([
			'order_state' => Order::ORDER_STATE_PAID,
			'order_by' => [
				'order_date' => SORT_DESC,
			]
		]);

		$salesHistory = [];
		foreach ($orders as $order) {

			$orderCurrency = Currency::getDefaultCurrency();

			$date = $order->order_date->toDateTime()->format('Y-m-d H:i:s');

			$client = $order->getPerson();
			$clientName = $client->getName();
			$clientLink =  '<a href="'.$client->getMainLink().'" target="_blank">'.$clientName.'</a>';
			$packs = $order->getPacks();

			$historyItem = '<p data-toggle="modal" data-target=".order'.$order->short_id.'" role="button">Expand</p>';


			$detail = '';
			$detail .= '
				<h4>Date: '.$date.'<span class="pull-right">Amount: '.$order->subtotal.$orderCurrency.'</span></h4>
				<h4>Client: '.$clientLink.'<span class="pull-right">Order id: '.$order->short_id .'</span></h4>
			';
			
			$detail .= '<hr />';

			$nProducts = 0;
			$feeWithoutVAT = 0;
			$vat = 0;
			$devisers = [];
			foreach ($packs as $pack) {

				$feeWithoutVAT += $pack->pack_total_fee - $pack->pack_total_fee_vat;
				$vat += $pack->pack_total_fee_vat;

				$deviser = $pack->getDeviser();
				$deviserName = $deviser->getName();
				$deviserLink = '<a href="'.$deviser->getMainLink().'" target="_blank">'.$deviserName.'</a>';
				$devisers [] = $deviserName;

				$products = $pack->getProducts();

				$detail .= '<h5>Pack '.$pack->short_id.': '.$deviserLink.' <span class="pull-right">'.$pack->pack_total_price.$pack->currency.'</span></h5>';

				$detail .= '<br />';
				$detail .= '<table class="table">';

				$detail .= '<tr>';
				$detail .= '<td>Product</td>';
				$detail .= '<td class="text-right">Quantity</td>';
				$detail .= '<td class="text-right">Unit price</td>';
				$detail .= '<td class="text-right">Total price</td>';
				$detail .= '</tr>';

				foreach ($products as $orderProduct) {
					$product = $orderProduct->getProduct();
					$productName = $product->getName();
					$productLink = '<a href="'.$product->getViewLink().'" target="_blank">'.$productName.'</a>';

					$detail .= '<tr>';
					$detail .= '<td>'.$productLink.'</td>';
					$detail .= '<td class="text-right">'.$orderProduct->quantity.'</td>';
					$detail .= '<td class="text-right">'.$orderProduct->price.$pack->currency.'</td>';
					$detail .= '<td class="text-right">'.$orderProduct->quantity * $orderProduct->price.$pack->currency.'</td>';
					$detail .= '</tr>';

					$nProducts += $orderProduct->quantity;
				}



				$detail .= '<tr>';
				$detail .= '<td>Shipping ('.$pack->shipping_type.')</td>';
				$detail .= '<td class="text-right">1</td>';
				$detail .= '<td class="text-right">'.$pack->shipping_price.$pack->currency.'</td>';
				$detail .= '<td class="text-right">'.$pack->shipping_price.$pack->currency.'</td>';
				$detail .= '</tr>';

				$detail .= '</table>';



				$historyItem .=
					'<div class="modal full-modal fade fc-000 order'.$order->short_id.'" id="order'.$order->short_id.'">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Order '.$order->short_id.'</h4>
								</div>
								<div class="modal-body">'.
									$detail . '
								</div>
							</div>
						</div>
					</div>';

			}

			$devisers = implode(',' , $devisers);

			$salesHistory[] = [
				'date' => $date,
				'order_id' => $order->short_id,
				'amount' => $order->subtotal.$orderCurrency,
				'netAmount' => ($order->subtotal-$feeWithoutVAT-$vat).$orderCurrency,
				'feeWithoutVAT' => $feeWithoutVAT.$orderCurrency,
				'vat' => $vat.$orderCurrency,
				'client' => $order->getPerson()->getName(),
				'devisers' => $devisers,
				'nProducts' => $nProducts,
				'detail' => $historyItem,

			];

		}

		$data = [
			'salesHistory' => $salesHistory,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("sales-history", $data) : $this->render("sales-history", $data);
	}


	public function actionMandrillSent($filters = null) {

		$emails = EmailsHelper::listSent(null, null);

		$provider = new ArrayDataProvider([
			'allModels' => $emails,
			'pagination' => ['pageSize' => 100]
		]);

		$data = [
			'emails' => $provider,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("mandrill-sent", $data) : $this->render("mandrill-sent", $data);
	}

	public function actionMandrillScheduled($filters = null) {

		$emails = EmailsHelper::listScheduled();

		$provider = new ArrayDataProvider([
			'allModels' => $emails,
			'pagination' => ['pageSize' => 100]
		]);

		$data = [
			'emails' => $provider,
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("mandrill-scheduled", $data) : $this->render("mandrill-scheduled", $data);
	}

	public function actionMandrillContent($message_id) {

		$message = EmailsHelper::content($message_id);

		if (!$message) {
			throw new NotFoundHttpException("Message not found");
		}
		$this->layout = '/desktop/empty-layout.php';

		return $this->renderContent($message['html']);
	}

	public function actionTags($filters = null) {
		$filters = Utils::stringToFilter($filters);

		$tags = new ActiveDataProvider([
			'query' => Tag::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'categories' => Category::find()->select(["_id" => 0])->asArray()->all(),
			'tags' => $tags
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("tags", $data) : $this->render("tags", $data);
	}

	public function actionTag($tag_id) {
		return $this->render("tag", [
			"tag" => Tag::find()->select(["_id" => 0])->where(["short_id" => $tag_id])->asArray()->one(),
			"categories" => Category::find()->select(["_id" => 0])->asArray()->all(),
			"mus" => [
				["value" => MetricType::NONE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]), "checked" => true],
				["value" => MetricType::SIZE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE])],
				["value" => MetricType::WEIGHT, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT])]
			]
		]);
	}

	public function actionSizeCharts($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"] = SizeChart::TODEVISE;

		$sizecharts = new ActiveDataProvider([
			'query' => SizeChart::find()->select(["_id" => 0])->where($filters),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'categories' => Category::find()->select(["_id" => 0])->asArray()->all(),
			'sizecharts' => $sizecharts,
			'sizechart_template' => SizeChart::find()->select(["_id" => 0, "short_id", "name"])->asArray()->all()
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("size-charts", $data) : $this->render("size-charts", $data);
	}

	public function actionSizeChart($size_chart_id) {
		$countries = Country::find()->select(["_id" => 0])->asArray()->all();
		$countries_lookup = [];
		$continents = [];

		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::l($country["country_name"]);
		}

		foreach(Country::CONTINENTS as $code => $continent) {
			$continents[] = [
				"country_code" => $code,
				"country_name" => [
					$this->lang => Yii::t("app/admin", $continent)
				]
			];
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$sizechart = SizeChart::find()->select(["_id" => 0])->where(["short_id" => $size_chart_id])->asArray()->one();
		$categories = Category::find()->select(["_id" => 0])->asArray()->all();
		Utils::l_collection($categories, "name");

		$countries = [
			[
				"country_name" => [
					$this->lang => Yii::t("app/admin", "Continents")
				],
				"sub" => $continents
			],
			[
				"country_name" => [
					$this->lang => Yii::t("app/admin", "Countries")
				],
				"sub" => $countries
			]
		];

		$mus = [
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x, "smallest" => MetricType::UNITS[MetricType::SIZE][0]]; }, MetricType::UNITS[MetricType::SIZE])
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x, "smallest" => MetricType::UNITS[MetricType::WEIGHT][0]]; }, MetricType::UNITS[MetricType::WEIGHT])
			]
		];

		return $this->render("size-chart", [
			"sizechart" => $sizechart,
			"categories" => $categories,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"mus" => $mus
		]);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}

	public function actionFaqs(){
		return $this->render("faqs", []);
	}

	public function actionTerms(){
		return $this->render("terms", []);
	}

	public function actionFaq($faq_id = null, $faq_subid = null){
		return $this->render("faq", ['faq_id' => $faq_id, 'faq_subid' => $faq_subid]);
	}

	public function actionTerm($term_id = null, $term_subid = null){
		return $this->render("term", ['term_id' => $term_id, 'term_subid' => $term_subid]);
	}

	public function actionProducts ($slug) {
		$deviser = Person::find()->select(["_id" => 0])->where(["slug" => $slug, "type" => ['$in' => [Person::DEVISER]]])->asArray()->one();

		$products = new ActiveDataProvider([
			'query' => OldProduct::find()->select(["_id" => 0])->where(['deviser_id' => $deviser['short_id']]),
			'pagination' => [
				'pageSize' => 100,
			],
		]);

		$data = [
			'deviser' => $deviser,
			'products' => $products
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("products", $data) : $this->render("products", $data);
	}

	/**
	 * Updates ALL PASSWORDS to todevise1234
	 *
	 * @deprecated
	 * @throws \yii\mongodb\Exception
	 */
	public function actionResetPassword($person_id)
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		/* @var Person[] $persons */
		$person = Person::findOne(['short_id' => $person_id]);
		if ($person) {
			$person->setPassword('todevise1234');

			// Update directly in low level, to avoid no desired behaviors of ActiveRecord
			/** @var Collection $collection */
			$collection = Yii::$app->mongodb->getCollection('person');
			$collection->update(
				[
					'short_id' => $person->short_id
				],
				[
					'credentials' => $person->credentials
				]
			);

			echo 'Password of '.$person->getName().' was succesfully reset. Now you can log-in we the above credentials:<br /><br />';
			echo 'Username: <b>'.$person->credentials['email'].'</b><br />';
			echo 'Password: <b>todevise1234</b><br />';
		}
	}

	public function actionInvoicesExcel($date_from, $date_to)
	{
		$date_from_formatted = strtotime($date_from);
		$date_to_formatted = strtotime($date_to);
		if (!$date_to || !$date_from) {
			throw new Exception("Invalid date");
		}
		$orders = Order::findSerialized([
			'order_state' => Order::ORDER_STATE_PAID,
			'order_date_from' => new \MongoDate($date_from_formatted),
			'order_date_to' => new \MongoDate($date_to_formatted),
			'order_by' => [
				'order_date' => SORT_ASC
			]
		]);
		$csv[] = [
			'Invoice number',
			'Date of transaction',
			'Name of deviser',
//			'Email of deviser',
			'Address of deviser',
			'Fee without VAT',
			'VAT (if applicable)',
			'Fee including VAT',
//			'Fee percentage',
		];
		$i = 0;
		foreach ($orders as $order) {
			$packs = $order->getPacks();
			foreach ($packs as $pack) {
				$feeAmount = $pack->pack_total_fee;
				if ($feeAmount) {
					$i++;
					$deviser = $pack->getDeviser();
					$csv[] = [
						$order->short_id . '/' . $pack->short_id,
						$order->order_date->toDateTime()->format('d/m/Y'),
						$deviser->getName(),
//						$deviser->getEmail(),
						$deviser->getCompleteAddress(),
						$pack->pack_total_fee - $pack->pack_total_fee_vat,
						$pack->pack_total_fee_vat,
						$pack->pack_total_fee,
//						($pack->pack_percentage_fee * 100) . '%',
					];
				}
			}
		}

//		var_dump($csv);
//		die;

		$result = '';
		foreach ($csv as $fila) {
			foreach ($fila as $valor) {
				$result .= '"'.$valor.'";';
			}
			$result .= "\n";
		}

		header("Content-type: text/txt");
		header("Content-Disposition: attachment; filename=invoices-".$date_from."-".$date_to.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo $result;
	}

	public function actionBanners($filters = null) {

		$data = [];

		return Yii::$app->request->isAjax ? $this->renderPartial("banners", $data) : $this->render("banners", $data);
	}
}
