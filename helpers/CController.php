<?php
namespace yii\helpers;

use Yii;
use yii\web\Controller;

class CController extends Controller
{
	private $_viewPath = "";

	public function init()
	{
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
