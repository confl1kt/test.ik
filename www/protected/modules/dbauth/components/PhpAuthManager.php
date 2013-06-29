<?php

    class PhpAuthManager extends CPhpAuthManager{
        public function init(){
            
            if($this->authFile===null)
            {
                $this->authFile=Yii::getPathOfAlias('dbauth.config.roles').'.php';
            }

            parent::init();

            $webuser = Yii::app()->user;
            
            if(!$webuser->isGuest)
            {
                foreach($webuser->getRoles() as $role)
                   if($role!='')
                    $this->assign($role, Yii::app()->user->id);
            }
        }
    }

?>