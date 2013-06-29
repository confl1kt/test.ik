<?php

class Follower extends CActiveRecord {
    public static function model($className = __CLASS__) {return parent::model($className);}
    public function tableName(){return 'followers';}

    public function rules(){
        return array(
            array('author_id, user_id, insert_date', 'required'),
            array('author_id, user_id, insert_date', 'length', 'max' => 10),
            array('id, author_id, user_id, insert_date', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
        );
    }

    public function behaviors() {
        return array(
            'cudates' => array("class" => "ext.behaviors.CUDatesBehavior",'insert'=>true,'update'=>false),            
            'authorid' => array("class" => "ext.behaviors.AuthorIDBehavior", "name" => false),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'author_id' => 'Author',
            'user_id' => 'User',
            'insert_date' => 'Insert Date',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('author_id', $this->author_id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('insert_date', $this->insert_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    public function getUserRole(){
        if(yii::app()->controller->action->id=='follow'){
            return $this->user_id;
        }
        else return $this->author_id;
    }
    public function getUserName(){        
        return yii::app()->users->get($this->userRole);        
    }
    public function followButton(){
        
    }
    public function getButtonTitle(CController $controller) {
        $id=yii::app()->controller->action->id=="follow" ? $this->user_id : $this->author_id;
        if (yii::app()->follows->is($id)) {
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/unfollow', array('id' => $id)),
                        'label' => 'Отписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $id,),                        
                    ));
        }
        else
            return $controller->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'url' => $controller->createUrl('follower/follow', array('id' => $id)),
                        'label' => 'Подписаться',
                        'type' => 'primary',
                        'size' => 'normal',
                        'htmlOptions' => array('style' => 'float:right;', 'id' => $id,),                        
                    ));
    }
}