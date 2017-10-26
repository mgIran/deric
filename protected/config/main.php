<?php
Yii::setPathOfAlias('chartjs', dirname(__FILE__).'/../extensions/yii-chartjs');
Yii::setPathOfAlias('ApkParser', dirname(__FILE__).'/../vendor/ApkParser');
return array(
    //'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
    //'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'اپلیکیشن فارسی',
    'timeZone' => 'Asia/Tehran',
    'theme' => 'abound',
    'language' => 'fa_ir',
		// preloading 'log' component
	'preload'=>array('log','userCounter','chartjs'),

	// autoloading model and component classes
	'import'=>array(
        'application.vendor.*',
        'application.models.*',
		'application.components.*',
		'application.modules.setting.models.*',
		'application.modules.users.models.*',
		'application.modules.users.components.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
        'admins',
        'users',
        'setting',
        'pages',
        'developers',
        'manageApps',
		'tickets',
		'advertises',
		'comments'=>array(
			//you may override default config for all connecting models
			'defaultModelConfig' => array(
				//only registered users can post comments
				'registeredOnly' => true,
				'useCaptcha' => true,
				//allow comment tree
				'allowSubcommenting' => true,
				//display comments after moderation
				'premoderate' => true,
				//action for postig comment
				'postCommentAction' => '/comments/comment/postComment',
				//super user condition(display comment list in admin view and automoderate comments)
				'isSuperuser'=>'',
				//order direction for comments
				'orderComments'=>'DESC',
				'showEmail' => false
			),
			//the models for commenting
			'commentableModels'=>array(
				//model with individual settings
				'Apps'=>array(
					'registeredOnly'=>true,
					'useCaptcha'=> false,
					'premoderate' => true,
					'allowSubcommenting'=>true,
					'isSuperuser'=>'!Yii::app()->user->isGuest && (Yii::app()->user->type == \'admin\' || Yii::app()->user->roles == "developer")',
					'orderComments'=>'DESC',
					//config for create link to view model page(page with comments)
					'pageUrl'=>array(
						'route'=>'apps/view/',
						'data'=>array('id'=>'id')
					),
				),
			),
			'userConfig'=>array(
				'class'=>'Users',
				'nameProperty'=>'userDetails.fa_name',
				'emailProperty'=>'email',
			),
		)
	),

	// application components
	'components'=>array(
		'JWT' => array(
			'class' => 'ext.jwt.JWT',
			'key' => base64_encode(md5('Rahbod-Market-Application-Farsi-1396')),
		),
		'JWS' => array(
			'class' => 'ext.jwt.JWT',
			'key' => base64_encode(sha1('Rahbod-Market-Application-Farsi-1396')),
		),
		'yexcel' => array(
			'class' => 'ext.yexcel.Yexcel'
		),
		'session' => array(
			'class' => 'YmDbHttpSession',
			'autoStart' => false,
			'connectionID' => 'db',
			'sessionTableName' => 'ym_sessions',
			'timeout' => 1800
		),
		'mellat' => array(
			'class'=> 'ext.mellatPayment.MellatPayment',
			'terminalId' => '',
			'userName' => '',
			'userPassword' => ''
		),
		'zarinpal' => array(
			'class'=> 'ZarinPal',
			'merchant_id' => ''
		),
        'userCounter' => array(
            'class' => 'application.components.UserCounter',
            'tableUsers' => 'ym_counter_users',
            'tableSave' => 'ym_counter_save',
            'autoInstallTables' => true,
            'onlineTime' => 5, // min
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'class' => 'WebUser',
			'loginUrl'=>array('/login'),
		),
        'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
        ),
		'chartjs' => array('class' => 'chartjs.components.ChartJs'),
		// uncomment the following to enable URLs in path-format
        // @todo change rules in projects
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
            'appendParams'=>true,
			'rules'=>array(
				'<action:(logout|login|register|dashboard)>' => 'users/public/<action>',
                'android' => 'site/index/platform/android',
                'ios' => 'site/index/platform/ios',
                'windowsphone' => 'site/index/platform/windowsphone',
				'apps/<id:\d+>'=>'apps/view',
				'api/<action:\w+>'=>'api/<action>',
				'documents/<id:\d+>/<title>'=>'pages/manage/view',
				'<module:\w+>/<id:\d+>'=>'<module>/manage/view',
                '<module:\w+>/<controller:\w+>'=>'<module>/<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>/<title:(.*)>'=>'<controller>/<action>',
                '<controller:\w+>/<id:\d+>/<title:(.*)>'=>'<controller>/view',
                '<module:\w+>/<controller:\w+>/<id:\d+>/<title:\w+>'=>'<module>/<controller>/view',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
            ),
		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'application.*',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'application.*',
                    'showInFireBug' => true,
                ),
			),
		),
//        'clientScript'=>array(
//            'class'=>'ext.minScript.components.ExtMinScript',
//            'coreScriptPosition' => CClientScript::POS_HEAD,
//            'defaultScriptFilePosition' => CClientScript::POS_END,
//        ),
    ),
    'controllerMap' => array(
        'min' => array(
            'class' =>'ext.minScript.controllers.ExtMinScriptController',
        )
    ),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// @todo change webmail of emails
		'adminEmail'=>'webmaster@rahbod.com',
        'noReplyEmail' => 'no-reply@rahbod.com',
		'SMTP' => array(
//            'Host' => 'mail.hyperapps.ir',
//            'Secure' => 'ssl',
//            'Port' => 465,
//            'Username' => 'no-reply@hyperapps.ir',
//            'Password' => '7cZKn*CWSrg87cZKn*CWSrg8',
        ),
        'mailTheme'=>
            '<h2 style="margin-bottom:0;box-sizing:border-box;display: block;width: 100%;background-color: #77c159;line-height:60px;color:#fff;font-size: 24px;text-align: right;padding-right: 50px">رهبد مارکت<span style="font-size: 14px;color:#f0f0f0"> - مرجع انواع نرم افزار تلفن های هوشمند</span></h2>
             <div style="display: inline-block;width: 100%;font-family:tahoma;line-height: 28px;">
                <div style="direction:rtl;display:block;overflow:hidden;border:1px solid #efefef;text-align: center;padding:15px;">{MessageBody}</div>
             </div>
             <div style="font-size: 8pt;color: #bbb;text-align: right;font-family: tahoma;padding: 15px;">
                <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/site/about">درباره</a> | <a href="'.((strpos($_SERVER['SERVER_PROTOCOL'], 'https'))?'https://':'http://').$_SERVER['HTTP_HOST'].'/site/help">راهنما</a>
                <span style="float: left;"> همهٔ حقوق برای اپلیکیشن فارسی محفوظ است. ©‏ {CurrentYear} </span>
             </div>',
	),
);
