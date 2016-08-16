<?php
namespace app\modules\api;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'priv' => [
                // you should consider using a shorter namespace here!
                'class' => 'app\modules\api\priv\Module',
            ],
        ];
    }
}