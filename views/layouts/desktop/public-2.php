<?php
use app\components\PublicFooter2;
use app\components\PublicHeader2;
use yii\web\View;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<!-- METAS -->
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php $this->head() ?>
	<!-- CSS -->
	<!--    <link href="/css/desktop/public-2/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>-->
	<!--    <link href="/css/desktop/public-2/application.css" rel="stylesheet" type="text/css" media="all"/>-->
	<!-- FONTS -->
	<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700italic,400italic,700,900,900italic'
	      rel='stylesheet' type='text/css'>
	<!-- ICONS -->
	<!-- IONIC ICONS -->
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- FONTAWESOME ICONS -->
	<script src="https://use.fontawesome.com/9a31e47575.js"></script>
</head>

<body>
<?php $this->beginBody() ?>

<?= PublicHeader2::widget(); ?>

<?= $content ?>

<?= PublicFooter2::widget(); ?>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="/js/desktop/public-2/bootstrap.min.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
