<?php

use app\assets\desktop\deviser\TimelineAsset;
use app\models\Category;
use app\models\Person;
use app\models\Product;

TimelineAsset::register($this);

/** @var Person $person */
/** @var Product[] $products */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = Yii::t('app/public','TIMELINE');
Yii::$app->opengraph->title = $this->title;

?>

To start building your timeline, you need to be a Todevise member