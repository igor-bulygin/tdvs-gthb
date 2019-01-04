<?php

use app\assets\desktop\product\GlobalAsset;
use app\models\Person;
use app\models\PersonVideo;
use app\models\Product;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = Yii::t('app/public',
	'CREATE_NEW_WORK_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<div class="container">
    <div class="card mb-0 text-center">

        <div class="fs3-857 funiv_thin fs-upper fc-6d">Import works</div>

        <div class="product-import-form text-left">
            <form action="/deviser/<?= $person->slug ?>/<?= $person->short_id ?>/works/import" method="post" enctype="multipart/form-data" class="form">
                <div class="form-group">
                    <label for="source">Import source</label>
                    <select class="form-control" name="source" id="source">
                        <option value="shopify">Shopify.com</option>
                        <option value="magento">Magento</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lang">Import language</label>
                    <select class="form-control" name="lang" id="lang">
                        <option value="en-US">English</option>
                        <option value="es-ES">Spanish</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="csv">CSV file</label>
                    <input type="file" name="csv" id="csv" /> load file
                </div>
                <input type="submit" value="Import works" name="Import" />
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>" />
            </form>
        </div>
    </div>
</div>