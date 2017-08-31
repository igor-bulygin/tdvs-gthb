<?php

return [
	'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',

	'messagePath' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'messages',

	'languages' => array_keys(require(__DIR__ . '/langs.php')),

	'translator' => 'Yii::t',

	'sort' => true,

	'removeUnused' => true,

	'overwrite' => true,

	'format' => 'php',

	// array, list of patterns that specify which files/directories should NOT be processed.
	// If empty or not set, all files/directories will be processed.
	// A path matches a pattern if it contains the pattern string at its end. For example,
	// '/a/b' will match all files and directories ending with '/a/b';
	// the '*.svn' will match all files and directories whose name ends with '.svn'.
	// and the '.svn' will match all files and directories named exactly '.svn'.
	// Note, the '/' characters in a pattern matches both '/' and '\'.
	// See helpers/FileHelper::findFiles() description for more details on pattern matching rules.
	'except' => [
		'.svn',
		'.git',
		'.gitignore',
		'.gitkeep',
		'.hgignore',
		'.hgkeep',
		'/assets',
		'/config',
		'/messages',
		'/vendor'
	],

	// array, list of patterns that specify which files (not directories) should be processed.
	// If empty or not set, all files will be processed.
	// Please refer to "except" for details about the patterns.
	// If a file/directory matches both a pattern in "only" and "except", it will NOT be processed.
	'only' => ['*.php'],
];
