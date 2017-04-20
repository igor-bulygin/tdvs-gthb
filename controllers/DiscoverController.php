<?php
namespace app\controllers;

use app\helpers\CController;

class DiscoverController extends CController
{
	public $defaultAction = "index";

	public function actionInfluencers()
	{
		$this->layout = '/desktop/public-2.php';
		return $this->render("influencers", []);
	}

	public function actionDevisers()
	{
		$this->layout = '/desktop/public-2.php';
		return $this->render("devisers", []);
	}

	public function actionBoxes()
	{
		$this->layout = '/desktop/public-2.php';
		return $this->render("boxes", []);
	}

	public function actionStories()
	{
		$this->layout = '/desktop/public-2.php';
		return $this->render("stories", []);
	}

}
