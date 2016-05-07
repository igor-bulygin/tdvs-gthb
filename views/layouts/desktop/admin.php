<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use app\helpers\Utils;
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
<html lang="<?= Yii::$app->language ?>" ng-app="todevise">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
		<?php $this->registerJs("var _lang = " . Json::encode(Yii::$app->language) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _lang_en = " . Json::encode(array_keys(Lang::EN_US)[0]) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs = " . Json::encode(Utils::availableLangs()) . ";", View::POS_HEAD) ?>
	</head>
	<body>
	<?php $this->beginBody() ?>
		<div class="container-fluid no-horizontal-padding max-height">
			<div class="row no-gutter max-height">

				<!-- NAVBAR LEFT -->
				<div class="col-xs-1-5 flex">
					<div class="navbar-left funiv_ultra fs1 flex-prop-1">
						<?php

							echo SideNav::widget([
								'type' => SideNav::TYPE_DEFAULT,
								'heading' => '<font class="fpf_bold">Todevise</font><br><span class="funiv fs0-414">ADMINISTRATION</span>',
								'headingOptions' => [
									'class' => "text-center"
								],
								'indItem' => '',
								'indMenuOpen' => '˄',
								'indMenuClose' => '˅',
								'containerOptions' => [
									'class' => 'max-height no-vertical-margin overflow'
								],
								'items' => [
									[
										'label' => 'Users',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' =>  [
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Devisers',
												'url' => Url::toRoute(['admin/devisers']),
												'active' => (
													Utils::compareURL('admin/devisers') ||
													Utils::compareURL('admin/deviser')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Customers',
												'url' => Url::toRoute(['admin/customers']),
												'active' => (
													Utils::compareURL('admin/customers') ||
													Utils::compareURL('admin/customer')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Collaborators',
												'url' => Url::toRoute(['admin/collaborators']),
												'active' => (
													Utils::compareURL('admin/collaborators') ||
													Utils::compareURL('admin/collaborator')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
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
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => [
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Sales',
												'url' => Url::toRoute(['admin/sales']),
												'active' => (
													Utils::compareURL('admin/sales') ||
													Utils::compareURL('admin/sale')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Returns',
												'url' => Url::toRoute(['admin/returns']),
												'active' => (
													Utils::compareURL('admin/returns') ||
													Utils::compareURL('admin/return')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
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
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => [
											 [
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Tags',
												'url'=> Url::toRoute(['admin/tags']),
												'active' => (
													Utils::compareURL('admin/tags') ||
													Utils::compareURL('admin/tag')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Size charts',
												'url'=> Url::toRoute(['admin/size-charts']),
												'active' => (
													Utils::compareURL('admin/size-charts') ||
													Utils::compareURL('admin/size-chart')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Categories',
												'url'=> Url::toRoute(['admin/categories']),
												'active' => (
													Utils::compareURL('admin/categories')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Banners',
												'url'=> Url::toRoute(['admin/banners']),
												'active' => (
													Utils::compareURL('admin/banners')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Shipping methods',
												'url'=> Url::toRoute(['admin/shipping-methods']),
												'active' => (
													Utils::compareURL('admin/shipping-methods')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Currencies',
												'url'=> Url::toRoute(['admin/currencies']),
												'active' => (
													Utils::compareURL('admin/currencies')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
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
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => [
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Emails',
												'url'=> Url::toRoute(['admin/emails']),
												'active' => (
													Utils::compareURL('admin/emails')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'SMS',
												'url'=> Url::toRoute(['admin/sms']),
												'active' => (
													Utils::compareURL('admin/sms')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'FAQ',
												'url'=> Url::toRoute(['admin/faq']),
												'active' => (
													Utils::compareURL('admin/faq')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Newsletter',
												'url'=> Url::toRoute(['admin/newsletter']),
												'active' => (
													Utils::compareURL('admin/newsletter')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Homepage categories',
												'url'=> Url::toRoute(['admin/homepage-categories']),
												'active' => (
													Utils::compareURL('admin/homepage-categories')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'About',
												'url'=> Url::toRoute(['admin/about']),
												'active' => (
													Utils::compareURL('admin/about')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Contact',
												'url'=> Url::toRoute(['admin/contact']),
												'active' => (
													Utils::compareURL('admin/contact')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
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
				</div>

				<!-- CONTENT TOP MENU / BODY -->
				<div class="col-xs-10-5">

					<div class="wrapper flex flex-column">
						<div class="header flex-prop-0-0">
							<?php
								NavBar::begin([
									'options' => [
										'class' => 'navbar-inverse',
									],

									'containerOptions' => [
										'class' => "no-horizontal-padding main-navbar flex"
									],

									'innerContainerOptions' => [
										'class' => 'container-fluid container-menutop'
									]
								]);

								echo Breadcrumbs::widget([
									'homeLink' => [
										'label' => 'admin home',
										'url' => ["/admin"]
									],
									'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
									'options' => ['class' => 'breadcrumb funiv fs-upper fs0-857 pull-left no-vertical-margin no-padding'],
								]);

								echo Nav::widget([
									'options' => ['class' => 'navbar-nav pull-right navopts funiv fs-upper fs0-857'],
									'items' => [
										Yii::$app->user->isGuest ?
											['label' => 'Login', 'url' => ['/site/login']] :
											['label' => 'Logout (' . Yii::$app->user->identity->personal_info["name"] . ')',
												'url' => ['/global/logout'],
												'linkOptions' => ['data-method' => 'post']
											]
									]
								]);

								echo LanguagePicker::widget([
									//'options' => 'pull-right',
									'skin' => LanguagePicker::SKIN_DROPDOWN,
									'size' => LanguagePicker::SIZE_LARGE,
									'parentTemplate' => '<div class="language-picker dropdown-list {size} funiv fs-upper fs0-929 pull-right"><div>{activeItem}<ul>{items}</ul></div></div>',
									'itemTemplate' => '<li class="funiv fs-upper fs0-929"><a href="{link}" title="{name}">{name}</a></li>',
									'activeItemTemplate' => '<a href="" title="{name}" class="funiv fs-upper fs0-929">{name}</a>',
									'languageAsset' => 'lajax\languagepicker\bundles\LanguageLargeIconsAsset',
									'languagePluginAsset' => 'lajax\languagepicker\bundles\LanguagePluginAsset',
								]);

							NavBar::end();
							?>
						</div>

						<div class="body-content flex flex-column flex-prop-1 overflow">
							<div class="main flex-prop-1-0">
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
