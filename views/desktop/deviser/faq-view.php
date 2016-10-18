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

$this->title = 'About ' . $deviser->personalInfo->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'faq';
$this->params['deviser_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?****>">+ ADD / EDIT QUESTIONS</a>


?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="faq-wrapper">
					<?php if (count($faq) == 0) { ?>
				    <div class="empty-wrapper">
                        <img class="sad-face" src="/imgs/sad-face.svg">
                        <p class="no-video-text">You don't have any question!</p>
                    </div>
					<?php } else { ?>
						<div class="red-link-btn"><a href="<?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Add question</a></div>
					<div id="accordion" role="tablist" aria-multiselectable="true">
						<?php foreach ($faq as $key => $item) { ?>
						<div class="panel faq-panel">
							<div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-<?= $key ?>">
								<h4 class="panel-title">
									<a class="faq-title <?= ($key!=0) ? 'collapsed' : '' ?>" role="button" data-toggle="collapse" data-parent="#accordion"
									   href="#collapse-faq-<?= $key ?>" aria-expanded="<?= ($key==0) ? 'true' : 'false' ?>" aria-controls="collapse-faq-<?= $key ?>">
										<?= $item["question"] ?>
									</a>
								</h4>
							</div>
							<div id="collapse-faq-<?= $key ?>" class="panel-collapse collapse <?= ($key==0) ? 'in' : '' ?>" role="tabpanel"
							     aria-labelledby="heading-faq-<?= $key ?>">
								<div class="panel-body faq-answer">
									<?= $item["answer"] ?>
								</div>
							</div>
						</div>
						<?php }  ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

