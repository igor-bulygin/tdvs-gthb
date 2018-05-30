<?php
namespace app\models;

class TimelineDetail
{
	public $target_id;
	public $target_object;

	public $action_type;
	public $action_name;

	public $title;
	public $description;
	public $photo;
	public $link;

	public function __construct($action_type, $target_id)
	{
		$this->action_type = $action_type;
		$this->target_id = $target_id;

		$this->fill();
	}

	protected function fill()
	{
		switch ($this->action_type) {

			case Timeline::ACTION_BOX_CREATED:
				$this->action_name = 'Created a box';
				$this->fillFromBox();
				break;

			case Timeline::ACTION_BOX_UPDATED:
				$this->action_name = 'Updated a box';
				$this->fillFromBox();
				break;

			case Timeline::ACTION_BOX_LOVED:
				$this->action_name = 'Loved a box';
				$this->fillFromBox();
				break;

			case Timeline::ACTION_PRODUCT_CREATED:
				$this->action_name = 'Created a product';
				$this->fillFromProduct();
				break;

			case Timeline::ACTION_PRODUCT_UPDATED:
				$this->action_name = 'Updated a product';
				$this->fillFromProduct();
				break;

			case Timeline::ACTION_PRODUCT_LOVED:
				$this->action_name = 'Loved a product';
				$this->fillFromProduct();
				break;

			case Timeline::ACTION_POST_CREATED:
				$this->action_name = 'Created a post';
				$this->fillFromPost();
				break;

			case Timeline::ACTION_POST_UPDATED:
				$this->action_name = 'Updated a post';
				$this->fillFromPost();
				break;

			case Timeline::ACTION_POST_LOVED:
				$this->action_name = 'Loved a post';
				$this->fillFromPost();
				break;

			case Timeline::ACTION_TIMELINE_LOVED:
				$this->action_name = 'Loved an action';
				$this->fillFromTimeline();
				break;

			case Timeline::ACTION_PERSON_FOLLOWED:
				$this->action_name = 'Followed someone';
				$this->fillFromPerson();
				break;
		}
	}

	protected function fillFromBox()
	{
		$box = Box::findOneSerialized($this->target_id);
		if (!$box) {
			return;
		}
		$this->title = $box->name;
		$this->description = $box->description;
		$this->photo = $box->getMainPhoto(600, 260);
		$this->link = $box->getViewLink();
	}

	protected function fillFromProduct()
	{
		$product = Product::findOneSerialized($this->target_id);
		if (!$product) {
			return;
		}
		$this->title = $product->name;
		$this->description = $product->description;
		$this->photo = $product->getImagePreview(600, 260);
		$this->link = $product->getViewLink();
	}

	protected function fillFromPost()
	{
		$post = Post::findOneSerialized($this->target_id);
		if (!$post) {
			return;
		}
		$this->title = null;
		$this->description = $post->text;
		$this->photo = $post->getImagePreview(600, 260);
		$this->link = null;
	}

	protected function fillFromTimeline()
	{
		$timeline = Timeline::findOneSerialized($this->target_id);
		if (!$timeline) {
			return;
		}
		$this->title = null;
		$this->description = $timeline->text;
		$this->photo = $timeline->getImagePreview(600, 260);
		$this->link = null;
	}

	protected function fillFromPerson()
	{
		$person = Person::findOneSerialized($this->target_id);
		if (!$person) {
			return;
		}
		$this->title = $person->getName();
		$this->description = $person->text_biography;
		$this->photo = $person->getHeaderImage(600, 260);
		$this->link = $person->getMainLink();
	}
}