<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

IndexStoryAsset::register($this);


/** @var Person $person */
/** @var array $photos */
$this->title = Yii::t('app/public',
	'SOCIAL_FEED_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'followers';
$this->params['person_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="content-store">
					<nav>
						<ul>
							<li class="<?=$type == 'follow' ? 'active' : ''?>">
								<a href="<?=$person->getFollowLink()?>">Follow</a>
							</li>
							<li class="<?=$type == 'followers' ? 'active' : ''?>">
								<a href="<?=$person->getFollowersLink()?>">Followers</a>
							</li>
						</ul>
					</nav>
					<nav>
						<ul>
							<li role="button" class="active toggle toggle-all" onclick="togglePersons('all')">All</li>
							<li role="button" class="toggle toggle-influencer" onclick="togglePersons('influencer')">Influencers</li>
							<li role="button" class="toggle toggle-deviser" onclick="togglePersons('deviser')">Devisers</li>
							<li role="button" class="toggle toggle-client" onclick="togglePersons('client')">Members</li>
						</ul>
					</nav>
					<?php foreach ($persons as $person) { ?>
						<div class="col-md-4 person person-<?=$person->getPersonTypeForUrl()?>">
							<?=\app\components\Person::widget(['person' => $person]) ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function togglePersons(type) {
		if (type == 'all') {
			$('.person').show();
		} else {
			$('.person').hide();
			$('.person-'+type).show();
		}
		$('.toggle').removeClass('active');
		$('.toggle.toggle-'+type).addClass('active');
	}
</script>