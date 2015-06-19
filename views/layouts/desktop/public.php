<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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

				<div class="col-lg-1-5 max-height">
					LEFT COLUMNN
				</div>

				<div class="col-lg-10-5 main-content">

					<?php

					NavBar::begin([
						'brandLabel' => 'Todevise',
						'brandUrl' => Yii::$app->homeUrl,
						'options' => [
							'class' => 'navbar-inverse navbar-fixed-top',
						],
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

					<div class="container-fluid">
						<div class="site-index">
							<div class="body-content">
								<?= $content ?>
							</div>
						</div>
					</div>

					<div class="footer">
						<div class="container">
							<p class="pull-left">&copy; Todevise <?= date('Y') ?></p>
							<p class="pull-right"><?= Yii::powered() ?></p>
						</div>
					</div>

				</div>
			</div>
		</div>

	<?php $this->endBody() ?>

	</body>
</html>
<?php $this->endPage() ?>
