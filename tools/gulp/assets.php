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
	'jsCompressor' => 'gulp compress-js --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',

    // Adjust command/callback for CSS files compressing:
//    'cssCompressor' => 'java -jar tools/yuicompressor.jar --type css {from} -o {to}',
	'cssCompressor' => 'gulp compress-css --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',

	// Whether to delete asset source after compression:
	'deleteSource' => false,

	// The list of asset bundles to compress:
    'bundles' => [
//    	'app\assets\AppAsset',
//    	'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\pub\PublicCommonAsset',
//		'yii\web\YiiAsset',
//		'yii\web\JqueryAsset',
    ],

	// Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'all.js',
            'css' => 'all.css',
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