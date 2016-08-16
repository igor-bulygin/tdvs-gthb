<?php
namespace app\modules\api;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'pub' => [
                'class' => 'app\modules\api\pub\Module',
            ],
            'priv' => [
                'class' => 'app\modules\api\priv\Module',
            ],
            'admin' => [
                'class' => 'app\modules\api\admin\Module',
            ],
        ];
    }
}