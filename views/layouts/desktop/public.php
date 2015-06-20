<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use lajax\languagepicker\widgets\LanguagePicker;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>
	<body>

		<?php $this->beginBody() ?>

			<div class="container-fluid no-horizontal-padding max-height">
				<div class="row no-gutter max-height">

					<!-- NAVBAR LEFT -->
					<div class="col-lg-1-5">
						<div class="navbar-left fixed">
							<ul class="list-group">
								<li class="logo">todevise<br/><span>A new concept of store</span></li>
								<li class="list-group-item">Art</li>
								<li class="list-group-item">Fashion</li>
								<li class="list-group-item">Industrial Design</li>
								<li class="list-group-item">Jewelery</li>
								<li class="list-group-item">More</li>
							</ul>
						</div>
					</div>

					<!-- CONTENT TOP MENU / BODY / FOOTER -->
					<div class="col-lg-10-5">

						<div class="row">
							<!-- NEMU TOP -->
							<div class="fixed">
								<div class="col-lg-12">

										<?php
											NavBar::begin([
												'brandUrl' => Yii::$app->homeUrl,
												'options' => [
													'class' => 'navbar-inverse',
												],
											]);

											echo Nav::widget([
												'options' => ['class' => 'navbar-nav'],
												'items' => [
													['label' => 'Home', 'url' => ['/site/index']],
													['label' => 'About', 'url' => ['/site/about']],
													['label' => 'Contact', 'url' => ['/site/contact']],
													Yii::$app->user->isGuest ?
														['label' => 'Login', 'url' => ['/site/login']] :
														['label' => 'Logout (' . Yii::$app->user->identity->personal_info["name"] . ')',
															'url' => ['/site/logout'],
															'linkOptions' => ['data-method' => 'post']],
												],
											]);
											NavBar::end();
										?>
									</div>
							</div>
						</div>
							<!-- BODY CONTENT -->
						<div class="container-fluid">
							<div class="row">
								<div class="section">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur diam quam, tincidunt in hendrerit mollis, luctus a lacus. Nam pellentesque ligula ut eros maximus, vel consequat nulla gravida. Mauris scelerisque, sapien vitae molestie ultrices, libero dolor interdum ipsum, ut lacinia enim elit id sapien. Praesent ultricies, neque quis convallis tempor, nisi risus imperdiet ante, sed dignissim neque nisl malesuada mi. Sed tempus id diam quis lacinia. Cras pharetra maximus purus in eleifend. Aliquam massa libero, malesuada a dignissim quis, vulputate eu diam. Maecenas et libero id sapien sollicitudin vestibulum eget vel nisi. Donec volutpat pulvinar augue, ac condimentum turpis ultricies id. Ut sem eros, lobortis eget sem a, scelerisque luctus tellus. Donec et laoreet turpis. Nunc aliquam posuere porta. In mauris mi, condimentum ut volutpat et, volutpat vel justo. Nullam vel hendrerit arcu. Morbi non risus tellus.
									<br>
									<br>
									Ut eu tortor dolor. Nullam convallis eros fringilla velit varius interdum. Aenean malesuada erat a luctus scelerisque. Vivamus in arcu ornare, pulvinar velit vehicula, ultricies tortor. Nam eget ligula nec eros consectetur blandit ac ac massa. Praesent finibus, mauris at semper sodales, quam lectus maximus sapien, non tristique nulla augue eget diam. Etiam ultricies viverra quam vel placerat. Sed at vestibulum dolor. Phasellus diam augue, commodo eu auctor vel, pretium at purus. Maecenas non finibus augue. Maecenas ut quam nibh. Quisque quis tempor ante.
								</div>
								<!-- FOOTER -->
								<div class="section">
									<div class="footer" style="display:block;">
										<div class="container">
											<p class="pull-left">&copy; Todevise <?= date('Y') ?></p>
											<p class="pull-right"><?= Yii::powered() ?></p>
										</div>
									</div>
								</section>
							</div>

						</div>

					</div>

				</div>
			</div>

		<?php $this->endBody() ?>

	</body>
</html>
<?php $this->endPage() ?>
