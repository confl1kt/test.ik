<?php

class User extends CActiveRecord {

    public $user;
    public $newpassword;
    public $newpassword2;
    public $checkPassword;
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'users';
    }

    public function rules() {
        return array(
            array('password, username, role, email, insert_date', 'required'),
            array('password, username, email', 'length', 'max' => 256),
            array('password, newpassword, newpassword2', 'required', 'on' => 'passwordChange'),
            array('checkPassword', 'authenticate','on'=>'passwordChange'),
            array('newpassword, newpassword2','newPassword','on'=>'passwordChange'),
            array('role, insert_date', 'length', 'max' => 10),
            array('id, password, username, role, email, insert_date', 'safe', 'on' => 'search'),
        );
    }

    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            if(!$this->validatePassword($this->checkPassword)){
                $this->addError('checkPassword', 'Неверный пароль');
                return false;
            }
                return true;
        }
    }
    public function newPassword($attribute,$params){
        if (!$this->hasErrors()) {            
            if ($this->newpassword!=$this->newpassword2){
                $this->addError('newpassword2', 'Пароли не совпадают');
                return false;
            }                
            $this->password=$this->newpassword;
            return true;
        }
        return false;
    }
    public function relations() {
        return array(
            'followers' => array(self::HAS_MANY, 'Follower', 'user_id'),
            'followersCount' => array(self::STAT, 'Follower', 'user_id'),
            'follows' => array(self::HAS_MANY, 'Follower', 'author_id'),
            'followsCount' => array(self::STAT, 'Follower', 'author_id'),
            'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
            'postsCount' => array(self::STAT, 'Post', 'author_id'),
            'Role' => array(self::BELONGS_TO, 'Role', 'role'),
            'favorites' => array(self::HAS_MANY, 'Favorite', 'author_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'password' => 'Пароль',
            'username' => 'Логин',
            'role' => 'Роль',
            'email' => 'E-mail',
            'insert_date' => 'Дата регистрации',
            'user' => 'Пользователь',
            'checkPassword'=>'Старый пароль',
            'newpassword'=>'Новый пароль',
            'newpassword2'=>'Повторите пароль',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('username', $this->user, true);
        $criteria->compare('email', $this->user, true, 'or');
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function register(RegisterForm $form) {
        if (self::checkUser($form->username, $form->email)) {
            $user = new self;
            $user->username = $form->username;
            $user->password = $form->password;
            $user->email = $form->email;
            $user->role = 1;
            $user->insert_date = time();
            if (!$user->save()) {
                $this->addErrors($user->getErrors());
                return false;
            }
            yii::app()->users->add($user->id,$user->username);
            return true;
        }
    }

    private function checkUser($login, $email) {
        if (self::model()->count('email=:email or username=:username', array(':email' => $email, ':username' => $login))) {
            $this->addError('username', 'Данный пользователь уже существует');
            return false;
        }
        return true;
    }

    public function setPassword($value) {
        return md5($value);
    }

    public function validatePassword($password) {        
        if ($this->password == $password)
            return true;
        return false;
    }        
    
    public function getButtonTitle(CController $controller) {
        
        if (yii::app()->follows->is($this->id)){            
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/unfollow', array('id' => $this->id)),
                        'label' => 'Отписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $this->id,),                        
                    ));
        }
        else
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/follow', array('id' => $this->id)),
                        'label' => 'Подписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $this->id,),                        
                    ));
    }
    
    public function getButtonTitle4profile(CController $controller) {
        if (yii::app()->follows->is($this->id)) {
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/unfollow', array('id' => $this->id)),
                        'label' => 'Отписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $this->id,),                        
                    ));
        }
        else
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/follow', array('id' => $this->id)),
                        'label' => 'Подписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $this->id,),                        
                    ));
    }
    
    public function editCache($id,$property,$data){
        if($username=yii::app()->users->get($id)){
            if($user=yii::app()->cache->get($username)){
                $user->$property+=$data;
                $user->saveCache();
            }
        }
    }
    public function saveCache(){
        yii::app()->cache->set($this->username,$this,3600);
    }
}