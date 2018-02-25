<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','DISCOVER_DEVISERS');
Yii::$app->opengraph->title = $this->title;
$this->registerJs("var type = 2", yii\web\View::POS_HEAD, 'person-type-script');

?>
<explore-person personType="type"></explore-person>