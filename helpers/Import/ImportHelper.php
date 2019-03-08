<?php
namespace app\helpers\Import;

use app\models\Product;
use yii\mongodb\ActiveQuery;
use app\models\Person;
use Yii;

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

    /**
     * @var string
     * temporary name of uploaded CSV file
     */
    private $csv_file;



    public function __construct($csv, $post, $person)
    {
        $this->csv_file = ($csv && is_readable($csv->tempName)) ? $csv : null;

        if ($post && $this->csv_file) {
            $classFile = $post['source'] . 'Parser';
            if (file_exists(__DIR__ . '/' . $classFile . '.php')) {
                $className = 'app\\helpers\\Import\\' . $classFile;
                $this->parser = new $className($this->csv_file->tempName, $post, $person);
            }
            $this->lang = $post['lang'];
            $this->person = $person;
        }
    }

    public function import()
    {
        /**
         * get parsed data from CSV and convert objects into arrays
         */
        $parsed_data = $this->parser->parse();


        return array(
            'products' => $this->checkExistingProducts($parsed_data['products']),
            'warnings' => $parsed_data['warnings']
        );
    }

    public function checkForm()
    {
        $csv_mimetypes = array(
            'text/csv',
            'text/tsv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        );

        $errors = array();

        if (!$this->csv_file) {
            $errors[] = Yii::t('app/import', 'FILE_NOT_UPLOADED_CORRECTLY');
            return $errors;
        }
        if (!in_array($this->csv_file->type, $csv_mimetypes) or pathinfo($this->csv_file->name, PATHINFO_EXTENSION) != 'csv') {
            $errors[] = Yii::t('app/import', 'FILE_TYPE_NOT_CSV');
        }

        return $errors;
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
