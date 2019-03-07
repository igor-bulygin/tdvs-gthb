<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 11/02/2019
 * Time: 14:54
 */

namespace app\helpers\Import;

use Yii;
use app\helpers\Utils;


class ImportUploadImage
{

    /**
     * @var array
     * allowed image mime types. All other will not be copied frm source store
     */
    private $allowed_types = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
    ];

    /**
     * @var string $image_prefix
     * prefix for images files downloaded from source store
     */
    private $image_prefix = 'product.photo.';

    /**
     * Height of main preview image
     * @var int
     */
    private $preview_height = 768;

    /**
     * Width of main preview image
     * @var int
     */
    private $preview_width = 614;


    private $OriginalUserAgent;

    function __construct()
    {
        /**
         * is used to get images content-type (get_headers() function in ImportUploadImage class)
         */
        $this->OriginalUserAgent = ini_get('user_agent');
        ini_set('user_agent', 'Mozilla/5.0');

    }

    function __destruct()
    {
        ini_set('user_agent', $this->OriginalUserAgent);
    }

    /**
     * @param $src - URL of image
     * @param bool $is_main - create or no PNG main image
     * @return null|array
     */
    public function upload($src, $is_main = false)
    {
        $path = Utils::join_paths(Yii::getAlias("@product"), "temp");
        $type = $this->getImageMimeType($src);
        if (!$type or !in_array($type, $this->allowed_types)) {
            return null;
        }
        $ext = Utils::getFileExtensionFromMimeType($type);
        $filename = $this->image_prefix . uniqid() . '.' . $ext;
        if (copy($src, $path . '/' . $filename)) {
            if ($is_main) {
                $filename_cropped = ($is_main) ? $this->image_prefix . uniqid() . '.png' : null;
                if ($this->makeMainImage($path, $filename, $filename_cropped)) {
                    return array(
                        'name'                  => $filename,
                        'name_cropped'          => $filename_cropped,
                        'main_product_photo'    => true
                    );
                }
                else {
                    return array('name' => $filename);
                }
            }
            else {
                return array('name' => $filename);
            }
        }
        return null;
    }

    private function getImageMimeType($src)
    {
        $headers = @get_headers($src, 1);
        if ($headers) {
            return $headers['Content-Type'];
        }
        return false;
    }

    private function makeMainImage($path, $src, $dest)
    {
        if (imagepng(imagecreatefromstring(file_get_contents($path . '/' . $src)), $path . '/'.$dest)) {
            $f = $path . '/'.$dest;
            return Utils::resizeAndCrop($f, $this->preview_width, $this->preview_height, true);
        }
        return false;
    }



}