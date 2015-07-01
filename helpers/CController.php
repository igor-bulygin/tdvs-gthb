<?php
namespace app\helpers;

use Yii;
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
