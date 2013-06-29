<?
    /**
     * If module DBAUTH turned off - do not return configuration
     */
    if(!ConfigInFolders::singleton()->hasModule('dbauth'))
            return array();
    else
    return array(
        /**
         * Try to update configuration
         */
        'import'=>array(
		'modules.dbauth.components.*',
                'modules.dbauth.models.*',
	),

        'components'=>array(
            'user'=>array('class'=>'modules.dbauth.components.WebUser'),
            'authManager' => array(
                    'class' => 'modules.dbauth.components.PhpAuthManager',
                    'defaultRoles' => array('guest'),
                ),
        )

    );

?>