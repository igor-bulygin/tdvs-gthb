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
					<div class="col-lg-1-5 col-md-1-5">
						<div class="navbar-left funit_ultra fs1">
							<ul class="list-group">
								<li class="logo fpf_bold">todevise<br/><span class="fpf_i">A new concept of store</span></li>
								<li class="list-group-item">Art</li>
								<li class="list-group-item">Fashion</li>
								<li class="list-group-item">Industrial Design</li>
								<li class="list-group-item">Jewelery</li>
								<li class="list-group-item">More</li>
							</ul>
						</div>
					</div>

					<!-- CONTENT TOP MENU / BODY / FOOTER -->
					<div class="col-lg-10-5 col-md-10-5">

						<div class="wrapper">
							<div class="header">

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
							<div class="content">
								<div class="main">
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tincidunt sagittis felis, sit amet cursus massa lobortis nec. Nunc vel enim dui. Aenean congue vestibulum elit rutrum dictum. In dignissim est nunc, quis suscipit libero pretium ut. Integer velit nulla, aliquet vel fringilla et, dignissim eu leo. Ut mattis ullamcorper purus in vulputate. Etiam iaculis pretium ligula. Proin at neque at sapien ullamcorper accumsan ac nec est. Vivamus metus nisi, ullamcorper ac lectus at, feugiat tempus libero. Aliquam convallis libero id nunc rhoncus condimentum nec quis tortor. Morbi rhoncus iaculis viverra.
									</p>
									<p>
										Vestibulum varius aliquam congue. Nunc condimentum egestas ullamcorper. Maecenas aliquet finibus metus feugiat interdum. Pellentesque sed fermentum nisi, at laoreet magna. Curabitur dignissim, libero eu commodo maximus, magna sapien faucibus ligula, a condimentum nisi dui non quam. Curabitur feugiat nisl eros, et consequat ligula fermentum eget. Suspendisse varius, orci eget ullamcorper faucibus, sem purus porta sapien, id cursus risus magna eu est. Quisque nunc ligula, lacinia sit amet condimentum id, aliquam vel mi. Nullam aliquet blandit ligula, sit amet euismod elit pharetra in.
									</p>
									<p>
										Fusce non erat nec justo blandit finibus et a metus. Etiam accumsan faucibus sagittis. In venenatis diam in quam aliquam, nec dictum elit blandit. Proin tincidunt, dolor porta ornare suscipit, nisi elit lacinia mauris, eu sollicitudin ipsum nisl vel quam. Nullam sit amet rhoncus nibh. Phasellus tristique facilisis aliquam. Fusce bibendum enim sed lorem feugiat imperdiet.
									</p>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tincidunt sagittis felis, sit amet cursus massa lobortis nec. Nunc vel enim dui. Aenean congue vestibulum elit rutrum dictum. In dignissim est nunc, quis suscipit libero pretium ut. Integer velit nulla, aliquet vel fringilla et, dignissim eu leo. Ut mattis ullamcorper purus in vulputate. Etiam iaculis pretium ligula. Proin at neque at sapien ullamcorper accumsan ac nec est. Vivamus metus nisi, ullamcorper ac lectus at, feugiat tempus libero. Aliquam convallis libero id nunc rhoncus condimentum nec quis tortor. Morbi rhoncus iaculis viverra.
									</p>
									<p>
										Vestibulum varius aliquam congue. Nunc condimentum egestas ullamcorper. Maecenas aliquet finibus metus feugiat interdum. Pellentesque sed fermentum nisi, at laoreet magna. Curabitur dignissim, libero eu commodo maximus, magna sapien faucibus ligula, a condimentum nisi dui non quam. Curabitur feugiat nisl eros, et consequat ligula fermentum eget. Suspendisse varius, orci eget ullamcorper faucibus, sem purus porta sapien, id cursus risus magna eu est. Quisque nunc ligula, lacinia sit amet condimentum id, aliquam vel mi. Nullam aliquet blandit ligula, sit amet euismod elit pharetra in.
									</p>
									<p>
										Fusce non erat nec justo blandit finibus et a metus. Etiam accumsan faucibus sagittis. In venenatis diam in quam aliquam, nec dictum elit blandit. Proin tincidunt, dolor porta ornare suscipit, nisi elit lacinia mauris, eu sollicitudin ipsum nisl vel quam. Nullam sit amet rhoncus nibh. Phasellus tristique facilisis aliquam. Fusce bibendum enim sed lorem feugiat imperdiet.
									</p>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tincidunt sagittis felis, sit amet cursus massa lobortis nec. Nunc vel enim dui. Aenean congue vestibulum elit rutrum dictum. In dignissim est nunc, quis suscipit libero pretium ut. Integer velit nulla, aliquet vel fringilla et, dignissim eu leo. Ut mattis ullamcorper purus in vulputate. Etiam iaculis pretium ligula. Proin at neque at sapien ullamcorper accumsan ac nec est. Vivamus metus nisi, ullamcorper ac lectus at, feugiat tempus libero. Aliquam convallis libero id nunc rhoncus condimentum nec quis tortor. Morbi rhoncus iaculis viverra.
									</p>
									<p>
										Vestibulum varius aliquam congue. Nunc condimentum egestas ullamcorper. Maecenas aliquet finibus metus feugiat interdum. Pellentesque sed fermentum nisi, at laoreet magna. Curabitur dignissim, libero eu commodo maximus, magna sapien faucibus ligula, a condimentum nisi dui non quam. Curabitur feugiat nisl eros, et consequat ligula fermentum eget. Suspendisse varius, orci eget ullamcorper faucibus, sem purus porta sapien, id cursus risus magna eu est. Quisque nunc ligula, lacinia sit amet condimentum id, aliquam vel mi. Nullam aliquet blandit ligula, sit amet euismod elit pharetra in.
									</p>
									<p>
										Fusce non erat nec justo blandit finibus et a metus. Etiam accumsan faucibus sagittis. In venenatis diam in quam aliquam, nec dictum elit blandit. Proin tincidunt, dolor porta ornare suscipit, nisi elit lacinia mauris, eu sollicitudin ipsum nisl vel quam. Nullam sit amet rhoncus nibh. Phasellus tristique facilisis aliquam. Fusce bibendum enim sed lorem feugiat imperdiet.
									</p>
									<p>
										Vestibulum varius aliquam congue. Nunc condimentum egestas ullamcorper. Maecenas aliquet finibus metus feugiat interdum. Pellentesque sed fermentum nisi, at laoreet magna. Curabitur dignissim, libero eu commodo maximus, magna sapien faucibus ligula, a condimentum nisi dui non quam. Curabitur feugiat nisl eros, et consequat ligula fermentum eget. Suspendisse varius, orci eget ullamcorper faucibus, sem purus porta sapien, id cursus risus magna eu est. Quisque nunc ligula, lacinia sit amet condimentum id, aliquam vel mi. Nullam aliquet blandit ligula, sit amet euismod elit pharetra in.
									</p>
									<p>
										Fusce non erat nec justo blandit finibus et a metus. Etiam accumsan faucibus sagittis. In venenatis diam in quam aliquam, nec dictum elit blandit. Proin tincidunt, dolor porta ornare suscipit, nisi elit lacinia mauris, eu sollicitudin ipsum nisl vel quam. Nullam sit amet rhoncus nibh. Phasellus tristique facilisis aliquam. Fusce bibendum enim sed lorem feugiat imperdiet.
									</p>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tincidunt sagittis felis, sit amet cursus massa lobortis nec. Nunc vel enim dui. Aenean congue vestibulum elit rutrum dictum. In dignissim est nunc, quis suscipit libero pretium ut. Integer velit nulla, aliquet vel fringilla et, dignissim eu leo. Ut mattis ullamcorper purus in vulputate. Etiam iaculis pretium ligula. Proin at neque at sapien ullamcorper accumsan ac nec est. Vivamus metus nisi, ullamcorper ac lectus at, feugiat tempus libero. Aliquam convallis libero id nunc rhoncus condimentum nec quis tortor. Morbi rhoncus iaculis viverra.
									</p>
									<p>
										Vestibulum varius aliquam congue. Nunc condimentum egestas ullamcorper. Maecenas aliquet finibus metus feugiat interdum. Pellentesque sed fermentum nisi, at laoreet magna. Curabitur dignissim, libero eu commodo maximus, magna sapien faucibus ligula, a condimentum nisi dui non quam. Curabitur feugiat nisl eros, et consequat ligula fermentum eget. Suspendisse varius, orci eget ullamcorper faucibus, sem purus porta sapien, id cursus risus magna eu est. Quisque nunc ligula, lacinia sit amet condimentum id, aliquam vel mi. Nullam aliquet blandit ligula, sit amet euismod elit pharetra in.
									</p>
									<p>
										Fusce non erat nec justo blandit finibus et a metus. Etiam accumsan faucibus sagittis. In venenatis diam in quam aliquam, nec dictum elit blandit. Proin tincidunt, dolor porta ornare suscipit, nisi elit lacinia mauris, eu sollicitudin ipsum nisl vel quam. Nullam sit amet rhoncus nibh. Phasellus tristique facilisis aliquam. Fusce bibendum enim sed lorem feugiat imperdiet.
									</p>
								</div>

								<div class="footer">footer</div>
							</div>

						</div>

					</div>

				</div>
			</div>

		<?php $this->endBody() ?>

	</body>
</html>
<?php $this->endPage() ?>
