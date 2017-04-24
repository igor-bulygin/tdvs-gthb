<?php
use app\assets\desktop\pub\StoriesViewAsset;
use app\models\Person;
use yii\helpers\Json;

/** @var Person $person */
/** @var \app\models\Story $story */

$this->title = 'Story '.$story->getTitle().' by ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var story = ".Json::encode($story), yii\web\View::POS_HEAD, 'story-script');

$firstText = $story->getFirstTextComponent();
$text = $firstText ? $firstText->getText() : null;
$photoUrl = $story->mainMediaMapping->getPhotoUrl();

?>


<a href="<?=$story->getViewLink()?>">
	<div>

		<div class="title"><?= $story->getTitle() ?></div>

		<div><?=$story->getPerson()->getName()?></div>

		<div>Fashion designer</div>

		<?php if ($photoUrl) { ?>
			<div><img src="<?=$photoUrl?>" class="img-responsive" /></div>
		<?php } ?>

		<div><?=$text?></div>

	</div>
</a>