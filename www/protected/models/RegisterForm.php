<?php

class RegisterForm extends CFormModel {

    public $username;
    public $password;
    public $email;

    public function rules() {

        return array(
            array('password,email,username', 'required'),
            array('username', 'checkUsername'),
            array('email', 'checkEmail'),
            array('password,username,', 'length', 'min' => 5),
            array('email', 'email'),
            //array('passwordConfirm', 'compare', 'compareAttribute' => 'password'),
            array('password', 'register', 'on' => 'insert', 'skipOnError' => true),
        );
    }

    public function myCaptcha($attr, $params) {
        if (Yii::app()->request->isAjaxRequest)
            return;

        CValidator::createValidator('captcha', $this, $attr, $params)->validate($this);
    }

    public function onBeforeValidate($event) {
        $r = parent::onBeforeValidate($event); 
        return $r;
    }

    public function behaviors() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'username' => 'Логин',
            'password' => 'Пароль',
            'passwordConfirm' => 'Подтверждение пароля',
            'sex' => 'Пол',
            'name' => 'Имя',
            'name' => 'Фамилия',
            'country_id' => 'Страна',
            'email' => 'E-mail',
            'contacts' => 'Телефон',
            'dateOfBirth' => 'Год рождения',
            'verifyCode' => 'Код проверки',
        );
    }

    public function authenticate() {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Неверный логин и\или пароль');
            else
                Yii::app()->user->login($this->_identity, 0);
        }
    }

    public function checkUsername($attribute, $params) {
        if (!is_null(User::model()->find('username = :username', array(':username' => $this->username)))) {
            $this->addError($attribute, 'Выбранный логин уже существует');
        }
    }

    public function checkEmail($attribute, $params) {
        if (!is_null(User::model()->find('email = :email', array(':email' => $this->email)))) {
            $this->addError($attribute, 'Пользователь с данным Email уже зарегистрирован');
        }
    }

    public function register($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = new User;
            if (!$user->register($this)) {
                $this->addErrors($user->getErrors());
                return false;
            } else {
                
                return true;
            }
        } else {
            return false;
        }
    }
}
