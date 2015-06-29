<?php
use yii\helpers\Html;
use app\components\CategoriesNavbar;
use app\components\PublicHeaderNavbar;
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
							<?= CategoriesNavbar::widget(); ?>
						</div>
					</div>

					<!-- CONTENT TOP MENU / BODY / FOOTER -->
					<div class="col-xs-10-5">

						<div class="wrapper flex flex-column">
							<div class="header">
								<?= PublicHeaderNavbar::widget(); ?>
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
