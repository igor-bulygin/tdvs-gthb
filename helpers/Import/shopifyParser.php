<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 11/02/2019
 * Time: 14:34
 */

namespace app\helpers\Import;

use Yii;
use app\helpers\Utils;
use app\helpers\Import\ImportUtil;

class shopifyParser
{

    /**
     * @var $post
     * $_POST data from form
     */
    private $post; // POST data from form

    /**
     * @var $csv
     * pat to uploaded CSV file
     */
    private $csv;

    /**
     * @var $person
     * Person object
     * Information about deviser
     */
    private $person;

    /**
     * @var $lang string
     * Language of source CSV
     * Information about deviser
     */
    private $lang;

    /**
     * @var int
     * Number of possible options of product
     */
    private $options_num = 3;

    /**
     * Array of warnings about problems during import. Allows to show them to user
     * @var array
     */
    private $warnings = array();





    public function __construct($csv, $post, $person)
    {
        $this->csv = $csv;
        $this->post = $post;
        $this->person = $person;
        $this->lang = (isset($this->post['lang']) && strlen($this->post['lang']) > 0) ? $this->post['lang'] : 'en-US';
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function parse()
    {
        if (!file_exists($this->csv) || !is_readable($this->csv)) {
            throw new \Exception('File not uploaded correctly');
        }

//        $encoding = mb_detect_encoding(file_get_contents($this->csv), mb_detect_order(), true);

        if (($handle = fopen($this->csv, 'r')) !== false) {
            $lines = array();
            while (($row = fgetcsv($handle, 1024, ",")) != false) {
                $lines[] = array_map(function ($item) {
                    return strip_tags($item);
                }, $row);
            }
        }
        else {
            throw new \Exception('Unable to read the input file');
        }

        $upload = new ImportUploadImage();

        /**
         * @var array
         * get positions of columns
         */
        $cols = $this->getCols(array_shift($lines));

//        var_dump($cols);
//        die();

        $result = array();
        foreach ($lines as $row) {
            $option_name = null;
            if (!array_key_exists($row[$cols['handle']], $result)) {
                $categories         = ImportUtil::getCategoryByName($row[$cols['category']], $this->lang); // retrieve categories short_id by 'Type' field in CSV
                $options            = array();
                $price_stock        = array();
                $tags               = array();
                if (isset($row[$cols['opt_value1']]) && strlen($row[$cols['opt_value1']]) > 0 && $row[$cols['opt_value1']] !== 'Default title') {
                    $price_stock_line   = array();
                    $price_stock_line['options'] = array();
                }
                else {
                    $price_stock_line = null;
                }
                if (isset($row[$cols['tags']]) && strlen($row[$cols['tags']]) > 0) {
                    $tags[$this->lang] = explode(', ', $row[$cols['tags']]);
                }
                else {
                    $tags = null;
                }

                $media = array();
                $media['photos'] = array();
                $media['description_photos'] = array();
                $pos = array(); // array where we store what option in which position is.
                // retrieve options and price stock from fields 7 - 12
                for ($j = 1; $j <= $this->options_num; $j++) {
                    if ($row[$cols['opt_name'.$j]] !== 0 && strlen($row[$cols['opt_name'.$j]]) > 0 && $row[$cols['opt_value'.$j]] !== 0 && strlen($row[$cols['opt_value'.$j]]) > 0) {
                        if (strtolower($row[$cols['opt_name'.$j]]) == 'size') {
                            $option_name = 'size';
                            $option_data = $row[$cols['opt_value'.$j]];
                        }
                        else {
                            $option_arr = ImportUtil::getOptionIdAndValue($row[$cols['opt_name'.$j]], $row[$cols['opt_value'.$j]], $this->lang);

                            if ($option_arr) {
                                $option_name    = $option_arr[0];
                                $option_data    = $option_arr[1];
                            }
                            else {
                                $option_name = null;
                            }
                        }
                        if ($option_name && $option_data) {
                            if (strtolower($option_name) !== 'size') {
                                $options[$option_name] = array(
                                    array($option_data)
                                );
                            }
                            if ($price_stock_line) {
                                if (strtolower($option_name) == 'size') {
                                    $price_stock_line['options'][$option_name] = $option_data;
                                }
                                else {
                                    $price_stock_line['options'][$option_name] = array($option_data);
                                }
                            }
                            $pos[$j] = $option_name; // write option name into $pos array for next iterations
                        }
                    }
                }
                if ($price_stock_line) {
                    $price_stock_line['price']      = floatval($row[$cols['price']]);
                    $price_stock_line['stock']      = intval($row[$cols['qty']]);
                    $price_stock_line['weight']     = $row[$cols['grams']];
                    $price_stock_line['sku']        = $row[$cols['sku']];
                    $price_stock_line['available']  = true;
                    $price_stock[] = $price_stock_line;
                }
                // images upload
                if (isset($row[$cols['image']]) && strlen($row[$cols['image']]) > 0) {
                    $image = $upload->upload($row[$cols['image']], true);
                    if ($image) {
                        $media['photos'][] = $image;
                        // $media->description_photos[] = '';
                    }
                }
                $result[$row[$cols['handle']]] = array();
                $result[$row[$cols['handle']]]['emptyCategory'] = ((count($categories) > 0) ? false : true);
                $result[$row[$cols['handle']]]['deviser_id']                = $this->person->short_id;
                $result[$row[$cols['handle']]]['name']                      = array();
                $result[$row[$cols['handle']]]['name'][$this->lang]         = trim($row[$cols['title']]);
                $result[$row[$cols['handle']]]['description']               = array();
                $result[$row[$cols['handle']]]['description'][$this->lang]  = nl2br(trim($row[$cols['description']]));
                $result[$row[$cols['handle']]]['product_state']             = 'product_state_draft';
                $result[$row[$cols['handle']]]['weight_unit']   = 'g';
                $result[$row[$cols['handle']]]['dimension_unit']   = 'cm';
                $result[$row[$cols['handle']]]['media']         = $media;
                $result[$row[$cols['handle']]]['categories']    = $categories;
                $result[$row[$cols['handle']]]['options']       = $options;
                $result[$row[$cols['handle']]]['price_stock']   = $price_stock;
                if ($tags) {
                    $result[$row[$cols['handle']]]['tags'] = $tags;
                }
                $result[$row[$cols['handle']]]['warranty']      = array('type' => 0, 'value' => null);
                $result[$row[$cols['handle']]]['returns']       = array('type' => 0, 'value' => null);
                $result[$row[$cols['handle']]]['bespoke']       = array('type' => 0, 'value' => null);
                $result[$row[$cols['handle']]]['madetoorder']   = array('type' => 0, 'value' => null);
                $result[$row[$cols['handle']]]['preorder']      = array('type' => 0, 'end' => null, 'ship' => null);

            }
            else {
                if (isset($row[$cols['opt_value1']]) && strlen($row[$cols['opt_value1']]) > 0 && $row[$cols['opt_value1']] !== 'Default title') {
                    $price_stock_line   = array();
                    $price_stock_line['options'] = array();
                }
                else {
                    $price_stock_line = null;
                }
                for ($j = 1; $j <= $this->options_num; $j++) {
                    if (isset($pos[$j]) && $row[$cols['opt_value'.$j]] !== null && strlen($row[$cols['opt_value'.$j]]) > 0) {
                        $option_name = $pos[$j];
                        if (strtolower($option_name) == 'size') {
                            $option_data = $row[$cols['opt_value'.$j]];
                        }
                        else {
                            $option_data = ImportUtil::getOptionValue($option_name, $row[$cols['opt_value' . $j]]);
                        }
                        if ($price_stock_line && $option_data) {
                            if (strtolower($option_name) == 'size') {
                                $price_stock_line['options'][$option_name] = $option_data;
                            }
                            else {
                                $price_stock_line['options'][$option_name] = array($option_data);
                            }
                        }
                        if (strtolower($option_name) != 'size' && !in_array(array($option_data), $result[$row[$cols['handle']]]['options'][$option_name])) {
                            array_push($result[$row[$cols['handle']]]['options'][$option_name], array($option_data));
                        }
                    }
                }
                if ($price_stock_line) {
                    $price_stock_line['price']      = floatval($row[$cols['price']]);
                    $price_stock_line['stock']      = intval($row[$cols['qty']]);
                    $price_stock_line['weight']     = $row[$cols['grams']];
                    $price_stock_line['sku']        = $row[$cols['sku']];
                    $price_stock_line['available']  = true;

                    $result[$row[$cols['handle']]]['price_stock'][] = $price_stock_line;
                }
                if (isset($row[$cols['image']]) && strlen($row[$cols['image']]) > 0) {
                    $image = $upload->upload($row[$cols['image']], false);
                    if ($image) {
//                        $media['photos'][] = array('name' => $image);
                        // $media->description_photos[] = '';
                        $result[$row[$cols['handle']]]['media']['photos'][] = $image;
                    }
                }

            }
//            $sizechart = ImportUtil::makeSizeChart($result[$row[$cols['handle']]]['price_stock']);
//            var_dump($row);
        }
        fclose($handle);

//        return $result;
        return array(
            'products' => $result,
            'warnings' => $this->warnings
        );

    }

    /**
     * @param array $titles_arr
     * @return array
     * Iterates over array of CSV columns titles and returns their positions
     */
    private function getCols($titles_arr)
    {
        $result = array();
        foreach ($titles_arr as $key => $value) {
            switch ($value) {
                case 'Handle':
                    $result['handle'] = $key;
                    break;
                case 'Title':
                    $result['title'] = $key;
                    break;
                case 'Body (HTML)':
                    $result['description'] = $key;
                    break;
                case 'Type':
                    $result['category'] = $key;
                    break;
                case 'Tags':
                    $result['tags'] = $key;
                    break;
                case 'Option1 Name':
                    $result['opt_name1'] = $key;
                    break;
                case 'Option1 Value':
                    $result['opt_value1'] = $key;
                    break;
                case 'Option2 Name':
                    $result['opt_name2'] = $key;
                    break;
                case 'Option2 Value':
                    $result['opt_value2'] = $key;
                    break;
                case 'Option3 Name':
                    $result['opt_name3'] = $key;
                    break;
                case 'Option3 Value':
                    $result['opt_value3'] = $key;
                    break;
                case 'Variant SKU':
                    $result['sku'] = $key;
                    break;
                case 'Variant Grams':
                    $result['grams'] = $key;
                    break;
                case 'Variant Inventory Qty':
                    $result['qty'] = $key;
                    break;
                case 'Variant Price':
                    $result['price'] = $key;
                    break;
                case 'Image Src':
                    $result['image'] = $key;
                    break;
                case 'Variant Weight Unit':
                    $result['weight_unit'] = $key;
                    break;
                default:
                    break;
            }
        }
        return $result;
    }

    private function convertToUTF8($content) {
        $content = htmlentities($content, ENT_SUBSTITUTE);
        return $content;

        if(!mb_check_encoding($content, 'UTF-8')
            OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {

            $content = mb_convert_encoding($content, 'UTF-8');

            if (mb_check_encoding($content, 'UTF-8')) {
                 var_dump('Converted to UTF-8 '.$content);
            } else {
                 var_dump('Could not converted to UTF-8 '.$content);
            }
        }
        else {
            $content = htmlentities($content, ENT_SUBSTITUTE);
        }
        return $content;
    }

}