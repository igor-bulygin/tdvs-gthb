<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Login';

?>

<?php if ($invalidLogin) { ?>
	<div class="alert alert-danger">Invalid login</div>
<?php } ?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
	<label for="email">Email</label>
	<input type="email" id="email" name="Login[email]" class="form-control" />
</div>

<div class="row">
	<label for="password">Password</label>
	<input type="password" id="password" name="Login[password]" class="form-control" />
</div>

<div class="row">
	<label>
		<input type="checkbox" name="Login[rememberMe]" />Remember me
	</label>
</div>

<div class="row">
	<button type="submit" class="btn btn-default btn-black">Login</button>
</div>

<?php ActiveForm::end(); ?>

