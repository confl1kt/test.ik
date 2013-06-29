<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $active
 * @property integer $insert_date
 * @property string $roles
 */
class BasicUser extends CActiveRecord
{

    protected $_identity;
    protected $rememberMe = true;

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    public function validatePassword($password)
    {
        //TODO: realize hash function
        return md5($password) == $this->password;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'safe'),
            array('email', 'email'),
            array('insert_date', 'numerical', 'integerOnly' => true),
            array('username', 'length', 'max' => 40),
            array('password', 'length', 'max' => 32),
            array('active', 'length', 'max' => 1),
            array('roles', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, username, password, active, insert_date, roles', 'safe', 'on' => 'search'),
        );
    }

    static public function getHttpPage($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }


    public function login($username = null, $password = null)
    {
        if (!$username)
            $username = $this->username;
        if (!$password)
            $password = $this->password;

        yii::trace("ExUID = $username; Password = $password ");

        if ($this->_identity === null)
        {
            $this->_identity = new UserIdentity($username, $password);
            $this->_identity->authenticate(true);
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
        {
            Yii::app()->user->login($this->_identity, 0);
            yii::trace("Login successed");
            return true;
        }
        else
        {

            yii::trace('External authinticate faild with code ' . $this->_identity->errorCode, 'error');
            return false;
        }
    }
    
    public function loginN($username = null, $password = null)
    {
        if (!$username)
            $username = $this->username;
        if (!$password)
            $password = $this->password;

        if ($this->_identity === null)
        {
            $this->_identity = new UserIdentity($username, $password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
        {
            Yii::app()->user->login($this->_identity, 0);
            yii::trace("Login successed");
            return true;
        }
        else
        {

            yii::trace('External authinticate faild with code ' . $this->_identity->errorCode, 'error');
            return false;
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // TODO: connection with profiles
        return array(
        );
    }

    function behaviors()
    {
        return array(
            'inserttime' => array(
                'class' => 'application.extensions.behaviors.InsertTimeBehavior',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'active' => 'Active',
            'insert_date' => 'Insert Date',
            'roles' => 'Roles',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);

        $criteria->compare('username', $this->username, true);

        $criteria->compare('password', $this->password, true);

        $criteria->compare('active', $this->active, true);

        $criteria->compare('insert_date', $this->insert_date);

        $criteria->compare('roles', $this->roles, true);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

}