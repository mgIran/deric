<?php

class AdminsModule extends CWebModule
{
	public function init()
    {
        $this->defaultController = 'dashboard';
        // import the module-level models and components
        $this->setImport( array(
            'admins.models.*',
            'admins.components.*',
        ) );
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
    }

    public $controllerMap = array(
        'manage' => 'application.modules.admins.controllers.AdminsManageController',
    );

	public function beforeControllerAction($controller, $action)
	{

		if(parent::beforeControllerAction($controller, $action))
		{

			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
