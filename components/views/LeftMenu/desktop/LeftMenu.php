<?php
use app\models\Lang;
use yii\helpers\Html;
use app\helpers\Utils;
use app\components\assets\leftMenuAsset;


LeftMenuAsset::register($this);

/* @var $lang string */
/* @var $categories ArrayObject */
?>

<?php

	function generate_ul ($categories, $short_id, $lang) {
		if (!array_key_exists($short_id, $categories)) {
			return '';
		} else {
			$children = $categories[$short_id];
		}

		$html = '<ul class="absolute">';
		$html .= '<div class="bg absolute dark-black"></div>';

		foreach ($children as $category) {
			$html .= '<li class="funiv_ultra fc-fff">';
			$html .= Html::a(
				Utils::getValue($category["name"], $lang, array_keys(Lang::EN_US)[0]),
				['public/category', "category_id" => $category['short_id'], "slug" => $category['slug']],
				['class' => 'fc-fff no-padding']
			);
			$html .= generate_ul($categories, $category['short_id'], $lang);
			$html .= '</li>';
		}

		$html .= '</ul>';
		return $html;
	}
?>

<ul class="list-group flex-prop-1 no-vertical-margin">
	<?php
		foreach($categories as $category) {
			if (array_key_exists('path', $category) && $category['path'] === '/') {
				//TODO: Add 'active' class to 'li' element if URL path is pointing to the current category.
				echo '<li class="funiv_ultra fs1-357 fc-fff">';
				echo Html::a(
					Utils::getValue($category["name"], $lang, array_keys(Lang::EN_US)[0]),
					['public/category', "category_id" => $category['short_id'], "slug" => $category['slug']],
					['class' => 'fc-fff']
				);
				echo generate_ul($categories, $category['short_id'], $lang);
				echo '</li>';
			}
		}
	?>
</ul>
