<?php
use app\assets\desktop\pub\Login2Asset;
use app\assets\desktop\pub\PublicCommonAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Login';
Login2Asset::register($this);

?>
<div class="create-deviser-account-wrapper">
	<div class="row">
		<div class="col-sm-6 col-md-4 col-md-offset-4">

	
				<form class="form-signin" action="/" method="POST">
					<input type="text" name="Login[email]" class="form-control" placeholder="Email" required autofocus>
					<input type="password" name="Login[password]" class="form-control" placeholder="Password" required>
					<button class="btn btn-lg btn-primary btn-block" type="submit">
						Sign in</button>
				</form>

		</div>
	</div>
</div>
