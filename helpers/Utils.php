<?php
namespace app\helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;

class Utils {

	/**
	 * Return all the available languages
	 * @return mixed
	 */
	public static function availableLangs() {
		return Yii::$app->languagepicker->languages;
	}

	/**
	 * Generate a random ID (YouTube-like) useful for slugs.
	 * @param int $length Desired length of ID
	 * @return string
	 */
	public static function shortID($length = 6) {
		$l = floor($length / 2);
		$e = $length % 2;
		$random = bin2hex(openssl_random_pseudo_bytes($l));
		if($e === 1){
			$random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
		}
		return $random;
	}

	/**
	 * Returns true if the current controller/action is equal to the passed URL.
	 * @param string URL to compare to. Example: 'admin/deviser', 'deviser/product', etc...
	 * @return bool
	 */
	public static function compareURL($url) {
		$queryParams = Yii::$app->request->queryParams;
		array_walk($queryParams, function(&$v, $k){
			$v = null;
		});

		return Url::current($queryParams) === Url::toRoute($url);
	}

	/**
	 * Guess file extension using magic mime.
	 * @param $path
	 * @return string
	 */
	public static function magic_guess_extension($path) {
		$file_info = new \finfo(FILEINFO_MIME);
		$mime_type = $file_info->buffer(file_get_contents($path));
		$mime_type = explode(";", $mime_type);
		return count($mime_type) === 2 ? $mime_type[0] : "";
	}

	/**
	 * Get the base64 (inline) string of a (image) file.
	 * @param $path
	 * @return string
	 */
	public static function fileToBase64($path) {
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		return 'data:image/' . $type . ';base64,' . base64_encode($data);
	}

	/**
	 * Create a recursive path of folders.
	 * @param $path
	 * @return bool
	 */
	public static function mkdir($path) {
		if(file_exists($path) === true) {
			return true;
		}
		umask(0000);
		return mkdir($path, 0777, true);
	}

	/**
	 * Create a file. Returns false if the file already exists or if it failed to create it.
	 * @param $path
	 * @return bool
	 */
	public static function touch($path, $mode = 0777) {
		if(file_exists($path) === true) {
			return false;
		}
		$fp = fopen($path, "c");
		if($fp === false) {
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
	public static function cfile($path, $ext){
		$date = new \DateTime();
		$s = $date->format(Yii::$app->params["php_fmt_datatime"]);
		$s = preg_replace("/[ :]/", "-", $s);

		Utils::mkdir($path);

		$r = Utils::shortID(5);
		$fp = Utils::join_paths($path, "${s}-${r}.$ext");
		error_log($fp, 4);

		while(file_exists($fp)) {
			$r = Utils::shortID(5);
			$fp = Utils::join_paths($path, "${s}-${r}.$ext");
		}

		return Utils::touch($fp) === false ? "" : "${s}-${r}";
	}

	/**
	 * Return a path, result of joining all the passed arguments, suitable for the current OS.
	 * @return mixed
	 */
	public static function join_paths() {
		$paths = array();

		foreach (func_get_args() as $arg) {
			if ($arg !== '') { $paths[] = $arg; }
		}

		return preg_replace('#/+#','/',join('/', $paths));
	}

	/**
	 * Remove all keys contained in $allowed that match the object
	 * created by JSON-decoding $data.
	 * @param $data Array of data
	 * @param $allowed Array containing the keys that shouldn't be removed
	 * @return array
	 */
	public static function removeAllExcept($data, $allowed) {
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
	public static function getValueFromPath($array, $path) {
		$temp = &$array;

		foreach($path as $key) {
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
	public static function setValueForPath(&$array, $path, $value) {
		$temp = &$array;

		foreach($path as $key) {
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
	public static function appendValueToPath(&$array, $path, $value) {
		$temp = &$array;

		foreach($path as $key) {
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
	public static function getValue($arr, $key, $default_key) {
		return isset($arr[$key]) ? $arr[$key] : $arr[$default_key];
	}

	/**
	 * Convert a stdClass object to a multidimensional array.
	 * @param $d
	 * @return array
	 */
	public static function objectToArray($d) {
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
	public static function arrayToObject($d) {
		if (is_array($d)) {
			/*
			 * Return array converted to object
			 * Using __FUNCTION__ (Magic constant)
			 * for recursive call
			 */
			return (object) array_map(__FUNCTION__, $d);
		}
		else {
			// Return object
			return $d;
		}
	}

	/**
	 * Convert under_score type array's keys to camelCase type array's keys
	 * @param   array   $array          array to convert
	 * @param   array   $arrayHolder    parent array holder for recursive array
	 * @return  array   camelCase array
	 */
	public static function camelCaseKeys($array, $arrayHolder = array()) {
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
	 * @param   array   $array          array to convert
	 * @param   array   $arrayHolder    parent array holder for recursive array
	 * @return  array   under_score array
	 */
	public static function underscoreKeys($array, $arrayHolder = array()) {
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
}
