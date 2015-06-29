<?php
use app\helpers\Utils;
use app\models\Lang;
use app\components\assets\categoriesNavbarAsset;

categoriesNavbarAsset::register($this);

/* @var $lang string */
/* @var $categories ArrayObject */
?>

<ul class="list-group">
	<?php foreach($categories as $category) { ?>
		<li class="list-group-item">
			<?php echo Utils::getValue($category["name"], $lang, array_keys(Lang::EN_US)[0]); ?>
		</li>
	<?php } ?>
</ul>