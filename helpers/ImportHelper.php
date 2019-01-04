<?php
namespace app\helpers;

use app\models\Category;
use app\models\Tag;
use Yii;
use app\helpers\Utils;

class ImportHelper {

    private $person; // deviser data
    private $post; // POST data from form
    private $csv; // CSV file uploaded
    private $allowed_types = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
    ];


    public function __construct($csv, $post, $person)
    {
        $this->person = $person;
        $this->csv = $csv;
        $this->post = $post;
        $this->image_prefix = 'product.photo.';
    }

    public function import()
    {
        switch ($this->post['source']) {
            case 'shopify':
                return $this->shopifyCSVToObjects();
                break;
            default:
                return null;
        }
    }

    /**
     * @param $csv - path to CSV file (temporary path to upload file, we don't save it)
     * @throws \Exception
     */
	private function shopifyCSVToObjects()
    {
        if (!file_exists($this->csv) || !is_readable($this->csv)) {
            throw new \Exception('File not uploaded correctly');
        }

        $OriginalUserAgent = ini_get('user_agent');
        ini_set('user_agent', 'Mozilla/5.0');


        if (($handle = fopen($this->csv, 'r')) !== false) {
            $lang = $this->post['lang'];
            $i = 0;
            $result = array();
            while (($row = fgetcsv($handle, 1024, ",")) != false) {
                $i++;
                if ($i == 1) {
                    continue;
                }
                $option_name = null;
                if (!array_key_exists($row[0], $result)) {
                    $categories         = $this->getCategoryByName($row[4], $lang); // retrieve categories short_id by 'Type' field in CSV
                    $options            = new \stdClass();
                    $price_stock        = array();
                    if (isset($row[8]) && strlen($row[8]) > 0 && $row[8] !== 'Default title') {
                        $price_stock_line   = new \stdClass();
                        $price_stock_line->options = new \stdClass();
                    }
                    else {
                        $price_stock_line = null;
                    }
                    $media = new \stdClass();
                    $media->photos = array();
                    $media->description_photos = array();
                    $pos = array(); // array where we store what option in which position is.
                    // retrieve options and price stock from fields 7 - 12
                    for ($j = 7; $j < 12; $j++) {
                        if ($row[$j] !== 0 && strlen($row[$j]) > 0 && $row[($j + 1)] !== 0 && strlen($row[($j + 1)]) > 0) {
                            if (strtolower($row[$j]) == 'size') {
                                $option_name = 'size';
                            }
                            else {
                                $option_name = $this->getOptionShortIdByName($row[$j], $lang);
                            }
                            $option_data = strtolower($row[($j + 1)]);
                            if ($option_name) {
                                if (strtolower($option_name) !== 'size') {
                                    $options->$option_name = array(
                                        array($option_data)
                                    );
                                }
                                if ($price_stock_line) {
                                    $price_stock_line->options->$option_name = array($option_data);
                                }
                                $pos[$j] = $option_name; // write option name into $pos array for next iterations
                            }
                        }
                    }
                    if ($price_stock_line) {
                        $price_stock_line->price = floatval($row[19]);
                        $price_stock_line->stock = intval($row[16]);
                        $price_stock_line->weight = $row[14];
                        $price_stock[] = $price_stock_line;
                    }
                    // images upload
                    if (isset($row[24]) && strlen($row[24]) > 0) {
                        $image = $this->uploadImage($row[24]);
                        if ($image) {
                            $media->photos[] = array('name' => $image);
                            // $media->description_photos[] = '';
                        }
                    }
                    $result[$row[0]] = new \stdClass();
                    $result[$row[0]]->emptyCategory = ((count($categories) > 0) ? false : true);
                    $result[$row[0]]->deviser_id            = $this->person->short_id;
                    $result[$row[0]]->name                  = new \stdClass();
                    $result[$row[0]]->name->$lang           = $row[1];
                    $result[$row[0]]->description           = new \stdClass();
                    $result[$row[0]]->description->$lang    = $row[2];
                    $result[$row[0]]->product_state         = 'product_state_draft';
//                        'weight_unit'   => $row[44],
                    $result[$row[0]]->weight_unit   = 'g';
                    $result[$row[0]]->media         = $media;
                    $result[$row[0]]->categories    = $categories;
                    $result[$row[0]]->options       = $options;
                    $result[$row[0]]->price_stock   = $price_stock;
                    $result[$row[0]]->avalaible     = 1;
                }
                else {
                    if (isset($row[8]) && strlen($row[8]) > 0 && $row[8] !== 'Default title') {
                        $price_stock_line   = new \stdClass();
                        $price_stock_line->options = new \stdClass();
                    }
                    else {
                        $price_stock_line = null;
                    }
                    for ($j = 7; $j < 12; $j++) {
                        if (isset($pos[$j]) && $row[($j + 1)] !== null && strlen($row[($j + 1)]) > 0) {
                            $option_name = $pos[$j];
                            $option_data = strtolower($row[($j + 1)]);
                            if ($price_stock_line) {
                                if (strtolower($option_name) == 'size') {
                                    $price_stock_line->options->$option_name = $option_data;
                                }
                                else {
                                    $price_stock_line->options->$option_name = array($option_data);
                                }
                            }
                            if (strtolower($option_name) != 'size' && !in_array(array($option_data), $result[$row[0]]->options->$option_name)) {
                                array_push($result[$row[0]]->options->$option_name, array($option_data));
                            }
                        }
                    }
                    if ($price_stock_line) {
                        $price_stock_line->price        = floatval($row[19]);
                        $price_stock_line->stock        = intval($row[16]);
                        $price_stock_line->weight       = $row[14];
                        $result[$row[0]]->price_stock[] = $price_stock_line;
                    }
                    if (isset($row[24]) && strlen($row[24]) > 0) {
                        $image = $this->uploadImage($row[24]);
                        if ($image) {
                            $media->photos[] = array('name' => $image);
                            // $media->description_photos[] = '';
                        }
                    }
                    $result[$row[0]]->avalaible     = 1;
                }
//                var_dump($row);
            }
            fclose($handle);
        }

        ini_set('user_agent', $OriginalUserAgent);                            

        return $result;
    }

    private function getCategoryByName($name, $lang) {
        $res = array();
        $cats = Category::find()->where(
            ['like', "name.".$lang, $name])->select(['short_id'])->asArray()->all();
        foreach ($cats as $v) {
            $res[] = $v['short_id'];
        }
        return $res;
    }

    private function getOptionShortIdByName($name, $lang) {
        $tags = Tag::find()->where(
            ['name.'.$lang => $name])->select(['short_id'])->asArray()->one();
        return ($tags) ? $tags['short_id'] : null;
    }

    private function uploadImage($src)
    {
        $path = Utils::join_paths(Yii::getAlias("@product"), "temp");
        $type = $this->getImageMimeType($src);
        if (!in_array($type, $this->allowed_types)) {
            return null;
        }
        $ext = Utils::getFileExtensionFromMimeType($type);
        $filename = $this->image_prefix . uniqid() . '.' . $ext;
        if (copy($src, $path . '/' . $filename)) {
            return $filename;
        }
        return null;
    }

    private function getImageMimeType($src)
    {
        $headers = get_headers($src, 1);
        return $headers['Content-Type'];
    }


}
