<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\models\Category;
use app\models\Person;
use app\models\Product;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Product[] $products */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = Yii::t('app/public','TIMELINE');
Yii::$app->opengraph->title = $this->title;

?>

TIMELINE