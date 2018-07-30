<?php

use app\components\PublicFooter2;
use app\components\PublicHeader2;
use app\helpers\Utils;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */
$show_header = isset($this->params['show_header']) ? $this->params['show_header']: true; 
$show_footer = isset($this->params['show_footer']) ? $this->params['show_footer']: true;

if (empty(Yii::$app->opengraph->description)) {
	Yii::$app->opengraph->description = Yii::t('app/public', 'TODEVISE_META_DESCRIPTION');
}

?>

<?php $this->beginPage() ?>
	<!doctype html>
	<html lang="<?= Yii::$app->language ?>">

	<head>
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title.' | Todevise') ?></title>
		<!-- METAS -->
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?=Yii::$app->opengraph->description?>"/>


		<?php $this->head() ?>
		<?php $this->registerJs("var _lang = " . Json::encode(Yii::$app->language) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _lang_en = " . Json::encode(array_keys(Lang::EN_US_DESC)[0]) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs = " . Json::encode(Utils::availableLangs()) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs_required = " . Json::encode(Lang::getRequiredLanguages()) . ";", View::POS_HEAD) ?>
		<!-- CSS -->
		<!--    <link href="/css/desktop/public-2/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>-->
		<!--    <link href="/css/desktop/public-2/application.css" rel="stylesheet" type="text/css" media="all"/>-->
		<!-- FONTS -->
		<link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700italic,400italic,700,900,900italic'
		      rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed' rel='stylesheet' type='text/css'>
		<!-- ICONS -->
		<link rel="icon" href="/imgs/favicon.png" type="image/x-icon" />
		<!-- IONIC ICONS -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- FONTAWESOME ICONS -->
		<script src="https://use.fontawesome.com/9a31e47575.js"></script>

		<?php if (YII_ENV_PROD) { ?>
			<!-- Hotjar Tracking Code for www.todevise.com -->
			<script>
				(function(h,o,t,j,a,r){
					h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
					h._hjSettings={hjid:775424,hjsv:6};
					a=o.getElementsByTagName('head')[0];
					r=o.createElement('script');r.async=1;
					r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
					a.appendChild(r);
				})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
			</script>
		<?php } ?>
	</head>

	<body ng-app="todevise">
	<?php $this->beginBody() ?>

	<?php if ($show_header) {
		echo PublicHeader2::widget();
	} ?>

	<?= $content ?>

	<?php if ($show_footer) {
		echo PublicFooter2::widget();
	} ?>

	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
	<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<?php $this->endBody() ?>
	</body>

	<?php if (YII_ENV_PROD) { ?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-38667411-1', 'auto');
			ga('send', 'pageview');
		</script>

		<!-- Facebook Pixel Code -->
		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '2063838850564274');
		  fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		  src="https://www.facebook.com/tr?id=2063838850564274&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
	<?php } ?>

	</html>
<?php $this->endPage() ?>
