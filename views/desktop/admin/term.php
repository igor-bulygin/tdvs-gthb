<?php
use app\assets\desktop\admin\TermAsset;
use yii\web\View;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Term',
	'url' => ['/admin/terms']
];

TermAsset::register($this);

$this->title = 'Todevise / Admin / Term';
?>

<div class="row no-gutter">



	<div class="main flex-prop-1-0" ng-controller="termCtrl as termCtrl">
		<?php $this->registerJs("var _term_id = '" . $term_id . "';", View::POS_END); ?>
		<?php $this->registerJs("var _term_subid = '" . $term_subid . "';", View::POS_END); ?>

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Edit Term"); ?></h2>
				</div>
				<div class="col-xs-2 col-height col-middle text-center">
					<button class="btn btn-grey fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="termCtrl.close()"><?= Yii::t("app/admin", "Cancel changes"); ?></button>
					<button class="btn btn-light-green fc-333 funiv fs0-786 fs-upper margin-l0-r1" ng-click="termCtrl.save()"><?= Yii::t("app/admin", "Save changes"); ?></button>

				</div>
			</div>
		</div>
		<div class="flex flex-column">
			<uib-accordion close-others="termCtrl.oneAtATime">

				<div ng-repeat="(lang_k, lang_v) in termCtrl.langs">
					<uib-accordion-group heading="{{ lang_v }} {{ lang_k }}">

						<div class="flex flex-column">

							<div class="flex flex-row field">
								<div class="fs-upper fc-c7 title-label"><?= Yii::t("app/admin", "title"); ?></div>
								<div class="width-100">
									<input required="" type="text" class="form-control fc-fff funiv fs1 ng-pristine ng-valid ng-not-empty ng-touched" placeholder="" aria-describedby="basic-addon-{{ $index }}" ng-model="termCtrl.subterm.question[lang_k]" name="{{ lang_k }}">
								</div>
							</div>

							<div class="flex flex-row field">

								<div class="fs-upper fc-c7 title-label">content</div>
								<div class="width-100">
									<textarea class="form-control fc-fff funiv fs1 ng-pristine ng-valid ng-not-empty ng-touched"
									ng-model="termCtrl.subterm.answer[lang_k]"
									[name="string"]
									[required="string"]
									[ng-required="string"]
									[ng-minlength="number"]
									[ng-maxlength="number"]
									[ng-pattern="string"]
									[ng-change="string"]
									[ng-trim="boolean"]>
									{{ termCtrl.subterm.answer[lang_k] }}
								</textarea>
							</div>

						</div>
					</div>

				</uib-accordion-group>
			</div>

		</uib-accordion>
	</div>
</div>
