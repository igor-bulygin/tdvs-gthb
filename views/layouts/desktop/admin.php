<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use app\assets\AppAsset;

use kartik\sidenav\SideNav;
use lajax\languagepicker\widgets\LanguagePicker;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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

	<div class="container-fluid no-horizontal-padding max-height">

		<div class="row no-gutter max-height">

			<div class="col-lg-2 max-height">

				<?php

				$item = "";
				echo SideNav::widget([
					'type' => SideNav::TYPE_DEFAULT,
					'heading' => 'Todevise',
					'indItem' => '',
					'indMenuOpen' => '˄',
					'indMenuClose' => '˅',
					'containerOptions' => ['class' => 'max-height no-vertical-margin'],
					'items' => [
						[
							'url' => '#',
							'label' => 'Users',
							'items' =>  [
								['label' => 'Devisers', 'url'=>'#', 'active' => ($item == 'most-popular')],
								['label' => 'Customers', 'url'=>'#', 'active' => ($item == 'most-popular')],
								['label' => 'Collaborators', 'url'=>'#', 'active' => ($item == 'most-popular')],
								['label' => 'Todevise team', 'url'=>'#', 'active' => ($item == 'most-popular')],
							]
						],
						[
							'label' => 'Orders',
							'items' => [
								 ['label' => 'About', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Contact', 'url'=>'#', 'active' => ($item == 'most-popular')],
							],
						],
						[
							'label' => 'Settings',
							'items' => [
								 ['label' => 'Tags', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Size charts', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Banners', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Shipping methods', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Currencies', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'General settings', 'url'=>'#', 'active' => ($item == 'most-popular')],
							],
						],
						[
							'label' => 'Content management',
							'items' => [
								 ['label' => 'Emails', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'SMS', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'FAQ', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Newsletter', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'Homepage categories', 'url'=>'#', 'active' => ($item == 'most-popular')],
								 ['label' => 'About us', 'url'=>'#', 'active' => ($item == 'most-popular')],
							],
						]
					],
				]);

				?>

			</div>

			<div class="col-lg-10">
				<?php

					NavBar::begin([
						'brandLabel' => 'Todevise',
						'brandUrl' => Yii::$app->homeUrl,
						'options' => [
							'class' => 'navbar-inverse ',
						],
					]);

					echo LanguagePicker::widget([
						'skin' => LanguagePicker::SKIN_DROPDOWN,
						'size' => LanguagePicker::SIZE_LARGE,
						'parentTemplate' => '<div class="language-picker dropdown-list {size}"><div>{activeItem}<ul>{items}</ul></div></div>',
						'itemTemplate' => '<li><a href="{link}" title="{name}">{name}</a></li>',
						'activeItemTemplate' => '<a href="{link}" title="{name}">{name}</a>',
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
								['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
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

		</div>

	</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
