<?php
use app\helpers\Utils;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

use kartik\sidenav\SideNav;
use lajax\languagepicker\widgets\LanguagePicker;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="todevise" angular-multi-select-mouse-trap angular-multi-select-key-trap>
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

				echo SideNav::widget([
					'type' => SideNav::TYPE_DEFAULT,
					'heading' => 'Todevise',
					'indItem' => '',
					'indMenuOpen' => '˄',
					'indMenuClose' => '˅',
					'containerOptions' => [
						'class' => 'max-height no-vertical-margin'
					],
					'items' => [
						[
							'label' => 'Users',
							'items' =>  [
								[
									'label' => 'Devisers',
									'url' => Url::toRoute(['admin/devisers']),
									'active' => (
										Utils::compareURL('admin/devisers') ||
										Utils::compareURL('admin/deviser')
									)
								],
								[
									'label' => 'Customers',
									'url' => Url::toRoute(['admin/customers']),
									'active' => (
										Utils::compareURL('admin/customers') ||
										Utils::compareURL('admin/customer')
									)
								],
								[
									'label' => 'Collaborators',
									'url' => Url::toRoute(['admin/collaborators']),
									'active' => (
										Utils::compareURL('admin/collaborators') ||
										Utils::compareURL('admin/collaborator')
									)
								],
								[
									'label' => 'Todevise team',
									'url' => Url::toRoute(['admin/todevise-team']),
									'active' => (
										Utils::compareURL('admin/todevise-team') ||
										Utils::compareURL('admin/todevise-team-member')
									)
								],
							]
						],
						[
							'label' => 'Orders',
							'items' => [
								[
									'label' => 'Sales',
									'url' => Url::toRoute(['admin/sales']),
									'active' => (
										Utils::compareURL('admin/sales') ||
										Utils::compareURL('admin/sale')
									)
								],
								[
									'label' => 'Returns',
									'url' => Url::toRoute(['admin/returns']),
									'active' => (
										Utils::compareURL('admin/returns') ||
										Utils::compareURL('admin/return')
									)
								],
								[
									'label' => 'Warranties',
									'url' => Url::toRoute(['admin/warranties']),
									'active' => (
										Utils::compareURL('admin/warranties') ||
										Utils::compareURL('admin/warranty')
									)
								]
							],
						],
						[
							'label' => 'Settings',
							'items' => [
								 [
									'label' => 'Tags',
									'url'=> Url::toRoute(['admin/tags']),
									'active' => (
									Utils::compareURL('admin/tags')
									)
								],
								[
									'label' => 'Size charts',
									'url'=> Url::toRoute(['admin/size-charts']),
									'active' => (
										Utils::compareURL('admin/size-charts')
									)
								],
								[
									'label' => 'Categories',
									'url'=> Url::toRoute(['admin/categories']),
									'active' => (
										Utils::compareURL('admin/categories')
									)
								],
								[
									'label' => 'Banners',
									'url'=> Url::toRoute(['admin/banners']),
									'active' => (
										Utils::compareURL('admin/banners')
									)
								],
								[
									'label' => 'Shipping methods',
									'url'=> Url::toRoute(['admin/shipping-methods']),
									'active' => (
										Utils::compareURL('admin/shipping-methods')
									)
								],
								[
									'label' => 'Currencies',
									'url'=> Url::toRoute(['admin/currencies']),
									'active' => (
										Utils::compareURL('admin/currencies')
									)
								],
								[
									'label' => 'General settings',
									'url'=> Url::toRoute(['admin/general-settings']),
									'active' => (
										Utils::compareURL('admin/general-settings')
									)
								],
							],
						],
						[
							'label' => 'Content management',
							'items' => [
								[
									'label' => 'Emails',
									'url'=> Url::toRoute(['admin/emails']),
									'active' => (
										Utils::compareURL('admin/emails')
									)
								],
								[
									'label' => 'SMS',
									'url'=> Url::toRoute(['admin/sms']),
									'active' => (
										Utils::compareURL('admin/sms')
									)
								],
								[
									'label' => 'FAQ',
									'url'=> Url::toRoute(['admin/faq']),
									'active' => (
										Utils::compareURL('admin/faq')
									)
								],
								[
									'label' => 'Newsletter',
									'url'=> Url::toRoute(['admin/newsletter']),
									'active' => (
										Utils::compareURL('admin/newsletter')
									)
								],
								[
									'label' => 'Homepage categories',
									'url'=> Url::toRoute(['admin/homepage-categories']),
									'active' => (
										Utils::compareURL('admin/homepage-categories')
									)
								],
								[
									'label' => 'About',
									'url'=> Url::toRoute(['admin/about']),
									'active' => (
										Utils::compareURL('admin/about')
									)
								],
								[
									'label' => 'Contact',
									'url'=> Url::toRoute(['admin/contact']),
									'active' => (
										Utils::compareURL('admin/contact')
									)
								],
								[
									'label' => 'Terms & conditions',
									'url'=> Url::toRoute(['admin/toc']),
									'active' => (
										Utils::compareURL('admin/toc')
									)
								],
							],
						]
					],
				]);

				?>

			</div>

			<div class="col-lg-10">
				<?php

					NavBar::begin([
						'options' => [
							'class' => 'navbar-inverse no-vertical-margin',
						],
						'containerOptions' => [
							'class' => 'no-horizontal-padding navbar-content' //bug
						],
						'innerContainerOptions' => [
							'class' => 'container-fluid no-horizontal-padding'
						]
					]);

					echo Breadcrumbs::widget([
						'homeLink' => [
							'label' => 'Admin home',
							'url' => ["/admin"]
						],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
						'options' => [
							'class' => 'breadcrumb no-vertical-margin'
						]
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
									'linkOptions' => ['data-method' => 'post']
								]
						]
					]);

					NavBar::end();
				?>

				<div class="container no-horizontal-padding">
					<div class="site-index">
						<div class="body-content">
							<?= $content ?>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
