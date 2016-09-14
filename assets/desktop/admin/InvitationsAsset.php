<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class InvitationsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/admins.css'
	];
	public $js = [
		'js/api/invitationDataService.js',
		'js/desktop/admin/edit-invitation.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset',
	];
}
