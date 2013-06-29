<?php

class DbauthModule extends CWebModule {

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            //'tags.models.*',
            'dbauth.components.*',
        ));

        $app = yii::app();
        $app->setComponents(array(
            'authManager' => array(
                'class' => 'dbauth.components.PhpAuthManager',
            ),
            'user' => array(
                'class' => 'dbauth.components.WebUser',
                'allowAutoLogin' => true,
            ),
        ));
    }

    public function beforeControllerAction($controller, $action) {
        return true;
    }

}
