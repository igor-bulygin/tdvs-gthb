<?php
namespace app\helpers;

use Yii;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\web\Controller;
use app\controllers\ApiController;

class CController extends Controller {
	private $_viewPath = "";
	public $api;

	public function init() {
		/**
		 * This little piece of code is really important. It will decide how the
		 * page should look like, based on the device type and the accessed
		 * controller.
		 *
		 * When the type of device is detected (mobile, tablet or desktop), the
		 * path of the layout would be changed to:
		 *
		 * /views/layout/<device type>/<controller name>.php
		 *
		 * Also, the path in which the views will be searched when a call to
		 * 'render' is made will be changed to:
		 *
		 * /views/<device type>/<controller name>
		 */
		if (\Yii::$app->params['devicedetect']['isMobile']) {
			$this->layout = '/mobile/' . $this->id;
			$this->_viewPath = '@app/views/mobile/' . $this->id;
		} else if(\Yii::$app->params['devicedetect']['isTablet']) {
			$this->layout = '/tablet/' . $this->id;
			$this->_viewPath = '@app/views/tablet/' . $this->id;
		} else {
			$this->layout = '/desktop/' . $this->id;
			$this->_viewPath = '@app/views/desktop/' . $this->id;
		}

		/**
		 * Expose the APi to all the controller so we can reuse the API's public
		 * methods from inside of our code.
		 */
		if($this->id !== ApiController::className()) {
			$this->api = new ApiController(ApiController::className(), null, [], true);
		}

		parent::init();
	}

	public function getViewPath() {
		return Yii::getAlias($this->_viewPath);
	}

	/**
	 * Get the body of a request, transform it to a JSON
	 * and return the value of $key
	 * @param $key
	 * @return mixed
	 */
	public function getJsonFromRequest($key) {
		$request = Yii::$app->getRequest();
		$_data = Json::decode($request->getRawBody());
		return $_data[$key];
	}

	/**
	 * Save a file to the path extracted from $alias, with the name $name
	 * or a generated string in the format YYYY-MM-DD-HH-MM-xxxxx.{ext}
	 * @param $path
	 * @param null $filename
	 * @return bool|string
	 */
	public function savePostedFile($path, $filename = null) {
		$f = $_FILES['file'];

		Utils::mkdir($path);

		$ext = strtolower(pathinfo($f["name"], PATHINFO_EXTENSION));
		if($ext === null || $ext == "") {
			//Try to find out the extension from the magic byte and/or the sent type
			$mime_type = Utils::magic_guess_extension($f["tmp_name"]);
			$sent_type = $f["type"];

			//Fuck you "jpeg" vs "jpg" extension madness. FUCK YOU!
			$mime_type = $mime_type === "image/jpeg" ? "image/jpg" : $mime_type;
			$sent_type = $sent_type === "image/jpeg" ? "image/jpg" : $sent_type;

			if(strcmp($sent_type, $mime_type) === 0) {
				$s = explode("/", $sent_type);
				$ext = count($s) === 2 ? $s[1] : "unknown";
			}
		}

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
}

?>
