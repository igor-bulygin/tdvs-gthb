<?php
namespace app\modules\api\pub\v1\forms;

use yii\base\Model;

class BecomeInfluencerForm extends Model {

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $representative_name;

	/**
	 * @var string
	 */
	public $brand_name;

	/**
	 * @var string
	 */
	public $phone_number;

	/**
	 * @var string
	 */
	public $creations_description;

	/**
	 * @var array
	 */
	public $urls_portfolio;

	/**
	 * @var array
	 */
	public $urls_video;
	
	/**
	 * @var string
	 */
	public $observations;

	public function init()
	{
		parent::init();
		$this->urls_portfolio = [];
		$this->urls_video = [];

	}

	public function rules()
	{
		return [
			[['email', 'representative_name', 'creations_description', 'urls_portfolio'], 'required'],
			[['email'], 'email'],
			[['urls_portfolio', 'urls_video'], 'each', 'rule' => ['url', 'defaultScheme' => 'http']],
			[['brand_name', 'phone_number', 'observations'], 'safe'],
		];
	}

}
