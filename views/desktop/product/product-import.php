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

        <div class="fs3-857 funiv_thin fs-upper fc-6d"><?=Yii::t('app/import', 'IMPORT_WORKS_TITLE')?></div>

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
                    <label for="import_source"><?=Yii::t('app/import', 'IMPORT_SOURCE')?></label>
                    <select class="form-control" name="source" id="import_source">
                        <option value="shopify"     <?=((isset($data) && isset($data['source']) && $data['source'] == 'shopify') ? 'selected' : null)?>>Shopify</option>
                        <option value="magento"     <?=((isset($data) && isset($data['source']) && $data['source'] == 'magento') ? 'selected' : null)?>>Magento</option>
                        <option value="prestashop"  <?=((isset($data) && isset($data['source']) && $data['source'] == 'prestashop') ? 'selected' : null)?>>PrestaShop</option>
                    </select>
                </div>
                <div class="form-group product-import-url" id="import_url">
                    <label for="source_url"><?=Yii::t('app/import', 'SOURCE_SHOP_URL')?></label>
                    <input id="source_url" class="form-control" type="text" name="source-url" value="<?=((isset($data) && isset($data['source-url'])) ? $data['source-url'] : '')?>" placeholder="https://my-magento-shop.com" />
                </div>
                <div class="form-group">
                    <label for="lang"><?=Yii::t('app/import', 'IMPORT_LANGUAGE')?></label>
                    <select class="form-control" name="lang" id="lang">
                        <option value="en-US" <?=((isset($data) && isset($data['lang']) && $data['lang'] == 'en-US') ? 'selected' : null)?>><?=Yii::t('app/import', 'IMPORT_LANG_ENGLISH')?></option>
                        <option value="es-ES" <?=((isset($data) && isset($data['lang']) && $data['lang'] == 'es-ES') ? 'selected' : null)?>><?=Yii::t('app/import', 'IMPORT_LANG_SPANISH')?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="csv"><?=Yii::t('app/import', 'CSV_FILE')?></label>
                    <input type="file" name="csv" id="csv" class="upload-csv-input" data-label="<?=Yii::t('app/import', 'UPLOAD_FILE')?>" />
                </div>
                <div class="no-url-alert alert alert-warning alert-dismissable">
                    <?=Yii::t('app/import', 'NO_SOURCE_URL_ALERT')?>
                </div>
                <button class="btn btn-auto btn-red auto-center" id="submit-form"><?=Yii::t('app/import', 'IMPORT_SUBMIT')?></button>
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>" />
            </form>
        </div>
    </div>
</div>