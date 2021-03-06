<?php
namespace app\helpers;

use app\models\Category;
use app\models\Lang;
use app\models\Loved;
use Exception;
use Thumbor\Url\Builder;
use Traversable;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class Utils
{
	public static $loveds = [];
	/**
	 * Return all the available languages
	 * @return mixed
	 */
	public static function availableLangs()
	{
		return Yii::$app->languagepicker->languages;
	}

	/**
	 * Generate a random ID (YouTube-like) useful for slugs.
	 * @param int $length Desired length of ID
	 * @return string
	 */
	public static function shortID($length = 6)
	{
		$l = floor($length / 2);
		$e = $length % 2;
		$random = bin2hex(openssl_random_pseudo_bytes($l));
		if ($e === 1) {
			$random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
		}
		return $random;
	}

	/**
	 * Returns true if the current controller/action is equal to the passed URL.
	 * @param string URL to compare to. Example: 'admin/deviser', 'deviser/product', etc...
	 * @return bool
	 */
	public static function compareURL($url)
	{
		$queryParams = Yii::$app->request->queryParams;
		array_walk($queryParams, function (&$v, $k) {
			$v = null;
		});

		return Url::current($queryParams) === Url::toRoute($url);
	}

	/**
	 * Guess file extension using magic mime.
	 * @param $path
	 * @return string
	 */
	public static function magic_guess_extension($path)
	{
		$file_info = new \finfo(FILEINFO_MIME);
		$mime_type = $file_info->buffer(file_get_contents($path));
		$mime_type = explode(";", $mime_type);
		return count($mime_type) === 2 ? $mime_type[0] : "";
	}

	/**
	 * get the appropriate file extension for a mime type
	 *
	 * @param string $mimeType
	 * @return string
	 */
	public static function getFileExtensionFromMimeType($mimeType)
	{
		$extensions = [
			'image/png' => 'png',
			'image/jpeg' => 'jpg',
			'image/jpg' => 'jpg',
			'application/pdf' => 'pdf',
		];

		// Add as many other Mime Types / File Extensions as you like
		return array_key_exists($mimeType, $extensions) ? $extensions[$mimeType] : 'tmp';
	}

	/**
	 * Save a file to the path extracted from $alias, with the name $name
	 * or a generated string in the format YYYY-MM-DD-HH-MM-xxxxx.{ext}
	 * @param $path
	 * @param null $filename
	 * @return bool|string
	 */
	public static function savePostedFile($path, $filename = null)
	{
		$f = $_FILES['file'];

		Utils::mkdir($path);

		$ext = Utils::getFileExtensionFromMimeType($f["type"]);

		if($filename === null) {
			$filename = Utils::cfile($path, $ext);
		} else {
			Utils::touch( Utils::join_paths($path, "${filename}.${ext}") );
		}
		$path = Utils::join_paths($path, "${filename}.${ext}");

		$res = @move_uploaded_file($f["tmp_name"], $path);
		if($res === true) {
			return "${filename}.${ext}";
		} else {
			return false;
		}
	}

	/**
	 * Get the base64 (inline) string of a (image) file.
	 * @param $path
	 * @return string
	 */
	public static function fileToBase64($path)
	{
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		return 'data:image/' . $type . ';base64,' . base64_encode($data);
	}

	/**
	 * Create a recursive path of folders.
	 * @param $path
	 * @return bool
	 */
	public static function mkdir($path)
	{
		if (file_exists($path) === true) {
			return true;
		}
		umask(0000);
		return mkdir($path, 0777, true);
	}

	/**
	 * Delete a folder recursively.
	 */
	public static function rmdir($path)
	{
		try {
			$dir = @scandir($path);
			if ($dir === false) return;
			$files = array_diff($dir, array('.', '..'));
			foreach ($files as $file) {
				(is_dir("$path/$file")) ? rmdir("$path/$file") : @unlink("$path/$file");
			}
		} catch (Exception $e) {
		}
		return rmdir($path);
	}

	/**
	 * Create a file. Returns false if the file already exists or if it failed to create it.
	 * @param $path
	 * @return bool
	 */
	public static function touch($path, $mode = 0777)
	{
		if (file_exists($path) === true) {
			return false;
		}
		$fp = fopen($path, "c");
		if ($fp === false) {
			return false;
		} else {
			fclose($fp);
			chmod($path, $mode);
			return true;
		}
	}

	/**
	 * Create a new file with the datetime and a 5 chars long string in the given
	 * path and with the given extension. Returns "" if it failed for some reason
	 * or the name of the file (without the extension) if it succeeded.
	 * @param $path
	 * @param $ext
	 * @return string
	 */
	public static function cfile($path, $ext)
	{
		$date = new \DateTime();
		$s = $date->format(Yii::$app->params["php_fmt_datatime"]);
		$s = preg_replace("/[ :]/", "-", $s);

		Utils::mkdir($path);

		$r = Utils::shortID(5);
		$fp = Utils::join_paths($path, "${s}-${r}.$ext");
		error_log($fp, 4);

		while (file_exists($fp)) {
			$r = Utils::shortID(5);
			$fp = Utils::join_paths($path, "${s}-${r}.$ext");
		}

		return Utils::touch($fp) === false ? "" : "${s}-${r}";
	}

	/**
	 * Return a path, result of joining all the passed arguments, suitable for the current OS.
	 * @return mixed
	 */
	public static function join_paths()
	{
		$paths = array();

		foreach (func_get_args() as $arg) {
			if ($arg !== '') {
				$paths[] = $arg;
			}
		}

		return preg_replace('#/+#', '/', join('/', $paths));
	}

	/**
	 * Remove all keys contained in $allowed that match the object
	 * created by JSON-decoding $data.
	 * @param $data Array of data
	 * @param $allowed Array containing the keys that shouldn't be removed
	 * @return array
	 */
	public static function removeAllExcept($data, $allowed)
	{
		return array_intersect_key($data, array_flip($allowed));
	}

	/**
	 * Get the value stored at the end of a deeply nested array, following
	 * the given path (array of keys).
	 * Example:
	 *
	 * $x = [
	 *     "a" => [
	 *         "b" => [
	 *             "c" => [
	 *                 42
	 *             ]
	 *         ]
	 *     ]
	 * ];
	 *
	 * getValueFromPath($x, ["a", "b", "c"])
	 * @param $array
	 * @param $path
	 * @return mixed
	 */
	public static function getValueFromPath($array, $path)
	{
		$temp = &$array;

		foreach ($path as $key) {
			$temp =& $temp[$key];
		}

		return $temp;
	}

	/**
	 * Set the value at the end of a deeply nested array, following the
	 * given path (array of keys).
	 * Example:
	 *
	 * $x = [];
	 * setValueForPath($x, ["a", "b", "c"], 42);
	 * @param $array
	 * @param $path
	 * @param $value
	 */
	public static function setValueForPath(&$array, $path, $value)
	{
		$temp = &$array;

		foreach ($path as $key) {
			$temp =& $temp[$key];
		}

		$temp = $value;
	}

	/**
	 * Append the value to an array at the end of a deeply nested array, following the
	 * given path (array of keys).
	 * Example:
	 *
	 * $x = [];
	 * appendValueForPath($x, ["a", "b", "c"], 42);
	 * @param $array
	 * @param $path
	 * @param $value
	 */
	public static function appendValueToPath(&$array, $path, $value)
	{
		$temp = &$array;

		foreach ($path as $key) {
			$temp =& $temp[$key];
		}

		$temp[] = $value;
	}

	/**
	 * Get a value of an array by a key, or get another value using a default key.
	 * @param $arr
	 * @param $key
	 * @param $default_key
	 * @return mixed
	 */
	public static function getValue($arr, $key, $default_key, $default = "")
	{
		if (isset($arr[$key])) {
			return $arr[$key];
		} else if (isset($arr[$default_key])) {
			return $arr[$default_key];
		} else {
			return $default;
		}
	}

	/**
	 * The equivalent of 'getValue', but for language-related values.
	 * It will try to get the value in the currently active language and
	 * will fallback to English.
	 */
	public static function l($arr)
	{
		return Utils::getValue($arr, Yii::$app->language, array_keys(Lang::EN_US_DESC)[0]);
	}

	/**
	 * The equivalent of 'l', but for arrays of data.
	 * Takes an array of objects and sets '$key' to the
	 * value returned by 'l'.
	 */
	public static function l_collection(&$arr, $key)
	{
		foreach ($arr as $index => &$value) {
			$value[$key] = @Utils::l($value[$key]);
		}
	}

	/**
	 * Translate "translatable" attributes of a single Model, or a Model collection
	 *
	 * @param mixed $mix
	 * @return mixed
	 */
	public static function translate($mix)
	{
		if ($mix instanceof CActiveRecord) {
			Utils::translateModel($mix);
		} elseif (is_array($mix) || $mix instanceof Traversable) {
			foreach ($mix as $model) {
				if ($model instanceof CActiveRecord) {
					Utils::translateModel($model);
				}
			}
		}

		return $mix;
	}

	/**
	 * Translate "translatable" attributes of a single Model
	 *
	 * @param CActiveRecord $model
	 * @return CActiveRecord
	 */
	public static function translateModel(CActiveRecord $model)
	{
		foreach ($model::$translatedAttributes as $translatedAttribute) {
			Utils::translateModelAttribute($model, $translatedAttribute);
		}

		return $model;
	}


	/**
	 * Translate a single attribute of a Model.
	 *
	 * "translated attribute" can be a sub element of an array. To specify sub elements, use "." separator,
	 * for example: "faqs.questions"
	 *
	 * @param CActiveRecord $model
	 * @param string $translatedAttribute
	 */
	public static function translateModelAttribute(CActiveRecord $model, $translatedAttribute)
	{
		$particles = explode('.', $translatedAttribute);
		$rootParticle = $particles[0];
		$otherParticles = array_slice($particles, 1, count($particles));
		$translatedValue = null;

		if (count($particles) == 1) {
			// want to translate this "particle" / "attribute"
			$translatedValue = Utils::getValue($model->getAttribute($rootParticle), Yii::$app->language, array_keys(Lang::EN_US_DESC)[0]);
		} elseif (count($particles) > 1) {
			// want to translate a sub attribute. now, are stored in arrays, not custom models
			$value = $model->getAttribute($rootParticle);
			if ($value) {
				$translatedValue = Utils::translateArrayAttribute($value, implode('.', $otherParticles));
			} else {
				$translatedValue = [];
			}
		}

		$model->setAttribute($rootParticle, $translatedValue);
	}

	/**
	 * Translate a single attribute of a Model
	 *
	 * @param array $arr
	 * @param string $translatedAttribute
	 * @return mixed
	 */
	public static function translateArrayAttribute(array &$arr, $translatedAttribute)
	{
		$particles = explode('.', $translatedAttribute);
		$rootParticle = $particles[0];
		$otherParticles = array_slice($particles, 1, count($particles));

		if (count($particles) == 1) {
			// want to translate this "particle" / "attribute"
			if (array_key_exists($rootParticle, $arr)) {
				$arr[$rootParticle] = Utils::getValue($arr[$rootParticle], Yii::$app->language, array_keys(Lang::EN_US_DESC)[0]);
			}
			// can be a set of items
			foreach ($arr as $key => &$attr) {
				if (is_array($attr) && array_key_exists($rootParticle, $attr)) {
					$attr[$rootParticle] = Utils::getValue($attr[$rootParticle], Yii::$app->language, array_keys(Lang::EN_US_DESC)[0]);
				}
			}
		} elseif (count($particles) > 1) {
			// want to translate a sub attribute
			if (array_key_exists($rootParticle, $arr)) {
				Utils::translateArrayAttribute($arr[$rootParticle], implode('.', $otherParticles));
			}
			// can be a set of items
			foreach ($arr as &$attr) {
				if (is_array($attr) && array_key_exists($rootParticle, $attr)) {
					Utils::translateArrayAttribute($attr[$rootParticle], implode('.', $otherParticles));
				}
			}
		}

		return $arr;
	}


	/**
	 * Compose an array to use as condition in where(), in ActiveQuery, like:
	 *
	 * $query->andFilterWhere(
	 *      ['or',
	 *          ['LIKE', 'name.en-US', "my filter"],
	 *          ['LIKE', 'name.es-ES', "my filter"],
	 *      ]
	 * );
	 *
	 * @param string $fieldName
	 * @param $value
	 * @param string $operator
	 * @return array
	 */
	public static function getFilterForTranslatableField($fieldName, $value, $operator = 'LIKE')
	{
		$nameFilter = ['or'];
		foreach (Lang::getAvailableLanguages() as $key => $name) {
			$field = ($fieldName . "." . $key);
			$nameFilter[] = [$operator, $field, $value];
		}
		return $nameFilter;
	}


	/**
	 * Convert a stdClass object to a multidimensional array.
	 * @param $d
	 * @return array
	 */
	public static function objectToArray($d)
	{
		if (is_object($d)) {
			// Gets the properties of the given object with get_object_vars function
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			/*
			 * Return array converted to object
			 * Using __FUNCTION__ (Magic constant)
			 * for recursive call
			 */
			return array_map(__FUNCTION__, $d);
		} else {
			return $d;
		}
	}

	/**
	 * Convert a multidimensional array to a stdClass object.
	 * @param $d
	 * @return object
	 */
	public static function arrayToObject($d)
	{
		if (is_array($d)) {
			/*
			 * Return array converted to object
			 * Using __FUNCTION__ (Magic constant)
			 * for recursive call
			 */
			return (object)array_map(__FUNCTION__, $d);
		} else {
			// Return object
			return $d;
		}
	}

	/**
	 * Convert under_score type array's keys to camelCase type array's keys
	 * @param   array $array array to convert
	 * @param   array $arrayHolder parent array holder for recursive array
	 * @return  array   camelCase array
	 */
	public static function camelCaseKeys($array, $arrayHolder = array())
	{
		$camelCaseArray = !empty($arrayHolder) ? $arrayHolder : array();
		foreach ($array as $key => $val) {
			$newKey = @explode('_', $key);
			array_walk($newKey, create_function('&$v', '$v = ucwords($v);'));
			$newKey = @implode('', $newKey);
			$newKey{0} = strtolower($newKey{0});
			if (!is_array($val)) {
				$camelCaseArray[$newKey] = $val;
			} else {
				$camelCaseArray[$newKey] = Utils::camelCaseKeys($val, $camelCaseArray[$newKey]);
			}
		}
		return $camelCaseArray;
	}

	/**
	 * Convert camelCase type array's keys to under_score+lowercase type array's keys
	 * @param   array $array array to convert
	 * @param   array $arrayHolder parent array holder for recursive array
	 * @return  array   under_score array
	 */
	public static function underscoreKeys($array, $arrayHolder = array())
	{
		$underscoreArray = !empty($arrayHolder) ? $arrayHolder : array();
		foreach ($array as $key => $val) {
			$newKey = preg_replace('/[A-Z]/', '_$0', $key);
			$newKey = strtolower($newKey);
			$newKey = ltrim($newKey, '_');
			if (!is_array($val)) {
				$underscoreArray[$newKey] = $val;
			} else {
				$underscoreArray[$newKey] = Utils::underscoreKeys($val, $underscoreArray[$newKey]);
			}
		}
		return $underscoreArray;
	}

	/**
	 * Takes a url-encoded JSON string and converts it to a PHP object (JSON representation).
	 * If the input string is null or something goes wrong, an empty object (array) will be returned.
	 */
	public static function stringToFilter($string)
	{
		if ($string === null) return [];

		$string = urldecode($string) ?: null;
		if ($string === null) {
			return [];
		} else {
			return Json::decode($string);
		}
	}

	public static function getDefaultImage($width, $height)
	{
		return Utils::url_scheme() . Utils::thumborize("imgs/img-default.jpg")->fitIn($width, $height)->addFilter('fill', 'e9e9e9');
	}

	/**
	 * @param $img_path
	 *
	 * @return Builder
	 */
	public static function thumborize($img_path)
	{
		// TODO obtain this from .env file
		$server = getenv("THUMBOR_SERVER");
		$secret = getenv("THUMBOR_SECURITY_KEY");
		return Builder::construct($server, $secret, $img_path);
	}

	public static function url_scheme()
	{
		if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			return "https://";
		} else {
			return "http://";
		}
	}

	/**
	 * Get images to use in header slider, for a specific category
	 *
	 * @param Category $category
	 * @return array
	 */
	public static function getBannerImages($category = null)
	{
		if (isset($category)) {
			$banner = $category->getBannerImage();
		}
		if (isset($banner)) {
			return [
					['img' => $banner, 'alt' => Utils::l($category->name), "active" => true],
			];
		}
		return Yii::$app->params['index_banners'];
	}

	/**
	 * Parse a Youtube URL video, and return the embedded url version, to use in a <iframe>
	 * (No external library found, to parse the url, without have to register and use API KEY)
	 *
	 * @param $urlYoutubeVideo
	 * @return string
	 */
	public static function getUrlEmbeddedYoutubePlayer($urlYoutubeVideo)
	{
		$videoId = Utils::getYoutubeVideoIdByUrl($urlYoutubeVideo);
		return sprintf('https://www.youtube.com/embed/%s', $videoId);
	}

	/**
	 * Extract the video id from a URL. It expect the video id as a query parameter named "v", like:
	 * "https://www.youtube.com/watch?v=7iagRgdL3kU" or "'https://youtu.be/01p8XH3sq85"
	 *
	 * (No external library found, to parse the url, without have to register and use their API KEY)
	 *
	 * @param $urlYoutubeVideo
	 * @return string|null
	 */
	public static function getYoutubeVideoIdByUrl($urlYoutubeVideo)
	{
		// New way using regexp: (thanks to http://stackoverflow.com/a/6382259)
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $urlYoutubeVideo, $match)) {
			return $match[1];
		}
		return null;
	}

	/**
	 * Returns TRUE if the product is loved by de person especified by params
	 *
	 * @param string $product_id
	 * @param string $person_id
	 *
	 * @return bool
	 */
	public static function productLovedByPerson($product_id, $person_id) {
		if (!isset(static::$loveds[$person_id])) {
			$loveds = Loved::findSerialized(['person_id' => $person_id]);

			$product_ids = [];
			foreach ($loveds as $loved) {
				$product_ids[] = $loved->product_id;
			}
			static::$loveds[$person_id] = $product_ids;
		}

		return in_array($product_id, static::$loveds[$person_id]);
	}

	/**
	 * Returns TRUE if the box is loved by de person especified by params
	 *
	 * @param string $box_id
	 * @param string $person_id
	 *
	 * @return bool
	 */
	public static function boxLovedByPerson($box_id, $person_id) {
		if (!isset(static::$loveds[$person_id])) {
			$loveds = Loved::findSerialized(['person_id' => $person_id]);

			$box_ids = [];
			foreach ($loveds as $loved) {
				$box_ids[] = $loved->box_id;
			}
			static::$loveds[$person_id] = $box_ids;
		}

		return in_array($box_id, static::$loveds[$person_id]);
	}

	/**
	 * Returns TRUE if the post is loved by de person especified by params
	 *
	 * @param string $post_id
	 * @param string $person_id
	 *
	 * @return bool
	 */
	public static function postLovedByPerson($post_id, $person_id) {
		if (!isset(static::$loveds[$person_id])) {
			$loveds = Loved::findSerialized(['person_id' => $person_id]);

			$post_ids = [];
			foreach ($loveds as $loved) {
				$post_ids[] = $loved->post_id;
			}
			static::$loveds[$person_id] = $post_ids;
		}

		return in_array($post_id, static::$loveds[$person_id]);
	}

	/**
	 * Returns TRUE if the timeline is loved by de person especified by params
	 *
	 * @param string $timeline_id
	 * @param string $person_id
	 *
	 * @return bool
	 */
	public static function timelineLovedByPerson($timeline_id, $person_id) {
		if (!isset(static::$loveds[$person_id])) {
			$loveds = Loved::findSerialized(['person_id' => $person_id]);

			$timeline_ids = [];
			foreach ($loveds as $loved) {
				$timeline_ids[] = $loved->timeline_id;
			}
			static::$loveds[$person_id] = $timeline_ids;
		}

		return in_array($timeline_id, static::$loveds[$person_id]);
	}

	/**
	 * Returns TRUE when the error represents a "required" error
	 *
	 * @param array|string $error
	 *
	 * @return bool
	 */
	public static function isRequiredError($error)
	{
		//TODO: Fix this! Find other way to determine if was a "required" field
		if (is_array($error)) {
			$error = $error[0];
		}
		if (strpos($error, 'cannot be blank') !== false ||
			strpos($error, 'no puede estar vacío') !== false ||
			strpos($error, 'can not be empty') !== false
		) {
			return true;
		}

		return false;
	}
}
