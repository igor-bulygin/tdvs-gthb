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
use EasySlugger\Slugger;

class magentoParser
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
     * @var array
     * Array of products. Can be changed during prsing
     */
    private $lines = array();

    /**
     * @var array
     * Array of product attributes that not needed to be found in DB
     */
    private $skip_attrs = array('ts_dimensions_height', 'ts_dimensions_length', 'ts_dimensions_width'); // attributes not needed to find into DB

    /**
     * @var string
     * Path to the product images on Magento server
     */
    private $server_images_path = '/pub/media/catalog/product';

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
            while (($row = fgetcsv($handle, 4192, ",")) != false) {
//                $this->lines[] = array_map('strip_tags', $row);
                $this->lines[] = array_map(function ($item) {
                    return strip_tags($item);
                }, $row);

            }
        }
        else {
            throw new \Exception('Unable to read the input file');
        }

        fclose($handle);

        $upload = new ImportUploadImage();

        /**
         * @var array
         * get positions of columns
         */
        $cols = $this->getCols(array_shift($this->lines));

//        var_dump($this->lines);
//        die();

        // get products that have variations (field 'type' in CSV is 'configurable')
        $configurable_products = array_filter($this->lines, function ($item) use ($cols) {
            return $item[$cols['product_type']] == 'configurable';
        });

//        var_dump($configurable_products);
//        die();

        // get array of products with their variations (stored in 'children' sub-srray)
        $parsed_lines = array();
        foreach ($configurable_products as $key=>$value) {
            if (isset($value[$cols['configurable_variations']]) && strlen($value[$cols['configurable_variations']]) > 0) {
                $value['children'] = array();
                $variations = explode('|', $value[$cols['configurable_variations']]);
                foreach ($variations as $variation) {
                    $str = explode(',', $variation);
                    list($_, $sku) = explode('=', $str[0]);
                    $child = $this->searchElementInLines($cols['sku'], $sku);
                    if ($child) {
                        $value['children'][] = $child;
                    }
                }
            }
            $parsed_lines[] = $value;
            unset($this->lines[$key]); // delete line from CSV array
        }

        // if something remained - there is simple product that haven't variations. Wee add it in our array.
        if (count($this->lines) > 0) {
            $parsed_lines = array_merge($parsed_lines, $this->lines);
        }
//        var_dump($parsed_lines);
//        die();

        $server = $this->getServer();
        if (!$server) {
            $this->warnings[] = array('object' => $this->post['source-url'], 'error' => 'HOST_UNREACHABLE');
        }

        $result = array();
        foreach ($parsed_lines as $row) {
            $line = array();

            $option_name = null;
            $categories         = $this->getCategories($row[$cols['categories']]);
            $options            = array();
            $options_child      = array();
            $price_stock        = array();
            $price_stock_line   = array();
            $media = array();
            $media['photos'] = array();
            $media['description_photos'] = array();

            $line['categories'] = $categories;

            if (count($categories) > 0) {
                $line['emptyCategory'] = null;
            }
            $line['name'] = array(
                $this->lang => $row[$cols['name']]
            );
            $line['deviser_id']                 = $this->person->short_id;
            $line['description']                = array();
            $line['description'][$this->lang]   = trim($row[$cols['description']]);
            $line['product_state']              = 'product_state_draft';
            $line['warranty']                   = array('type' => 0, 'value' => null);
            $line['returns']                    = array('type' => 0, 'value' => null);
            $line['bespoke']                    = array('type' => 0, 'value' => null);
            $line['madetoorder']                = array('type' => 0, 'value' => null);
            $line['preorder']                   = array('type' => 0, 'end' => null, 'ship' => null);
            $line['weight_unit']                = 'g';
            $line['dimension_unit']             = 'cm';




            // get product options from 'additional_attributes' field
            $attrs = null;
            if (strlen($row[$cols['additional_attributes']]) > 0) {
                $attrs = $this->parseAttributes($row[$cols['additional_attributes']]);
            }

            if ($attrs) {
                $price_stock_line['options'] = array();
                foreach ($attrs as $attr) {
                    if ($attr[0] != 'size' && !in_array($attr[0], $this->skip_attrs)) {
                        $options[$attr[0]] = array(array($attr[1]));
                    }

                    if (!in_array($attr[0], $this->skip_attrs)) {
                        $price_stock_line['options'][$attr[0]] = array(array($attr[1]));
                    }
                    // get dimensions from 'additional attributes'. They are stored into 'ts_dimensions_' variables
                    else {
                        if (strpos($attr[0], 'ts_dimensions') !== false) {
                            $attr_name = str_replace('ts_dimensions_', '', $attr[0]);
                            $price_stock_line[$attr_name] = round($attr[1], 2);
                        }
                    }
                }
                // if there aren't variations - we have only 1 variation stored in the main line of product
                if (!isset($row['children']) or count($row['children']) == 0) {
                    $price_stock_line['price']      = round($row[$cols['price']], 2);
                    $price_stock_line['stock']      = round($row[$cols['qty']]);
                    $price_stock_line['sku']        = Slugger::slugify($row[$cols['sku']]);
                    $price_stock_line['available']  = true;

                    $price_stock[] = $price_stock_line;
                }
            }

            if (isset($this->post['source-url']) && strlen($this->post['source-url']) > 0) {
                // get images from 'base_image' and 'additional_images' attributes
                $images_parent = $this->parseImages($row[$cols['base_image']], $row[$cols['additional_images']]);

                // handle products variations
                $images_child = array();
                $images_child_arr = array(); // array for variations images
            }

            // if there are children - we get variations from this array
            if (isset($row['children']) && count($row['children']) > 0) {

                foreach ($row['children'] as $child) {
                    $price_stock_child = array();
                    if (strlen($child[$cols['additional_attributes']]) > 0) {
                        $attrs = $this->parseAttributes($child[$cols['additional_attributes']]);
                    }
                    if ($attrs) {
                        foreach ($attrs as $attr) {
                            if ($attr[0] != 'size' && !in_array($attr[0], $this->skip_attrs)) {
                                if (array_key_exists($attr[0], $options_child)) {
                                    if (!in_array($attr[1], $options_child[$attr[0]][0])) {
                                        array_push($options_child[$attr[0]][0], $attr[1]);
                                    }
                                }
                                else {
                                    $options_child[$attr[0]][] = array($attr[1]);
                                }
                            }
                            /**
                             * make options line for 'price_stock' array (it includes 'size')
                            **/

                            // get dimensions from 'additional attributes'. They are stored into 'ts_dimensions_' variables
                            if (in_array($attr[0], $this->skip_attrs)) {
                                if (strpos($attr[0], 'ts_dimensions') !== false) {
                                    $attr_name = str_replace('ts_dimensions_', '', $attr[0]);
                                    $price_stock_child[$attr_name] = round($attr[1], 2);
                                }
                            }
                            // get size, color, material and other options
                            else {
                                if (strtolower($attr[0]) == 'size') {
                                    $price_stock_child['options'][$attr[0]] = $attr[1];
                                }
                                else {
                                    $price_stock_child['options'][$attr[0]] = array($attr[1]);
                                }
                            }
                        }
                    }
                    $price_stock_child['price']     = round($child[$cols['price']], 2);
                    $price_stock_child['stock']     = round($child[$cols['qty']]);
                    $price_stock_child['sku']       = Slugger::slugify($child[$cols['sku']]);
                    $price_stock_child['available'] = true;

                    if (isset($this->post['source-url']) && strlen($this->post['source-url']) > 0) {
                        $images_child_arr[] = $this->parseImages($child[$cols['base_image']], $child[$cols['additional_images']]);
                    }

                    $price_stock[] = $price_stock_child;
                }


                if (isset($this->post['source-url']) && strlen($this->post['source-url']) > 0) {
                    $images_child = array_unique(array_merge(...$images_child_arr)); // flatten variations images array
                }
            }

//            $price_stock[] = $price_stock_line;

            if (isset($this->post['source-url']) && strlen($this->post['source-url']) > 0) {
                /**
                 * upload and save images of product and its variations
                 * 1. make URL for download image
                 * 2. copy image from original source
                 * 3. remove path and save only image original name
                 **/
                $images_arr = array_unique(array_merge($images_parent, $images_child));

                if ($server) {
                    $i = 0;
                    foreach ($images_arr as $image) {
                        $image_url = $server.$this->server_images_path.$image;
                        $is_main = ($i == 0) ? true : false;
                        if ($uploaded_image = $upload->upload($image_url, $is_main)) {
                            $media['photos'][] = $uploaded_image;
                            $i++;
                        }
                        else {
                            $this->warnings = $upload->getWarnings();
                        }
                    }
                }
            }

            $line['options'] = array_replace_recursive($options, $options_child);
            $line['price_stock'] = $price_stock;
            $line['media'] = $media;

            $result[] = $line;
        }
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
                case 'sku':
                    $result['sku'] = $key;
                    break;
                case 'product_type':
                    $result['product_type'] = $key;
                    break;
                case 'categories':
                    $result['categories'] = $key;
                    break;
                case 'name':
                    $result['name'] = $key;
                    break;
                case 'description':
                    $result['description'] = $key;
                    break;
                case 'short_description':
                    $result['short_description'] = $key;
                    break;
                case 'weight':
                    $result['weight'] = $key;
                    break;
                case 'price':
                    $result['price'] = $key;
                    break;
                case 'base_image':
                    $result['base_image'] = $key;
                    break;
                case 'additional_attributes':
                    $result['additional_attributes'] = $key;
                    break;
                case 'qty':
                    $result['qty'] = $key;
                    break;
                case 'additional_images':
                    $result['additional_images'] = $key;
                    break;
                case 'configurable_variations':
                    $result['configurable_variations'] = $key;
                    break;
                default:
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $key - search field in sub array
     * @param $value - searched value
     * @return mixed|null - return found array of values
     * Also function removes found array from global $lines array
     */
    private function searchElementInLines($key, $value){
        $array = null;
        foreach($this->lines as $subKey => $subArray){
            if($subArray[$key] == $value){
                $array = $subArray;
                unset($this->lines[$subKey]);
            }
        }
        return $array;
    }


    /**
     * @param $categories_str - string from CSV
     * @return array
     * Returns array of categories IDs retrieved from DB
     */
    private function getCategories($categories_str = '')
    {
        $res = array();
        if (strlen($categories_str) > 0) {
            $cats_arr = explode(',', $categories_str);
            if ($cats_arr && count($cats_arr) > 0) {
                foreach ($cats_arr as $value) {
                    $cats_names = explode('/', $value);
                    $cat_name = end($cats_names);
                    $res[] = ImportUtil::getCategoryByName($cat_name, $this->lang);
                }
            }
        }
        return array_unique(array_merge(...$res));
    }

    private function parseAttributes($str = '') {
        $res = array();
        if (strlen($str) > 0) {
            $attrs_arr = explode(',', $str);
            if ($attrs_arr && count($attrs_arr) > 0) {
                foreach ($attrs_arr as $value) {
                    list($attr_name, $attr_value) = explode('=', $value);
                    if (strtolower($attr_name) == 'size' or in_array(strtolower($attr_name), $this->skip_attrs)) {
                        $res[] = array($attr_name, $attr_value);
                    }
                    else {
                        $res[] = ImportUtil::getOptionIdAndValue($attr_name, $attr_value, $this->lang);
                    }
                }
            }
        }
        return $res;
    }

    private function parseImages($base_image, $additional_images)
    {
        $res = array();
        if (isset($base_image) && strlen($base_image) > 0) {
            $res[] = $base_image;
        }
        if (isset($additional_images) && strlen($additional_images) > 0) {
            $img_arr =explode(',', $additional_images);
            foreach ($img_arr as $img) {
                $res[] = $img;
            }
        }
        return $res;
    }

    /**
     * Function adds 'http' or 'https' to shop URL and checks if it is working
     * @return null|string
     */
    private function getServer()
    {
        $domain = rtrim($this->post['source-url'], '/');
        if (substr($domain, 0, 7) == "http://" or substr($domain, 0, 8) == "https://") {
            return $domain;
        }
        else {
            if($fp = @fsockopen($domain,443,$errCode,$errStr,1)){
                $url = "https://" . $domain;
            } else {
                $url = "http://" . $domain;
            }
            if ($fp) fclose($fp);

            if (ImportUtil::urlTest($url)) {
                return $url;
            }
        }
        return null;
    }



}