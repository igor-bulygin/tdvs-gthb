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
                    <a class="edit-faq-btn" href="#">+ ADD / EDIT QUESTIONS</a>
                    <div id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel faq-panel">
                        <div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-1">
                          <h4 class="panel-title">
                            <a class="faq-title" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-faq-1" aria-expanded="true" aria-controls="collapse-faq-1">
                              What is Todevise
                            </a>
                          </h4>
                        </div>
                        <div id="collapse-faq-1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-faq-1">
                          <div class="panel-body faq-answer">
                            <p>Todevise is a new concept of online store where you can browse and purchase from a diverse portfolio of curated high-quality, exclusive products. All of our pieces have been created by some of the world’s most innovative and talented artists, artisans, designers and creators.</p>
                            <p>You will find on Todevise something more than an online store - while exploring our pieces, you will fell like walking around a museum filled with beautiful contemporary creations, and you can immerse yourself in the fascinating details and stories behind them. When you purchase a product on Todevise not only that product will have a positive impact on your life thanks to its masterful combination of beauty and utility, but you also directly support some of the world’s most talented creators.</p>
                            <p>In addition to curating our large collection of exclusive and original pieces, we also paid special attention to crafting an outstanding shopping experience. The intuitiveness of our design allows you to explore the store in a comfortable manner, and our customer representatives will always be there to assist you.</p>
                          </div>
                        </div>
                      </div>
                      <div class="panel faq-panel">
                        <div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-2">
                          <h4 class="panel-title">
                            <a class="collapsed faq-title" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-faq-2" aria-expanded="false" aria-controls="collapse-faq-2">
                              What is a Deviser ?
                            </a>
                          </h4>
                        </div>
                        <div id="collapse-faq-2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-faq-2">
                          <div class="panel-body faq-answer">
                            <p>Todevise is a new concept of online store where you can browse and purchase from a diverse portfolio of curated high-quality, exclusive products. All of our pieces have been created by some of the world’s most innovative and talented artists, artisans, designers and creators.</p>
                            <p>You will find on Todevise something more than an online store - while exploring our pieces, you will fell like walking around a museum filled with beautiful contemporary creations, and you can immerse yourself in the fascinating details and stories behind them. When you purchase a product on Todevise not only that product will have a positive impact on your life thanks to its masterful combination of beauty and utility, but you also directly support some of the world’s most talented creators.</p>
                            <p>In addition to curating our large collection of exclusive and original pieces, we also paid special attention to crafting an outstanding shopping experience. The intuitiveness of our design allows you to explore the store in a comfortable manner, and our customer representatives will always be there to assist you.</p>
                          </div>
                        </div>
                      </div>
                      <div class="panel faq-panel">
                        <div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-3">
                          <h4 class="panel-title">
                            <a class="collapsed faq-title" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-faq-3" aria-expanded="false" aria-controls="collapse-faq-3">
                              What is a Todevise member ?
                            </a>
                          </h4>
                        </div>
                        <div id="collapse-faq-3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-faq-3">
                          <div class="panel-body faq-answer">
                            <p>Todevise is a new concept of online store where you can browse and purchase from a diverse portfolio of curated high-quality, exclusive products. All of our pieces have been created by some of the world’s most innovative and talented artists, artisans, designers and creators.</p>
                            <p>You will find on Todevise something more than an online store - while exploring our pieces, you will fell like walking around a museum filled with beautiful contemporary creations, and you can immerse yourself in the fascinating details and stories behind them. When you purchase a product on Todevise not only that product will have a positive impact on your life thanks to its masterful combination of beauty and utility, but you also directly support some of the world’s most talented creators.</p>
                            <p>In addition to curating our large collection of exclusive and original pieces, we also paid special attention to crafting an outstanding shopping experience. The intuitiveness of our design allows you to explore the store in a comfortable manner, and our customer representatives will always be there to assist you.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

