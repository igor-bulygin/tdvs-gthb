<?php
namespace app\modules\api\priv\v1\forms;

use app\helpers\Utils;
use app\models\Order;
use app\models\Person;
use app\models\Product;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

	const UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL = 'deviser-media-header-original';
	const UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED = 'deviser-media-header-cropped';
	const UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL = 'deviser-media-header-cropped-small';
	const UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL = 'deviser-media-profile-original';
	const UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED = 'deviser-media-profile-cropped';
	const UPLOAD_TYPE_PERSON_MEDIA_PHOTOS = 'deviser-media-photos';
	const UPLOAD_TYPE_PERSON_PRESS_IMAGES = 'deviser-press';
	const UPLOAD_TYPE_PERSON_CURRICULUM = 'deviser-curriculum';

	const UPLOAD_TYPE_PERSON_STORY_PHOTOS = 'story-photos';

	const UPLOAD_TYPE_PERSON_PACK_INVOICE = 'person-pack-invoice';

	const UPLOAD_TYPE_BANNER_IMAGE = 'banner-image';

	const UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO = 'known-product-photo';
	const UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO = 'unknown-product-photo';

	const SCENARIO_UPLOAD_DEVISER_IMAGE = 'scenario-upload-deviser-image';
	const SCENARIO_UPLOAD_DEVISER_CURRICULUM = 'scenario-upload-deviser-curriculum';
	const SCENARIO_UPLOAD_DEVISER_INVOICE = 'scenario-upload-deviser-invoice';
	const SCENARIO_UPLOAD_PRODUCT_IMAGE = 'scenario-upload-product-image';
	const SCENARIO_UPLOAD_BANNER_IMAGE = 'scenario-upload-banner-image';


	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string
	 */
	public $person_id;

	/**
	 * @var string
	 */
	public $product_id;

	/**
	 * @var string
	 */
	public $pack_id;

	/**
	 * @var UploadedFile
	 */
	public $file;

	/**
	 * @var string
	 */
	public $pathUpload;
	
	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	public $filename;

	public function rules()
	{
		return [
			[['type'], 'validateType'],
			[['person_id', 'product_id', 'pack_id'], 'safe'],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg'], 'on' => [self::SCENARIO_UPLOAD_DEVISER_IMAGE, self::SCENARIO_UPLOAD_PRODUCT_IMAGE]],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['pdf'], 'on' => [self::SCENARIO_UPLOAD_DEVISER_INVOICE]],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'], 'on' => self::SCENARIO_UPLOAD_DEVISER_CURRICULUM],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg'], 'on' => [self::SCENARIO_UPLOAD_BANNER_IMAGE]],
		];
	}

	/**
	 * Validate data, and save uploaded file in the appropriated path, depending on their use context
	 *
	 * @return bool
	 */
	public function upload()
	{
		if ($this->validate()) {

			$this->pathUpload = $this->getPathUpload();

			if ($this->type == UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM) {
				$this->filename = $this->file->name;
			} else {
				$this->filename = $this->getPrefix() . uniqid() . '.' . Utils::getFileExtensionFromMimeType($this->file->type);
			}

			if (!file_exists($this->pathUpload)) {
				Utils::mkdir($this->pathUpload);
			}

			$this->file->saveAs($this->pathUpload . '/' . $this->filename);

			$this->url = $this->getUrlUpload();

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the path to save the file
	 *
	 * @return string
	 */
	private function getPathUpload()
	{

		switch ($this->type) {
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PACK_INVOICE:
				$path = Utils::join_paths(Yii::getAlias("@deviser"), $this->person_id);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
				$path = Utils::join_paths(Yii::getAlias("@product"), $this->product_id);
				break;
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$path = Utils::join_paths(Yii::getAlias("@product"), "temp");
				break;
			case UploadForm::UPLOAD_TYPE_BANNER_IMAGE:
				$path = Utils::join_paths(Yii::getAlias("@banner"), "");
				break;
			default:
				$path = Yii::getAlias("@uploads");
				break;
		}
		return $path;
	}

	/**
	 * Get the prefix that must be used to rename the file
	 *
	 * @return string
	 */
	private function getPrefix()
	{
		$prefixes = [
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL => 'person.header.original.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED => 'person.header.cropped.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL => 'person.header.cropped.small.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL => 'person.profile.original.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED => 'person.profile.cropped.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS => 'person.photo.',
			UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES => 'person.press.',
			UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM => 'person.cv.',
			UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS => 'person.story.',
			UploadForm::UPLOAD_TYPE_PERSON_PACK_INVOICE => 'person.pack.invoice.',
			UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO => 'product.photo.',
			UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO => 'product.photo.',
			UploadForm::UPLOAD_TYPE_BANNER_IMAGE => 'banner.image.',

		];

		return $prefixes[$this->type];
	}

	/**
	 * Custom validator for type param
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateType($attribute, $params)
	{
		switch ($this->$attribute) {
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				if (empty($this->person_id)) {
					$this->addError($attribute, 'Person id must be specified');
				}
				if (empty($this->getPerson())) {
					$this->addError($attribute, 'Person not found');
				}
				break;
			case UploadForm::UPLOAD_TYPE_PERSON_PACK_INVOICE:
				if (empty($this->person_id)) {
					$this->addError($attribute, 'Person id must be specified');
				}
				if (empty($this->pack_id)) {
					$this->addError($attribute, 'Pack id must be specified');
				} else {
					$pack = $this->getPack();
					if (empty($pack)) {
						$this->addError($attribute, 'Pack not found');
					}
					if ($pack['packs'][0]['deviser_id'] != $this->person_id) {
						$this->addError($attribute, 'This pack does not belongs to the specified person');
					}
				}
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
				if (empty($this->product_id)) {
					$this->addError($attribute, 'Product id must be specified');
				}
				if (empty($this->getProduct())) {
					$this->addError($attribute, 'Product not found');
				}
				break;
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				break;
			case UploadForm::UPLOAD_TYPE_BANNER_IMAGE:
				break;
			default:
				$this->addError($attribute, 'Invalid type');
				break;
		}
	}

	/**
	 * Get the url where is the uploaded file
	 *
	 * @return string|null
	 */
	private function getUrlUpload()
	{
		switch ($this->type) {
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PACK_INVOICE:
				$url = (Yii::getAlias("@deviser_url") . "/" . $this->person_id . "/" . $this->filename);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
				$url = (Yii::getAlias("@product_url") . "/" . $this->product_id . "/" . $this->filename);
				break;
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$url = (Yii::getAlias("@product_url") . "/temp/" . $this->filename);
				break;
			case UploadForm::UPLOAD_TYPE_BANNER_IMAGE:
				$url = (Yii::getAlias("@banner_url") . "/" . $this->filename);
				break;
			default:
				$url = null;
				break;
		}
		return $url;
	}

	/**
	 * Set the scenario rules, according to upload "type"
	 *
	 * @return UploadForm
	 */
	public function setScenarioByUploadType()
	{
		switch ($this->type) {
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED_SMALL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_DEVISER_IMAGE);
				break;
			case UploadForm::UPLOAD_TYPE_PERSON_PACK_INVOICE:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_DEVISER_INVOICE);
				break;
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_DEVISER_CURRICULUM);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_PRODUCT_IMAGE);
				break;
			case UploadForm::UPLOAD_TYPE_BANNER_IMAGE:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_BANNER_IMAGE);
				break;
			default:
				$url = null;
				break;
		}

		return $this;
	}

	/**
	 * Get the Person related with the upload
	 *
	 * @return Person
	 */
	public function getPerson()
	{
		return Person::findOne(['short_id' => $this->person_id]);
	}

	/**
	 * Get the Product related with the upload
	 *
	 * @return Product
	 */
	public function getProduct()
	{
		return Product::findOne(['short_id' => $this->product_id]);
	}

	/**
	 * Get the Order related with the upload
	 *
	 * @return Order
	 */
	public function getPack()
	{
		$orders = Order::findSerialized([
			"deviser_id" => $this->person_id,
			"pack_id" => $this->pack_id,
			"only_matching_packs" => true,
			"order_state" => Order::ORDER_STATE_PAID,
			"limit" => 1,
		]);
		if ($orders && isset($orders[0]['packs'])) {
			return $orders[0];
		}

		return null;
	}

	/**
	 * Serialize fields
	 *
	 * @return array
	 */
	public function fields()
	{
		return ["filename", "url"];
	}
}
