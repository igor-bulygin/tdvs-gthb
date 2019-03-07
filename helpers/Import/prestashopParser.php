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

class prestashopParser
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



    public function __construct($csv, $post, $person)
    {
        $this->csv = $csv;
        $this->post = $post;
        $this->person = $person;
        $this->lang = $this->post['lang'];
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
            while (($row = fgetcsv($handle, 1024, ";")) != false) {
                $lines[] = array_map(function ($item) {
                    return strip_tags($item, '<p><br>');
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
            $categories         = ImportUtil::getCategoryByName($row[$cols['category']], $this->lang); // retrieve categories short_id by 'Type' field in CSV
            $options            = array();
            $price_stock        = array();
            $price_stock_line   = array();
            $media = array();
            $media['photos'] = array();
            $media['description_photos'] = array();
            $price_stock_line['price']    = floatval($row[$cols['price']]);
            $price_stock_line['stock']    = intval($row[$cols['qty']]);
            $price_stock_line['sku']      = Slugger::slugify($row[$cols['title']]);
            $price_stock_line['available'] = true;
            $price_stock[] = $price_stock_line;
            // images upload
            if (isset($row[$cols['image']]) && strlen($row[$cols['image']]) > 0) {
                $image = $upload->upload($row[$cols['image']], true);
                if ($image) {
                    $media['photos'][] = $image;
                    // $media->description_photos[] = '';
                }
            }
            $line = array();
            $line['emptyCategory'] = ((count($categories) > 0) ? false : true);
            $line['deviser_id']                = $this->person->short_id;
            $line['name']                      = array();
            $line['name'][$this->lang]         = trim($row[$cols['title']]);
            $line['product_state']             = 'product_state_draft';
//                        'weight_unit'   => $row[44],
            $line['media']          = $media;
            $line['categories']     = $categories;
            $line['options']        = $options;
            $line['price_stock']    = $price_stock;
            $line['avalaible']      = 1;
            $line['warranty']       = array('type' => 0, 'value' => null);
            $line['returns']        = array('type' => 0, 'value' => null);
            $line['bespoke']        = array('type' => 0, 'value' => null);
            $line['madetoorder']    = array('type' => 0, 'value' => null);
            $line['preorder']       = array('type' => 0, 'end' => null, 'ship' => null);
            $line['weight_unit']                = 'g';
            $line['dimension_unit']             = 'cm';


            $result[] = $line;
        }
        fclose($handle);

        return $result;
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
                case 'Image':
                    $result['image'] = $key;
                    break;
                case 'Name':
                    $result['title'] = $key;
                    break;
                case 'Category':
                    $result['category'] = $key;
                    break;
                case 'Price (tax excl.)':
                    $result['price'] = $key;
                    break;
                case 'Quantity':
                    $result['qty'] = $key;
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