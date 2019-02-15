<?php
namespace app\helpers\Import;

use app\models\Product;
use yii\mongodb\ActiveQuery;
use app\models\Person;

class ImportHelper {


    /**
     * @var string $parser
     * link to class to parse CSV depending on its source
     */
    private $parser;

    /**
     * @var $lang string
     * Language of source CSV
     */
    private $lang;

    /**
     * @var Person $person
     * Information about deviser
     */
    private $person;



    public function __construct($csv, $post, $person)
    {
        $classFile = $post['source'].'Parser';
        if (file_exists(__DIR__.'/'.$classFile.'.php')) {
            $className = 'app\\helpers\\Import\\'.$classFile;
            $this->parser = new $className($csv, $post, $person);
        }
        $this->lang = $post['lang'];
        $this->person = $person;
    }

    public function import()
    {
        /**
         * get parsed data from CSV and convert objects into arrays
         */
        $parsed_data = $this->parser->parse();


        return $this->checkExistingProducts($parsed_data);
    }

    private function checkExistingProducts($data)
    {
        $res = array();
        $query = new ActiveQuery(Product::class);
        foreach ($data as $product) {
            if ($exist_id = $this->checkProductExistsBySKU($product, $query)) {
                $res[] = array('mode' => 'update', 'product_id' => $exist_id) + $this->mergeData($exist_id, $product);
            }
            elseif ($exist_id = $this->checkProductExistsByTitle($product, $query)) {
                $res[] = array('mode' => 'update', 'product_id' => $exist_id) + $this->mergeData($exist_id, $product);
            }
            else {
                $res[] = array('mode' => 'add', 'product_id' => null) + $product;
            }
        }
        return $res;
    }

    /**
     * @param $product - Product array from parsed CSV file
     * @param $query - link to ActiveQuery instance
     * @return mixed|null
     * Function checks if exists product with SKU that is imported from CSV file
     */
    private function checkProductExistsBySKU($product, $query)
    {
        $exists = null;
        $skus = array();

        foreach ($product['price_stock'] as $stock) {
            if (isset($stock['sku']) && strlen($stock['sku']) > 0) {
                $skus[] = $stock['sku'];
            }
        }
        if (count($skus) > 0) {
            $exists = $query->select(['short_id'])->where(
                ['price_stock' =>
                    ['$elemMatch' =>
                        ['sku' =>
                            ['$in' => $skus]
                        ]
                    ]
                ]

            )->andWhere(
                    ['deviser_id' => $this->person->short_id]
            )->one();
        }

        if ($exists) {
            return $exists->short_id;
        }
        return null;
    }

    /**
     * @param $product - Product array from parsed CSV file
     * @param $query - link to ActiveQuery instance
     * @return mixed|null
     * Function checks if exists product with SKU that is imported from CSV file
     */
    private function checkProductExistsByTitle($product, $query)
    {
        $exists = null;
        $exists = $query->select(['short_id'])->where(
            ['name.'.$this->lang => $product['name'][$this->lang]]
        )->andWhere(
            ['deviser_id' => $this->person->short_id]
        )->one();

        if ($exists) {
            return $exists->short_id;
        }
        return null;
    }

    private function mergeData($exist_id, $data) {
        $product_data = Product::findOneAsArray($exist_id);

//        print_r($data);

        return array_replace_recursive($product_data, $data);
    }

}
