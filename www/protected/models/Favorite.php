<?php

class Favorite extends CActiveRecord {
    public static function model($className = __CLASS__){return parent::model($className);}
    public function tableName(){return 'favorites';}
    public function behaviors() {
        return array(
            'cudates' => array("class" => "ext.behaviors.CUDatesBehavior"),            
            'authorid' => array("class" => "ext.behaviors.AuthorIDBehavior", "name" => false),
        );
    }

    public function rules() {
        return array(
            array('author_id, post_id, insert_date', 'required'),
            array('author_id, post_id, insert_date', 'length', 'max' => 10),           
            array('id, author_id, post_id, insert_date', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'author'=>array(self::BELONGS_TO,'User','author_id'),
            'post'=>array(self::BELONGS_TO,'Post','post_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'author_id' => 'Author',
            'post_id' => 'Post',
            'insert_date' => 'Insert Date',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('author_id', $this->author_id, true);
        $criteria->compare('post_id', $this->post_id, true);
        $criteria->compare('insert_date', $this->insert_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}