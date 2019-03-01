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
	'IMPORT_WORKS_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
$this->registerJsFile(Yii::getAlias('@web') . '/js/desktop/product/import-form.js', ['depends' => [yii\web\JqueryAsset::className()]]);

?>

<div class="container">
    <div class="card mb-0 text-center">

        <div class="fs3-857 funiv_thin fs-upper fc-6d">Import works</div>

        <div class="product-import-form text-left">

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i><?=Yii::t('app/import', 'ERROR_IMPORT_TITLE')?></h4>
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>

            <form action="/deviser/<?= $person->slug ?>/<?= $person->short_id ?>/works/import" method="post" enctype="multipart/form-data" class="form" id="import_form">
                <div class="form-group">
                    <label for="import_source">Import source</label>
                    <select class="form-control" name="source" id="import_source">
                        <option value="shopify"     <?=((isset($data) && $data['source'] == 'shopify') ? 'selected' : null)?>>Shopify</option>
                        <option value="magento"     <?=((isset($data) && $data['source'] == 'magento') ? 'selected' : null)?>>Magento</option>
                        <option value="prestashop"  <?=((isset($data) && $data['source'] == 'prestashop') ? 'selected' : null)?>>PrestaShop</option>
                    </select>
                </div>
                <div class="form-group product-import-url" id="import_url">
                    <label for="source_url">Source shop main page URL</label>
                    <input id="source_url" class="form-control" type="text" name="source-url" value="<?=((isset($data)) ? $data['source-url'] : '')?>" placeholder="https://my-magento-shop.com" />
                </div>
                <div class="form-group">
                    <label for="lang">Import language</label>
                    <select class="form-control" name="lang" id="lang">
                        <option value="en-US" <?=((isset($data) && $data['lang'] == 'en-US') ? 'selected' : null)?>>English</option>
                        <option value="es-ES" <?=((isset($data) && $data['lang'] == 'es-ES') ? 'selected' : null)?>>Spanish</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="csv">CSV file</label>
                    <input type="file" name="csv" id="csv" class="upload-csv-input" />
                </div>
                <div class="no-url-alert alert alert-warning alert-dismissable">
                    <?=Yii::t('app/import', 'NO_SOURCE_URL_ALERT')?>
                </div>
                <button value="Import works" class="btn btn-auto btn-red auto-center" id="submit-form"><?=Yii::t('app/import', 'IMPORT')?></button>
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>" />
            </form>
        </div>
    </div>
</div>
