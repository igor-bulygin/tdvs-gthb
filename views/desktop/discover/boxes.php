<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','EXPLORE_BOXES');
Yii::$app->opengraph->title = $this->title;

?>
<explore-boxes></explore-boxes>
