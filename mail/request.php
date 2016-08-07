
<div><p><strong>Name:</strong><?=$post['name']?></p></div>
<div><p><strong>Brand Name:</strong><?=$post['brand']?></p></div>
<div><p><strong>Email:</strong><?=$post['email']?></p></div>
<div><p><strong>Phone:</strong><?=$post['phone']?></p></div>
<div><p><strong>What do you create?:</strong><?=$post['create']?></p></div>
<div><p><strong>Portfolio:</strong></p></div>

<?php foreach($post['portfolio'] as $portf){ ?>
	<div><p><strong>-</strong><?=$portf?></p></div>
<?php } ?>

<div><p><strong>Video:</strong></p></div>

<?php foreach($post['video'] as $vid){ ?>
	<div><p><strong>-</strong><?=$vid?></p></div>
<?php } ?>
<div><p><strong>Observations:</strong><?=$post['observations']?></p></div>
