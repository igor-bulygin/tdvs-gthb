<?php
use yii\helpers\Url;

?>

<div class="product flex-inline" width="$model['width']" height="$model['height']">
	<a href="<?= Url::to(["public/product", "slug" => $model['slug'], 'category_id' => $model['category_id'], "product_id" => $model['product_id']]) ?>">
		<img src="<?= $model['img'] ?>" alt="" />
		<div class="data absolute flex flex-column white">
			<span class="funiv fc-1c1919 fs1"><?= $model['name'] ?></span>
			<span class="funiv_ultra fc-9a fs1-143"><?= $model['price'] ?></span>
		</div>
	</a>
</div>
