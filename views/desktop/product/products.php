<?php

/* @var \app\models\Product2[] $products */
/* @var \app\models\Product2[][] $moreWork */
$this->title = 'Works - Todevise';

\app\assets\desktop\pub\ProductsAsset::register($this);

?>


<section class="grid-wrapper">
	<div class="container">
		<p class="text-primary"><?=$total?> results <?=($text ? 'of <b>'.$text.'</b>' : '')?></p>
		<div id="macy-container"><?=$htmlWorks?></div>
	</div>
	<form id="formPagination">
		<input type="hidden" id="text" name="text" value="<?=$text?>" />
		<input type="hidden" id="page" name="page" value="<?=$page?>" />
		<input type="hidden" id="more" name="more" value="<?=$more?>" />
	</form>
</section>