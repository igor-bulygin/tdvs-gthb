<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use yii\web\View;
use app\models\Lang;
use app\assets\desktop\pub\Index2Asset;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditPressAsset;

EditPressAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'press';

?>

<?= DeviserHeader::widget() ?>
<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>

<div class="store" ng-controller="editPressCtrl as editPressCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
			    <div ng-if="editPressCtrl.images === 0">You don't have any press images!</div>
                        
				<div class="mesonry-row press-3">
                       <form name="editPressCtrl.form">
                            <div ng-if="editPressCtrl.isDropAvailable">
                                <div class="photo-loader" class="menu-category list-group" ng-model="editPressCtrl.image" ngf-drop ngf-select ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable">
                                    <div class="plus-add">+</div>
                                    <span>Add press image</span>
                                </div>
                            </div>
                            <div ngf-no-file-drop>
                                <input type="file" name="file" ng-model="editPressCtrl.image" ngf-select ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable">
                                <button ng-click="editPressCtrl.upload(editPressCtrl.image)">Guardar</button>
                            </div>
                        </form>
                       <div class="menu-category list-group draggable-list" ng-if="editPressCtrl.images.length > 0" dnd-list="editPressCtrl.images" ng-repeat="item in editPressCtrl.images">
                           <div class="image-press-wrapper">
                                <span class="ion-android-close x-close" ng-click="editPressCtrl.deleteImage($index)"></span>
                                <img class="grid-image draggable-img" ng-src="{{item.url}}" dnd-draggable="item" dnd-effect-allowed="move" dnd-moved="editPressCtrl.update($index)">
                            </div>
                        </div>
                </div>
			</div>
		</div>
	</div>
</div>

