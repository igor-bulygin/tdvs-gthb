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
$this->params['deviser_menu_active_option'] = 'faq';

/** array $faq */

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
					<a class="edit-faq-btn" href="<?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">+ ADD / EDIT QUESTIONS</a>
					<?php if (count($faq) == 0) { ?>
						<div>You don't have any question!</div>
					<?php } else { ?>

					<div id="accordion" role="tablist" aria-multiselectable="true">
						<?php foreach ($faq as $item) { ?>
						<div class="panel faq-panel">
							<div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-1">
								<h4 class="panel-title">
									<a class="faq-title" role="button" data-toggle="collapse" data-parent="#accordion"
									   href="#collapse-faq-1" aria-expanded="true" aria-controls="collapse-faq-1">
										<?= $item["question"] ?>
									</a>
								</h4>
							</div>
							<div id="collapse-faq-1" class="panel-collapse collapse in" role="tabpanel"
							     aria-labelledby="heading-faq-1">
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

