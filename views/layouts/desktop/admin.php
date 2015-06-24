<?php
use app\helpers\Utils;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\web\View;
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
		<?php $this->registerJs("var _lang = " . Json::encode(Yii::$app->language) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs = " . Json::encode(Utils::availableLangs()) . ";", View::POS_HEAD) ?>
	</head>
	<body>
	<?php $this->beginBody() ?>
		<div class="container-fluid no-horizontal-padding max-height">
			<div class="row no-gutter max-height">

				<!-- NAVBAR LEFT -->
				<div class="col-xs-1-5 flex">
					<div class="navbar-left funiv_ultra fs1  flex-prop-1">
						<?php

							echo SideNav::widget([
								'type' => SideNav::TYPE_DEFAULT,
								'heading' => '<font class="fpf_bold">Todevise</font><br><span class="funiv fs0-414">ADMINISTRATION</span>',
								'indItem' => '',
								'indMenuOpen' => '˄',
								'indMenuClose' => '˅',
								'containerOptions' => [
									'class' => 'max-height no-vertical-margin'
								],
								'items' => [
									[
										'label' => 'Users',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857',
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
											'class' => 'item-menu-left funiv_bold fs0-857',
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
											'class' => 'item-menu-left funiv_bold fs0-857',
										],
										'items' => [
											 [
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Tags',
												'url'=> Url::toRoute(['admin/tags']),
												'active' => (
												Utils::compareURL('admin/tags')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Size charts',
												'url'=> Url::toRoute(['admin/size-charts']),
												'active' => (
													Utils::compareURL('admin/size-charts')
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
											'class' => 'item-menu-left funiv_bold fs0-857',
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
						<div class="header">
							<?php

								NavBar::begin([
									'options' => [
										'class' => 'navbar-inverse',
									],
									'containerOptions' => [
										'class' => ['widget' => 'no-horizontal-padding navbar-content']
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
						</div>

						<div class="content flex flex-column flex-prop-1">
							<div class="main">
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
