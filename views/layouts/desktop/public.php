<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\components\CategoriesList;
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

			<div class="container-fluid no-horizontal-padding max-height">
				<div class="row no-gutter max-height">

					<!-- NAVBAR LEFT -->
					<div class="col-xs-1-5 flex">
						<div class="navbar-left funiv_ultra fs1 flex-prop-1">
							<ul class="list-group">
								<li class="logo fpf_bold">todevise<br/><span class="fpf_i">A new concept of store</span></li>
							</ul>
							<?php echo CategoriesList::widget(); ?>
						</div>
					</div>

					<!-- CONTENT TOP MENU / BODY / FOOTER -->
					<div class="col-xs-10-5">

						<div class="wrapper flex flex-column">
							<div class="header">

								<?php

									NavBar::begin([
										'brandUrl' => Yii::$app->homeUrl,
										'options' => [
											'class' => 'navbar-inverse',
										],
										'innerContainerOptions' => [
											'class' => 'container-fluid container-menutop'
										]
									]);

									echo Nav::widget([
										'options' => ['class' => 'navbar-nav flex menutop funiv_ultra fs1'],
										'items' => [
											[
												'label' => Yii::t("app/public", 'Find the perfect gift'),
												'url' => ['/site/index'],
												"options" => [
													"class" => "item-menutop fs-upper flex-prop-1-0"
												]
											],
											[
												'label' => Yii::t("app/public", 'Discover trends'),
												'url' => ['/site/about'],
												"options" => [
													"class" => "item-menutop fs-upper flex-prop-1-0"
												]
											],
											[
												'label' => Yii::t("app/public", 'Discover trends'),
												'url' => ['/site/contact'],
												"options" => [
													"class" => "item-menutop fs-upper flex-prop-1-0"
												]
											],
											[
												'label' => Yii::t("app/public", 'Caja de búsqueda'),
												"options" => [
													"class" => "item-menutop fs-upper flex-prop-1-0 flex-grow-3"
												]
											],
											[
												'label' => 'IMG',
												"options" => [
													"class" => "item-menutop flex-prop-1-0"
												]
											],
											[
												'label' => Yii::t("app/public", 'TODEVISE'),
												"options" => [
													"class" => "item-menutop fs-upper flex-prop-1-0 fpf_bold fs0-786"
												]
											],
											Yii::$app->user->isGuest ?
												[
													'label' => Yii::t("app/public", 'Login'),
													'url' => ['/site/login'],
													"options" => [
														"class" => "item-menutop flex-prop-1-0"
													]
												] :
												[
													'label' => Yii::t("app/public", 'Logout ({user})', [
															"user" => Yii::$app->user->identity->personal_info["name"]
														]),
													'url' => ['/site/logout'],
													'linkOptions' => ['data-method' => 'post']
												],
										],
									]);
									NavBar::end();
								?>

							</div>
							<div class="body-content flex flex-column flex-prop-1 overflow">
								<div class="main flex-prop-1-0">
									<?= $content ?>
								</div>

								<div class="footer">footer</div>
							</div>

						</div>

					</div>

				</div>
			</div>

		<?php $this->endBody() ?>

	</body>
</html>
<?php $this->endPage() ?>
