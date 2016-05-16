<?php
use yii\helpers\Url;

?>

<div class="product_box flex-inline">
	<a href="<?= Url::to(["public/product", "slug" => @$model['slug'], 'category_id' => @$model['categories'][0], "product_id" => $model['short_id']]) ?>">
		<img src="<?= @$model['img'] ?>" alt="" />
		<div class="data absolute flex flex-column white">
			<span class="funiv fc-1c1919 fs1 marquee"><?= @$model['name'] ?></span>
			<span class="funiv_ultra fc-9a fs1-143"><?= @$model['category'] ?></span>
		</div>
	</a>
</div>
