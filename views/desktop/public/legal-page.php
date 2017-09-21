<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = $title;

?>

<div class="about-us-wrapper">
	<div class="container">
		<h1><?=$title?></h1>
		<?=$text?>
		<br />
		<br />
		<br />
	</div>
</div>
