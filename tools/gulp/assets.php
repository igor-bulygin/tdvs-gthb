<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
Yii::setAlias('@webroot', __DIR__ . '/../../web');
Yii::setAlias('@web', '/');

return [

	// Adjust command/callback for JavaScript files compressing:
//    'jsCompressor' => 'java -jar tools/compiler.jar --compilation_level SIMPLE_OPTIMIZATIONS --js {from} --js_output_file {to}',
	'jsCompressor' => 'tools/gulp/node_modules/.bin/gulp compress-js --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',

    // Adjust command/callback for CSS files compressing:
//    'cssCompressor' => 'java -jar tools/yuicompressor.jar --type css {from} -o {to}',
	'cssCompressor' => 'tools/gulp/node_modules/.bin/gulp compress-css --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',

	// Whether to delete asset source after compression:
	'deleteSource' => false,

	// The list of asset bundles to compress:
    'bundles' => [
//    	'app\assets\AppAsset',
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\desktop\admin\GlobalAsset',
    ],

	// Asset bundle for compression output:
    'targets' => [
		'app' => [
			'class' => 'yii\web\AssetBundle',
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/assets',
			'js' => 'app.js',
			'css' => 'app.css',
			'depends' => [
				'app\assets\AppAsset',
			],
		],
		'public' => [
			'class' => 'yii\web\AssetBundle',
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/assets',
			'js' => 'public.js',
			'css' => 'public.css',
			'depends' => [
				'app\assets\desktop\pub\PublicCommonAsset',
			],
		],
		'admin' => [
			'class' => 'yii\web\AssetBundle',
			'basePath' => '@webroot/assets',
			'baseUrl' => '@web/assets',
			'js' => 'admin.js',
			'css' => 'admin.css',
			'depends' => [
//				'app\assets\desktop\admin\GlobalAsset',
			],
		],
    ],

	// Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
		'appendTimestamp' => true,
		'bundles' => [
			'yii\bootstrap\BootstrapAsset' => [
				//'sourcePath' => '',
				'css' => []
			],
		],
    ],
];