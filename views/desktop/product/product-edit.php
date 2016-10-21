<?php
use app\assets\desktop\pub\Product2Asset;
use app\assets\desktop\pub\ProductDetailAsset;
use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Category;
use app\models\Person;
use app\models\PersonVideo;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

PublicCommonAsset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = $product->name . ' - Todevise';

?>

<div>Product edit</div>