<?php
namespace app\modules\api\admin;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'v1' => [
                // you should consider using a shorter namespace here!
                'class' => 'app\modules\api\admin\v1\Module',
            ],
        ];
    }
}