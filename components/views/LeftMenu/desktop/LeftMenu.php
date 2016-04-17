<?php
use yii\helpers\Html;
use app\helpers\Utils;
use app\components\assets\leftMenuAsset;


LeftMenuAsset::register($this);

/* @var $lang string */
/* @var $categories ArrayObject */
?>

<?php

	function generate_ul ($categories, $short_id) {
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
				Utils::l($category['name']),
				['public/category', "category_id" => $category['short_id'], "slug" => $category['slug']],
				['class' => 'fc-fff no-padding']
			);
			$html .= generate_ul($categories, $category['short_id']);
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
				echo '<li class="funiv_ultra fs1-357 fc-fff">';
				echo Html::a(
					Utils::l($category['name']),
					['public/category', "category_id" => $category['short_id'], "slug" => $category['slug']],
					['class' => 'fc-fff']
				);
				echo generate_ul($categories, $category['short_id']);
				echo '</li>';
			}
		}
	?>
</ul>
