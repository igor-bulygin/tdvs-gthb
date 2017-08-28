<?php
namespace app\helpers;

use app\models\EmbedModel;
use app\models\Lang;
use Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * @property mixed _id
 * @property string short_id
 */
class CActiveRecord extends ActiveRecord
{

	const SERIALIZE_SCENARIO_PREVIEW = 'serialize_scenario_preview';
	const SERIALIZE_SCENARIO_PUBLIC = 'serialize_scenario_public';
	const SERIALIZE_SCENARIO_OWNER = 'serialize_scenario_owner';
	const SERIALIZE_SCENARIO_ADMIN = 'serialize_scenario_admin';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	/** @var  int */
	public static $countItemsFound = 0;

	/**
	 * Determine if serialization have to translate "translatable" attributes automatically
	 *
	 * @var bool
	 */
	protected static $translateFields = true;

	/**
	 * The attributes that should be translatable
	 *
	 * @var array
	 */
	public static $translatedAttributes = [];

	/**
	 * The attributes that should be used when a keyword search is done
	 *
	 * @var array
	 */
	public static $textFilterAttributes = [];

	public function genValidID($length = 6)
	{
		$_found = false;
		$_id = null;
		while ($_found === false) {
			$_id = Utils::shortID($length);
			try {
				$this->short_id = $_id;
				$this->insert();
				$_found = true;
			} catch (Exception $e) {
			}
		}

		return $_id;
	}

	/**
	 * Get list of fields that want to be serialized
	 *
	 * @return array
	 */
	public function fields()
	{
		if (!empty(static::$serializeFields)) {
			return static::$serializeFields;
		}

		return $this->attributes();
	}

	/**
	 * Get list of fields that want to be retrieved from database. There will be only fields that will be used
	 * in serialization (directly, or thru getters).
	 *
	 * @return array
	 */
	public static function getSelectFields()
	{
		// fields that want to be serialized, and extra fields for internal use
		return array_merge(array_values(static::$serializeFields), static::$retrieveExtraFields);
	}

	/**
	 * Parse html tags, and remove not allowed tags.
	 * Now, only <p> are allowed
	 *
	 * @param string|array|null $mix
	 * @param string $tags
	 *
	 * @return array|null|string
	 */
	public static function stripNotAllowedHtmlTags($mix, $tags = "<p>")
	{
		$newValue = null;

		if (is_string($mix)) {
			$newValue = strip_tags($mix, $tags);
		} elseif (is_array($mix)) {
			foreach ($mix as &$item) {
				$item = strip_tags($item, $tags);
			}
			$newValue = $mix;
		}

		return $newValue;
	}


	/**
	 * Compose an array to use as condition in where(), in ActiveQuery, like:
	 *
	 * $query->andFilterWhere(
	 *      ['or',
	 *          ['LIKE', 'name.en-US', "my filter"],
	 *          ['LIKE', 'name.es-ES', "my filter"],
	 *      ]
	 * );
	 *
	 * @param array|string $fieldNames
	 * @param $value
	 * @param string $operator
	 *
	 * @return array
	 */
	public static function getFilterForText($fieldNames, $value, $operator = 'LIKE')
	{
		if (!is_array($fieldNames)) {
			$fieldNames = [$fieldNames];
		}
		$nameFilter = ['or'];
		foreach ($fieldNames as $fieldName) {
			if (in_array($fieldName, static::$translatedAttributes)) {
				foreach (Lang::getAvailableLanguages() as $key => $name) {
					$field = ($fieldName . "." . $key);
					$nameFilter[] = [$operator, $field, $value];
				}
			} else {
				$nameFilter[] = [$operator, $fieldName, $value];

			}
		}

		return $nameFilter;
	}

	/**
	 * Set the current object as parent on embbed objects
	 * By default, this function makes nothing. It has to be implemented on child clases
	 *
	 * TODO: try to implement the logic here using the functions provided by the embbed library
	 *
	 */
	public function setParentOnEmbbedMappings()
	{
	}

	/**
	 * Before validate an object, set the object as parent on embbed objects
	 *
	 * @return bool
	 */
	public function beforeValidate()
	{
		return parent::beforeValidate();
	}

	/**
	 * After find an object, set the object as parent on embbed objects
	 */
	public function afterFind()
	{
		parent::afterFind();
	}

	/**
	 * Returns embedded object or list of objects.
	 *
	 * @param string $name embedded name.
	 *
	 * @return object|object[]|null embedded value.
	 */
	public function getEmbedded($name)
	{
		$embedded = parent::getEmbedded($name);
		if ($embedded) {
			if ($embedded instanceof EmbedModel && empty($embedded->getParentObject())) {
				// Embedded Item
				// Only when we get embedded object for first time, we set the reference to parent object
				$embedded->setParentObject($this);
			} elseif ($embedded instanceof \ArrayObject) {
				// Embedded List
				foreach ($embedded as $item) {
					if ($item instanceof EmbedModel && empty($item->getParentObject())) {
						// Only when we get embedded object for first time, we set the reference to parent object
						$item->setParentObject($this);
					}
				}
			}
		}

		return $embedded;
	}

	protected $subDocuments = [];

	public function subDocumentsConfig()
	{
		return [];
	}

	public function hasSubdocument($name)
	{
		return isset($this->subDocumentsConfig()[$name]);
	}

	/**
	 * @param $name
	 *
	 * @return Model|Model[]
	 * @throws Exception
	 */
	public function getSubDocument($name)
	{

		if (!isset($this->subDocuments[$name])) {

			if ($this->hasSubdocument($name)) {
				if (!$this->hasAttribute($name)) {
					throw new Exception("Property " . $name . " has defined an embedded object, but it is not defined as property in class " . get_class($this));
				}

				$config = $this->subDocumentsConfig()[$name];

				$actualValue = $this->{$name} ?: [];

				if (is_object($actualValue)) {
					if ((!$actualValue instanceof Model)) {
						throw new InvalidConfigException('Element of type [' . gettype($actualValue) . '], representing attribute '.$name.' must be an instance or descendant of "' . Model::className() . '".');
					}
					return $actualValue;
				}

				if ($config['type'] == 'list') {

					$items = [];

					foreach ($actualValue as $item) {
						$obj = new $config['class']();
						foreach ($item as $prop => $value) {
							if (in_array($prop, $obj->attributes())) {
								$obj->{$prop} = $value;
							}
						}
						$obj->setParentObject($this);
						$obj->setScenario($this->scenario);
						$items[] = $obj;
					}

					$this->subDocuments[$name] = $items;

				} else {

					$obj = new $config['class']();
					foreach ($actualValue as $prop => $value) {
						if (in_array($prop, $obj->attributes())) {
							$obj->{$prop} = $value;
						}
					}
					$obj->setParentObject($this);
					$obj->setScenario($this->scenario);

					$this->subDocuments[$name] = $obj;
				}
			}
		}

		return $this->subDocuments[$name];
	}

	protected function setSubDocument($name, $value) {
		$this->subDocuments[$name] = $value;
		$this->refreshProperyFromSubDocument($name);
	}

	public function setSubDocumentsForSerialize()
	{
		$configs = $this->subDocumentsConfig();
		foreach ($configs as $name => $config) {
			$this->setAttribute($name, $this->getSubDocument($name));
		}
	}

	protected function refreshProperyFromSubDocument($name)
	{
		$config = $this->subDocumentsConfig()[$name];
		$embedded = $this->getSubDocument($name);
		if ($config['type'] == 'list') {
			$propertyArray = [];
			foreach ($embedded as $obj) {
				$propertyArray[] = $obj->getAttributes();
			}
			$this->setAttribute($name, $propertyArray);
		} else {
			$this->setAttribute($name, $embedded->getAttributes());
		}
	}

	protected function refreshPropertiesFromSubDocuments()
	{
		$configs = $this->subDocumentsConfig();
		foreach ($configs as $name => $config) {
			$this->refreshProperyFromSubDocument($name);
		}
	}

}