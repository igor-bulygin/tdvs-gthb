<?php
namespace app\modules\api\priv\v1\forms;

use app\helpers\Utils;
use app\models\Person;
use app\models\Product2;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

	const UPLOAD_TYPE_PERSON_MEDIA_HEADER_ORIGINAL = 'deviser-media-header-original';
	const UPLOAD_TYPE_PERSON_MEDIA_HEADER_CROPPED = 'deviser-media-header-cropped';
	const UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL = 'deviser-media-profile-original';
	const UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED = 'deviser-media-profile-cropped';
	const UPLOAD_TYPE_PERSON_MEDIA_PHOTOS = 'deviser-media-photos';
	const UPLOAD_TYPE_PERSON_PRESS_IMAGES = 'deviser-press';
	const UPLOAD_TYPE_PERSON_CURRICULUM = 'deviser-curriculum';

	const UPLOAD_TYPE_PERSON_STORY_PHOTOS = 'story-photos';

	const UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO = 'known-product-photo';
	const UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO = 'unknown-product-photo';

	const SCENARIO_UPLOAD_DEVISER_IMAGE = 'scenario-upload-deviser-image';
	const SCENARIO_UPLOAD_DEVISER_CURRICULUM = 'scenario-upload-deviser-curriculum';
	const SCENARIO_UPLOAD_PRODUCT_IMAGE = 'scenario-upload-product-image';


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
			[['person_id', 'product_id'], 'safe'],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg'], 'on' => [self::SCENARIO_UPLOAD_DEVISER_IMAGE, self::SCENARIO_UPLOAD_PRODUCT_IMAGE]],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg', 'pdf'], 'on' => self::SCENARIO_UPLOAD_DEVISER_CURRICULUM],
		];
	}

	/**
	 * Validate data, and save uploaded file in the appropriated path, depending on their use context
	 *
	 * @return bool
	 */
	public function upload()
	{
		Yii::warning('uplad init');

		if ($this->validate()) {
			Yii::warning('upload after validate');

			$this->pathUpload = $this->getPathUpload();
			Yii::warning('upload pathUpload: '.$this->pathUpload);

			if ($this->type == UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM) {
				$this->filename = $this->file->name;
			} else {
				$this->filename = $this->getPrefix() . uniqid() . '.' . Utils::getFileExtensionFromMimeType($this->file->type);
			}
			Yii::warning('upload filename: '.$this->filename);

			if (!file_exists($this->pathUpload)) {
				Yii::warning('upload before mkdir '.$this->pathUpload);
				Utils::mkdir($this->pathUpload);
				Yii::warning('upload after mkdir '.$this->pathUpload);
			}

			Yii::warning('upload before save in '.$this->pathUpload.'/'.$this->filename);
			$this->file->saveAs($this->pathUpload . '/' . $this->filename);
			Yii::warning('upload after save');

			$this->url = $this->getUrlUpload();

			Yii::warning('upload end');
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
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				$path = Utils::join_paths(Yii::getAlias("@deviser"), $this->person_id);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
				$path = Utils::join_paths(Yii::getAlias("@product"), $this->product_id);
				break;
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$path = Utils::join_paths(Yii::getAlias("@product"), "temp");
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
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL => 'person.profile.original.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED => 'person.profile.cropped.',
			UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS => 'person.photo.',
			UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES => 'person.press.',
			UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM => 'person.cv.',
			UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS => 'person.story.',

			UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO => 'product.photo.',
			UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO => 'product.photo.',

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
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				if (empty($this->person_id)) {
					$this->addError($attribute, 'Deviser id must be specified');
				}
				if (empty($this->getDeviser())) {
					$this->addError($attribute, 'Deviser not found');
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
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				$url = (Yii::getAlias("@deviser_url") . "/" . $this->person_id . "/" . $this->filename);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
				$url = (Yii::getAlias("@product_url") . "/" . $this->product_id . "/" . $this->filename);
				break;
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$url = (Yii::getAlias("@product_url") . "/temp/" . $this->filename);
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
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_ORIGINAL:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PROFILE_CROPPED:
			case UploadForm::UPLOAD_TYPE_PERSON_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_PERSON_PRESS_IMAGES:
			case UploadForm::UPLOAD_TYPE_PERSON_STORY_PHOTOS:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_DEVISER_IMAGE);
				break;
			case UploadForm::UPLOAD_TYPE_PERSON_CURRICULUM:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_DEVISER_CURRICULUM);
				break;
			case UploadForm::UPLOAD_TYPE_KNOWN_PRODUCT_PHOTO:
			case UploadForm::UPLOAD_TYPE_UNKNOWN_PRODUCT_PHOTO:
				$this->setScenario(UploadForm::SCENARIO_UPLOAD_PRODUCT_IMAGE);
				break;
			default:
				$url = null;
				break;
		}

		return $this;
	}

	/**
	 * Get the Deviser related with the upload
	 *
	 * @return Person
	 */
	public function getDeviser()
	{
		return Person::findOne(['short_id' => $this->person_id]);
	}

	/**
	 * Get the Product related with the upload
	 *
	 * @return Product2
	 */
	public function getProduct()
	{
		return Product2::findOne(['short_id' => $this->product_id]);
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
