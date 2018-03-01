<?php

use app\helpers\Utils;
use app\models\Lang;
use kartik\sidenav\SideNav;
use lajax\languagepicker\widgets\LanguagePicker;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

// Create invoices link, one per month
$date = new DateTime();
$limit = DateTime::createFromFormat('Y-m-d H:i:s', '2017-09-01 00:00:00');
$oneMonth = DateInterval::createFromDateString('1 month');

$itemsInvoices = [];
while ($date > $limit) {
	$aux = clone $date;
	$next = clone $date;
	$next = $next->add($oneMonth);
	$aux = $aux->sub($oneMonth);
	$itemsInvoices[] = [
		'options' => [
			'class' => 'item-submenu funiv fs0-929',
		],
		'label' => $date->format('M Y'),
		'url' => Url::toRoute(['admin/invoices-excel']) . '/' . $date->format('Y-m-d') . '/' . $next->format('Y-m-d'),
	];
	$date = $aux;
}


// Create links for Packages excel, one per day
$date = new DateTime();
$limit = DateTime::createFromFormat('Y-m-d H:i:s', '2017-09-01 00:00:00');
$oneDay = DateInterval::createFromDateString('1 day');

$itemsPackages= [];
while ($date > $limit) {
	$aux = clone $date;
	$next = clone $date;
	$next = $next->add($oneDay);
	$aux = $aux->sub($oneDay);
	$itemsPackages[] = [
		'options' => [
			'class' => 'item-submenu funiv fs0-929',
		],
		'label' => $date->format('d M Y'),
		'url' => Url::toRoute(['admin/packages-excel']) . '/' . $date->format('Y-m-d') . '/' . $next->format('Y-m-d'),
	];
	$date = $aux;
}

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
		<?php $this->registerJs("var _lang_en = " . Json::encode(array_keys(Lang::EN_US_DESC)[0]) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs = " . Json::encode(Lang::getEnabledLanguages()) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _langs_required = " . Json::encode(Lang::getRequiredLanguages()) . ";", View::POS_HEAD) ?>
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
												'label' => 'Influencers',
												'url' => Url::toRoute(['admin/influencers']),
												'active' => (
													Utils::compareURL('admin/influencers') ||
													Utils::compareURL('admin/influencer')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Clients',
												'url' => Url::toRoute(['admin/clients']),
												'active' => (
													Utils::compareURL('admin/clients') ||
													Utils::compareURL('admin/client')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Todevise team',
												'url' => Url::toRoute(['admin/admins']),
												'active' => (
													Utils::compareURL('admin/admins') ||
													Utils::compareURL('admin/admins-member')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Invitations',
												'url' => Url::toRoute(['admin/invitations']),
												'active' => (
													Utils::compareURL('admin/invitations') ||
													Utils::compareURL('admin/invitation')
												)
											],
										]
									],
									/*
									[
										'label' => 'ORDERS',
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
									*/
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
												'url'=> Url::toRoute(['admin/faqs']),
												'active' => (
													Utils::compareURL('admin/faqs')
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
												'url'=> Url::toRoute(['admin/terms']),
												'active' => (
													Utils::compareURL('admin/terms')
												)
											],
										],
									],
									[
										'label' => 'Stats	',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => [
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Sales',
												'url'=> Url::toRoute(['admin/sales-history']),
												'active' => (
												Utils::compareURL('admin/sales-history')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Basic stats',
												'url'=> Url::toRoute(['admin/basic-stats']),
												'active' => (
												Utils::compareURL('admin/basic-stats')
												)
											],
										],
									],
									[
										'label' => 'Postman',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => [
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Emails',
												'url'=> Url::toRoute(['admin/postman-emails']),
												'active' => (
												Utils::compareURL('admin/postman-emails')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Mandrill (sent)',
												'url'=> Url::toRoute(['admin/mandrill-sent']),
												'active' => (
												Utils::compareURL('admin/mandrill-sent')
												)
											],
											[
												'options' => [
													'class' => 'item-submenu funiv fs0-929',
												],
												'label' => 'Mandrill (scheduled)',
												'url'=> Url::toRoute(['admin/mandrill-scheduled']),
												'active' => (
												Utils::compareURL('admin/mandrill-scheduled')
												)
											],
										],
									],
									[
										'label' => 'Invoices',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => $itemsInvoices,
									],
										[
										'label' => 'Packages',
										'options' => [
											'class' => 'item-menu-left funiv_bold fs0-857 fs-upper',
										],
										'items' => $itemsPackages,
									],
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
											['label' => 'Login', 'url' => ['/public/login']] :
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
