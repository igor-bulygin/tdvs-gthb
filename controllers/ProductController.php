<?php
namespace app\controllers;

use app\helpers\CController;
use app\helpers\Utils;
use app\models\Bespoke;
use app\models\Box;
use app\models\Lang;
use app\models\MadeToOrder;
use app\models\Person;
use app\models\Product;
use Yii;
use yii\filters\AccessControl;
use yii\mongodb\Collection;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;
use app\helpers\Import\ImportHelper;

class ProductController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => ['create', 'edit'],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		$maxLimit = 120;
		// set pagination values
		$limit = Yii::$app->request->get('limit', $maxLimit);
		$limit = max(1, $limit);
		$limit = min($limit, $maxLimit);
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$text = Yii::$app->request->get("q"); // search in name, description, and more
        $searchTypeId = Yii::$app->request->get("searchTypeId"); // search in name, description, and more
		$products = Product::findSerialized([
				"id" => Yii::$app->request->get("id"),
				"name" => Yii::$app->request->get("name"), // search only in name attribute
				"text" => Yii::$app->request->get("q"), // search in name, description, and more
				"deviser_id" => Yii::$app->request->get("deviser"),
				"categories" => Yii::$app->request->get("categories"),
				"order_type" => Yii::$app->request->get("order_type"),
				"product_state" => Product::PRODUCT_STATE_ACTIVE,
				"limit" => $limit,
				"offset" => $offset,
		]);
		$total = Product::$countItemsFound;
		$more = $total > ($page * $limit) ? 1 : 0;

//		$htmlWorks = $this->renderPartial("more-products", [
//				'total' => $total,
//				'products' => $products,
//		]);

		$this->layout = '/desktop/public-2.php';
		return $this->render("products", [
//				'htmlWorks' => $htmlWorks,
				'text'          => $text,
				'searchTypeId'  => $searchTypeId,
				'total'         => $total,
				'products'      => $products,
				'more'          => $more,
				'page'          => $page + 1,
		]);
	}

	public function actionMoreWorks()
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		$maxLimit = 120;
		// set pagination values
		$limit = Yii::$app->request->get('limit', $maxLimit);
		$limit = max(1, $limit);
		$limit = min($limit, $maxLimit);
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$products = Product::findSerialized([
				"id" => Yii::$app->request->get("id"),
				"name" => Yii::$app->request->get("name"), // search only in name attribute
				"text" => Yii::$app->request->get("q"), // search in name, description, and more
				"deviser_id" => Yii::$app->request->get("deviser"),
				"categories" => Yii::$app->request->get("categories"),
				"product_state" => Product::PRODUCT_STATE_ACTIVE,
				"limit" => $limit,
				"offset" => $offset,
		]);
		$total = Product::$countItemsFound;
		$more = $total > ($page * $limit) ? 1 : 0;

		$this->layout = '/desktop/empty-layout.php';

		$html = $this->render("more-products", [
				'total' => $total,
				'products' => $products,
		]);

		return json_encode([
			'html' => $html,
			"page" => $more ? $page + 1 : null,
		]);
	}


	public function actionDetail($slug, $product_id)
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		// get the product
		$product = Product::findOneSerialized($product_id);

		if (empty($product) || $product->product_state != Product::PRODUCT_STATE_ACTIVE) {
			throw new HttpException(404, 'The requested item could not be found.');
		}

		if ($slug != $product->getSlug()) {
			return $this->redirect($product->getViewLink(), 301);
		}

		// get the deviser
		$deviser = Person::findOneSerialized($product->deviser_id);
		if (empty($deviser) || $deviser->account_state != Person::ACCOUNT_STATE_ACTIVE) {
			throw new HttpException(404, 'The requested item could not be found.');
		}

		// get other products of the deviser
		$deviserProducts = Product::findSerialized([
			'deviser_id' => $product->deviser_id,
			'product_state' => Product::PRODUCT_STATE_ACTIVE,
		]);

		$boxes = Box::findSerialized([
			'product_id' => $product->short_id,
		]);


    // COMMISSION FOR DISCOVERY
    //-------------------------

    // Check if user is logged in
    if(isset(Yii::$app->user->identity)) {

      $referrer_url = explode('/', Yii::$app->request->referrer);  // Getting referrer URL

      // Checking for devisers, influencers or clients
      $controller_name = isset($referrer_url[3]) ? $referrer_url[3] : '';

      switch ($controller_name) {
        case 'deviser':
          $identifier = isset($referrer_url[5]) ? $referrer_url[5] : '';
          break;
        case 'influencer':
          $identifier = isset($referrer_url[5]) ? $referrer_url[5] : '';
          break;
        case 'client':
          $identifier = isset($referrer_url[5]) ? $referrer_url[5] : '';
          break;
        default: // From any other page
          $identifier = "0";
          break;
      }

      $currentUser = Yii::$app->user->identity;

      // We check if the customer has already seen the product
      if(!isset($currentUser->product_discovery_from_user)) { // No product viewed
        Yii::$app->mongodb->getCollection('person')->update(
          [
            'short_id' => $currentUser->short_id
          ],
          [
            'product_discovery_from_user' => [
              "PRODUCT".$product_id => $identifier
            ],
          ]
        );
      }
      else if(!array_key_exists("PRODUCT".$product_id, $currentUser->product_discovery_from_user)) { // Product not viewed
        $discoveries = $currentUser->product_discovery_from_user;
        $discoveries["PRODUCT".$product_id] = $identifier; // Adding discovery

        Yii::$app->mongodb->getCollection('person')->update(
          [
            'short_id' => $currentUser->short_id
          ],
          [
            'product_discovery_from_user' => $discoveries,
          ]
        );
      }
    }
    else { // Not logged in
    }
    //------------------------------------------------


		$this->layout = '/desktop/public-2.php';
		return $this->render("detail", [
			'product' => $product,
			'person' => $deviser,
			'personProducts' => $deviserProducts,
			'boxes' => $boxes,
		]);
	}

	public function actionCreate($slug, $person_id)
	{
		/** @var Person $person */
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isDeviserEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("product-create", [
			'person' => $person,
		]);
	}

    public function actionImportForm($slug, $person_id)
    {
        /** @var Person $person */
        $person = Person::findOneSerialized($person_id);

        if (!$person) {
            throw new NotFoundHttpException();
        }

        if (!$person->isDeviserEditable()) {
            throw new UnauthorizedHttpException();
        }

        $this->layout = '/desktop/public-2.php';
        return $this->render("product-import", [
            'person' => $person,
        ]);
    }

    public function actionImport($slug, $person_id)
    {
        /** @var Person $person */
        $person = Person::findOneSerialized($person_id);

        if (!$person) {
            throw new NotFoundHttpException();
        }

        if (!$person->isDeviserEditable()) {
            throw new UnauthorizedHttpException();
        }

        $csv_file = UploadedFile::getInstanceByName('csv');
        if (!$csv_file || $csv_file === null) {
            throw new \Exception('File not uploaded correctly');
        }

        $importHelper = new ImportHelper($csv_file->tempName, Yii::$app->request->post(), $person);

        $product_arr = $importHelper->import();

//        print_r($product_arr);
//        die();

        foreach ($product_arr as $data) {
            $mode = array_shift($data);
            $product_id = array_shift($data);

            Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

            if ($mode == 'add') {
                $product = new Product();

                $product->setScenario(Product::SCENARIO_PRODUCT_DRAFT);

                if ($product->load($data, '')) {
                    $product->save(false);
                }
            }
            elseif ($mode == 'update') {
                $product = Product::findOneSerialized($product_id);
                if (!$product) {
                    continue;
                }

                $product->setScenario(Product::SCENARIO_PRODUCT_DRAFT);

                if ($product->load($data, '')) {

                    $product->save(false);

                    $product->moveTempUploadsToProductPath();


                } else {
                    // TODO write error to flash
                }

            }
        }
        $this->redirect('/deviser/'.$person->slug.'/'.$person->short_id.'/store/edit?product_state=product_state_draft');




        // print_r($product_arr);
    }



	public function actionEdit($slug, $person_id, $product_id)
	{
		/** @var Person $person */
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isDeviserEditable()) {
			throw new UnauthorizedHttpException();
		}

		$product = Product::findOneSerialized($product_id);

		if (!$product) {
			throw new NotFoundHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("product-edit", [
			'person' => $person,
			'product' => $product,
		]);
	}

	/**
	 * @deprecated
	 */
	public function actionFixPosition()
	{
//		ini_set('memory_limit', '2048M');

		// set pagination values
		$deviser_id = Yii::$app->request->get('deviser_id');

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
		$query = Person::find();
		if (!empty($deviser_id)) {
			$query->where(["short_id" => $deviser_id]);
		}
		$devisers = $query->all();

		$cant = 0;
		/** @var Person $deviser */
		foreach ($devisers as $deviser) {
			$products = Product::find()->where(["deviser_id" => $deviser->short_id])->all();
			$i = 0;
			/** @var Product $product */
			foreach ($products as $product) {
				$i++;
				// Update directly in low level, to avoid no desired behaviors of ActiveRecord
				/** @var Collection $collection */
				$collection = Yii::$app->mongodb->getCollection('product');
				$collection->update(
						[
							'short_id' => $product->short_id
						],
						[
							'position' => $i
						]
					);
				$cant++;
			}
		}
		Yii::$app->response->setStatusCode(200); // Success, without body
		var_dump("done (" . $cant . ")");
	}

	public function actionFixProducts()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		/* @var Product[] $products */
		$products = Product::findSerialized();
		foreach ($products as $product) {
			// fix name field, must be an array
			if (!empty($product->name) && !is_array($product->name)) {
				$name = [];
				foreach (Lang::getAvailableLanguages() as $key => $langName) {
					$name[$key] = $product->name;
				}
				$product->setAttribute('name', $name);
			}

			// fix description field, must be an array
			if (!empty($product->description) && !is_array($product->description)) {
				$description = [];
				foreach (Lang::getAvailableLanguages() as $key => $langName) {
					$description[$key] = $product->description;
				}
				$product->setAttribute('description', $description);
			}

			// set product state. If it has minimal info, we set the product as public
			if (empty($product->product_state)) {
//				if (empty($product->categories) || empty($product->deviser_id) || empty($product->name) || empty($product->price_stock) || empty($product->mediaFiles) || empty($product->mediaFiles->photos)) {
//					$product->product_state = Product2::PRODUCT_STATE_DRAFT;
//				} else {
//					$product->product_state = Product2::PRODUCT_STATE_ACTIVE;
//				}
				// for the moment all products are public
				$product->product_state = Product::PRODUCT_STATE_ACTIVE;
			}

			// available on price_stock
			$priceStock = $product->price_stock;
			foreach ($priceStock as $k => $item) {
				if (!isset($item['available'])) {
					$priceStock[$k]['available'] = true;
				}
			}
			$product->setAttribute('price_stock', $priceStock);

			// bespoke default
			if (empty($product->bespoke) || !is_array($product->bespoke)) {
				$bespoke = ['type' => Bespoke::NO];
				$product->setAttribute('bespoke', $bespoke);
			}

			// madetoorder...
			$madeToOrder = $product->madetoorder;
			$value = isset($madeToOrder['value']) ? $madeToOrder['value'] : null;
			$type = isset($madeToOrder['type']) ? $madeToOrder['type'] : null;
			if (!empty($value)) {
				$madeToOrder['type'] = MadeToOrder::DAYS;
				$madeToOrder['value'] = (int)$value;
			} else {
				$madeToOrder['type'] = MadeToOrder::NONE;
				unset($madeToOrder['value']);
			}
			$product->setAttribute('madetoorder', $madeToOrder);

			// save make other fixes (created_at and updated_at dates, short_ids on price&stock....)
			$product->save(false); // false parameter for prevent validation

		}
		Yii::$app->response->setStatusCode(200); // Success, without body
	}

	public function actionFixProductsWithNoDeviser()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		/* @var Product[] $products */
		$products = Product::findSerialized();
		foreach ($products as $product) {
			if (empty($product->deviser_id)) {

				echo Utils::l($product->name).' - '.$product->short_id.' => Deleted because empty deviser_id<br />';
				$product->delete();

			} else {
				
				$deviser = Person::findOneSerialized($product->deviser_id);

				if (empty($deviser)) {

					echo Utils::l($product->name) . ' - ' . $product->short_id . ' => Deleted because deviser_id ' . $product->deviser_id . ' does not exists<br />';
					$product->delete();

				} elseif ($deviser->account_state != Person::ACCOUNT_STATE_ACTIVE) {

					echo Utils::l($product->name) . ' - ' . $product->short_id . ' => Changed state to draft because deviser_id ' . $product->deviser_id . ' is not active<br />';
					// If deviser is not active, disable the product
					// Update directly in low level, to avoid no desired behaviors of ActiveRecord
					/** @var Collection $collection */
					Yii::$app->mongodb->getCollection('product')->update(
						[
							'short_id' => $product->short_id
						],
						[
							'product_state' => Product2::PRODUCT_STATE_DRAFT,
						]
					);

				}
			}
		}
	}

}
