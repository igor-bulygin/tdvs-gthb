<?php

$this->title = 'Discover devisers - Todevise';

\app\assets\desktop\discover\DiscoverAsset::register($this);

$this->registerJs("var type = 2", yii\web\View::POS_HEAD, 'person-type-script');

?>
<div class="container" ng-controller="discoverCtrl as discoverCtrl">
	<h3>Our devisers</h3>
	<h5 class="text-center" style="color: white;">Discover innovative and talented creators from around the globe</h5>
	<form name="discoverCtrl.form">
		<div class="col-md-4 col-md-offset-4">
			<input type="text" class="form-control" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search(discoverCtrl.form)" placeholder="Search keyword">
		</div>
	</form>
	<div class="col-md-10 col-md-offset-2">
		<discover-results key="{{discoverCtrl.key_search}}" results="discoverCtrl.results" ng-if="discoverCtrl.results" ng-cloak></discover-results>
	</div>
</div>