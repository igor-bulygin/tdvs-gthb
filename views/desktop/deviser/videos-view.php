<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'videos';

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (count($videos) == 0) { ?>
					<div>You don't have any video!</div>
				<?php } else { ?>
			    <div class="video-container">
				    <?php foreach ($videos as $video) { ?>
                    <div class="col-sm-<?= (count($videos)<=3) ? '12' : '6' ?>">
                        <div class="video-wrapper">
                            <iframe width="560" height="315" src="<?= Utils::getUrlEmbeddedYoutubePlayer($video["url"]) ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
				    <?php }  ?>
                </div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>