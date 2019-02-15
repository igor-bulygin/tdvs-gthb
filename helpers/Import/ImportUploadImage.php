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

    public function upload($src)
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