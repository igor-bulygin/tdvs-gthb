<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;
use app\components\PublicFooter;
use app\components\LeftMenu;
use app\components\PublicHeader;
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
							<ul class="list-group">
								<li class="logo fpf_bold">todevise<br/><span class="fpf_i">A new concept of store</span></li>
							</ul>
							<?= LeftMenu::widget(); ?>
						</div>
					</div>

					<!-- CONTENT TOP MENU / BODY / FOOTER -->
					<div class="col-xs-10-5">

						<div class="wrapper flex flex-column">
							<div class="body-content flex flex-column flex-prop-1 overflow">
								<div class="header flex-prop-0-0">
									<?= PublicHeader::widget(); ?>
								</div>

								<div class="main flex-prop-1-0">
									<?= $content ?>
								</div>

								<div class="footer flex-prop-0-0">
									<?= PublicFooter::widget(); ?>
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
