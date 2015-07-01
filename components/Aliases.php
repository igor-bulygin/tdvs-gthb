<?php
namespace app\components;

use Yii;
use yii\base\Component;

class Aliases extends Component {
	public function init() {
		Yii::setAlias('uploads', '@webroot/uploads');
		Yii::setAlias('deviser', '@uploads/deviser');
		Yii::setAlias('product', '@uploads/product');
	}
}