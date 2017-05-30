<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Country;
use Yii;
use yii\web\NotFoundHttpException;

class CountryController extends AppPublicController {

	public function actionView($countryCode)
	{
		Country::setSerializeScenario(Country::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Country $country */
		$country = Country::findOneSerialized($countryCode);
		if (empty($country)) {
			throw new NotFoundHttpException('Country not found');
		}

		return $country;
	}

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Country::setSerializeScenario(Country::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 99999);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

	    $countries = Country::findSerialized([
		    "name" => Yii::$app->request->get("name"), // search only in name attribute
		    "person_type" => Yii::$app->request->get("person_type", null),
		    "only_with_boxes" => Yii::$app->request->get("only_with_boxes", null),
		    "only_with_stories" => Yii::$app->request->get("only_with_stories", null),
		    "limit" => $limit,
		    "offset" => $offset,
	    ]);

	    return [
		    "items" => $countries,
		    "meta" => [
			    "total_count" => count($countries),
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }

    public function actionWorldwide() {

		// set the scenario to serialize objects
		Country::setSerializeScenario(Country::SERIALIZE_SCENARIO_PUBLIC);

    	$continents = [];
    	foreach (Country::CONTINENTS as $code => $name) {
    		if ($code != Country::WORLD_WIDE) {
				$continent = new \stdClass();
				$continent->code = $code;
				$continent->name = $name;
				$continent->path = Country::WORLD_WIDE.'/'.$code;
				$continent->items = Country::findSerialized(['continent' => $code]);
				$continents[] = $continent;
			}
		}

		$worldwide = new \stdClass();
		$worldwide->code = Country::WORLD_WIDE;
		$worldwide->name = Country::CONTINENTS[Country::WORLD_WIDE];
		$worldwide->path = Country::WORLD_WIDE;
		$worldwide->items = $continents;

		return $worldwide;
	}

}

