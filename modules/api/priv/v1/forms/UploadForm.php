<?php
namespace app\modules\api\priv\v1\forms;

use app\models\Person;
use Yii;
use app\helpers\Utils;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

	const UPLOAD_TYPE_DEVISER_MEDIA_HEADER = 'deviser-media-header';
	const UPLOAD_TYPE_DEVISER_MEDIA_PROFILE = 'deviser-media-profile';
	const UPLOAD_TYPE_DEVISER_MEDIA_PHOTOS = 'deviser-media-photos';
	const UPLOAD_TYPE_DEVISER_PRESS_IMAGES = 'deviser-press';

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string
	 */
	public $deviser_id;

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
			[['deviser_id', 'product_id'], 'safe'],
			[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
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
			$this->filename = $this->getPrefix() . uniqid() . '.' . Utils::getFileExtensionFromMimeType($this->file->type);

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
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_HEADER:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PROFILE:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_DEVISER_PRESS_IMAGES:
				$path = Utils::join_paths(Yii::getAlias("@deviser"), $this->deviser_id);
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
			UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_HEADER => 'deviser.header.',
			UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PROFILE => 'deviser.profile.',
			UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PHOTOS => 'deviser.photo.',
			UploadForm::UPLOAD_TYPE_DEVISER_PRESS_IMAGES => 'deviser.press.',
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
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_HEADER:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PROFILE:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_DEVISER_PRESS_IMAGES:
				if (empty($this->deviser_id)) {
					$this->addError($attribute, 'Deviser id must be specified');
				}
				if (empty($this->getDeviser())) {
					$this->addError($attribute, 'Deviser not found');
				}
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
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_HEADER:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PROFILE:
			case UploadForm::UPLOAD_TYPE_DEVISER_MEDIA_PHOTOS:
			case UploadForm::UPLOAD_TYPE_DEVISER_PRESS_IMAGES:
				$url = (Yii::getAlias("@deviser_url") . "/" . $this->deviser_id . "/" . $this->filename);
				break;
			default:
				$url = null;
				break;
		}
		return $url;
	}

	/**
	 * Get the Deviser related with the upload
	 *
	 * @return Person
	 */
	public function getDeviser()
	{
		return Person::findOne(['short_id' => $this->deviser_id]);
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
