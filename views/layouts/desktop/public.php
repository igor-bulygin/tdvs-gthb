<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use lajax\languagepicker\widgets\LanguagePicker;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
	<?php

	NavBar::begin([
		'brandLabel' => 'Todevise',
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);

	echo LanguagePicker::widget([
		'skin' => LanguagePicker::SKIN_DROPDOWN,
		'size' => LanguagePicker::SIZE_LARGE,
		//'parentTemplate' => '<div class="language-picker dropdown-list {size}"><div>{activeItem}<ul>{items}</ul></div></div>',
		'itemTemplate' => '<li><a href="{link}" title="{name}">{name}</a></li>',
		'activeItemTemplate' => '<a href="" title="{name}">{name}</a>',
		'languageAsset' => 'lajax\languagepicker\bundles\LanguageLargeIconsAsset',
		'languagePluginAsset' => 'lajax\languagepicker\bundles\LanguagePluginAsset',
	]);

	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => [
			['label' => 'Home', 'url' => ['/site/index']],
			['label' => 'About', 'url' => ['/site/about']],
			['label' => 'Contact', 'url' => ['/site/contact']],
			Yii::$app->user->isGuest ?
				['label' => 'Login', 'url' => ['/site/login']] :
				['label' => 'Logout (' . Yii::$app->user->identity->personal_info["name"] . ')',
					'url' => ['/site/logout'],
					'linkOptions' => ['data-method' => 'post']],
		],
	]);
	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; My Company <?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</footer>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
