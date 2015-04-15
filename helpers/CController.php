<?php
namespace app\helpers;

use Yii;
use yii\web\Controller;

class CController extends Controller
{
	private $_viewPath = "";

	public function init()
	{
		/**
		 * Force Trailing Slashes to Avoid Duplicate Content
		 */
		parent::init();
		$requestUri = Yii::$app->request->getUrl();
		$repairedRequestUri = $requestUri;

		while (mb_strpos($repairedRequestUri, '//') !== false) {
			$repairedRequestUri = preg_replace("////", '/', $repairedRequestUri);
		}

		if (mb_strpos($repairedRequestUri, '?') === false && mb_substr($repairedRequestUri, -1) !== '/') {
			$repairedRequestUri = "{$repairedRequestUri}/";
		} elseif (mb_substr($repairedRequestUri, mb_strpos($repairedRequestUri, '?') - 1, 1) !== '/') {
			$repairedRequestUri = mb_substr($repairedRequestUri, mb_strpos($repairedRequestUri, "?"), 0);
		}

		if ($repairedRequestUri !== $requestUri) {
			$this->redirect($repairedRequestUri, 301);
		}


		/**
		 * This little piece of code is really important. It will decide how the
		 * page should look like, based on the device type and the accessed
		 * controller.
		 *
		 * When the type of  device is detected (mobile, tablet or desktop), the
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
	}

	public function getViewPath()
	{
		return Yii::getAlias($this->_viewPath);
	}
}

?>
