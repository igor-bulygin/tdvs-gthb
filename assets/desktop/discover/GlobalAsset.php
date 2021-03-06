<?php
namespace app\assets\desktop\discover;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/discover/person/discoverCtrl.js',
		'js/desktop/discover/person/results/results.js',
		'js/desktop/discover/person/filters/filters.js',
		'js/desktop/discover/boxes/exploreBoxesCtrl.js',
		'js/desktop/discover/boxes/filters/filters.js',
		'js/desktop/discover/boxes/results/results.js',
		'js/desktop/discover/stories/exploreStoriesCtrl.js',
		'js/desktop/discover/stories/results/results.js',
		'js/desktop/discover/stories/filters/filters.js',
		'js/desktop/discover/products/explore-products.js',
		'js/desktop/discover/products/filters/filters.js',
		'js/desktop/discover/products/results/results.js',
		'js/desktop/discover/mainSearcherCtrl.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\discover\IndexAsset',
	];
}