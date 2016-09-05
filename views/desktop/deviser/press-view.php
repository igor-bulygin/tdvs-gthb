<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\web\View;
use app\models\Lang;
use app\assets\desktop\pub\Index2Asset;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditPressAsset;

EditPressAsset::register($this);

/** @var Person $deviser */
/** @var array $press */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'press';

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (count($press) == 0) { ?>
				<div>You don't have any press images!</div>
				<?php } else { ?>
				<div class="mesonry-row press-3">
					<?php foreach ($press as $item) { ?>
					<div class="menu-category list-group draggable-list">
						<img class="grid-image draggable-img" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getUrlImagesLocation() . $item)->resize(355, 0) ?>">
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>