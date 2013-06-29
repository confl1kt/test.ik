<?php

class Post extends CActiveRecord {
    public static function model($className = __CLASS__) {return parent::model($className);}
    public function tableName() {return 'posts';}
    public function rules() {
        return array(
            array('author_id, insert_date', 'required'),
            array('author_id, insert_date, update_date, repost_id', 'length', 'max' => 10),
            array('text', 'length', 'max' => 140),
            array('id, author_id, insert_date, update_date, text, repost_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'reposts' => array(self::BELONGS_TO, 'Post', 'repost_id'),
            'favorite' => array(self::STAT, 'Favorite', 'post_id'),
            'myfavorite' => array(self::HAS_ONE, 'Favorite', 'post_id', 'on' => 'myfavorite.author_id=' . yii::app()->user->id),
            'Refavorite' => array(self::STAT, 'Favorite', 'post_id'),
            'myRefavorite' => array(self::HAS_ONE, 'Favorite', 'post_id', 'on' => 'myRefavorite.author_id=' . yii::app()->user->id),
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'Reauthor' => array(self::BELONGS_TO, 'User', 'author_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'author_id' => 'Author',
            'insert_date' => 'Insert Date',
            'update_date' => 'Update Date',
            'text' => 'Text',
            'repost_id' => 'Repost',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('author_id', $this->author_id, true);
        $criteria->compare('insert_date', $this->insert_date, true);
        $criteria->compare('update_date', $this->update_date, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('repost_id', $this->repost_id, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getAuthorName() {
        if ($this->repost_id) {
            return yii::app()->users->get($this->reposts->author_id);
        }
        else
            return yii::app()->users->get($this->author_id);
    }

    public function getTime() {
        if ($this->repost_id) {
            return $this->reposts->normalTime;
        }
        else
            return $this->normalTime;
    }

    public function getNormalTime() {
        $time = time();
        if ($time - $this->insert_date < 60)
            return $time - $this->insert_date . ' sec';
        if ($time - $this->insert_date < 3600)
            return intval(($time - $this->insert_date) / 60) . ' min';
        if ($time - $this->insert_date < 3600 * 24) {
            return intval(($time - $this->insert_date) / 3600) . ' hours';
        }
        if (date('Y', $time) == date('Y', $this->insert_date)) {
            return date('d m', $this->insert_date);
        }
        return date('d M y', $this->insert_date);
    }

    public function getIsRepost() {
        if ($this->repost_id)
            return true;
        return false;
    }
    
    public function getPostText(){
        if($this->isRepost)
            return $this->reposts->text;
        else return $this->text;
    }
    public function getIdRepost(){
        if($this->isRepost)
            return $this->repost_id;
        else 
            return $this->id;
    }
    public function checkFavorite(CController $cont) {
        $id=$this->isRepost ? $this->repost_id : $this->id; 
        if (!$fav_id=yii::app()->favorites->is($id))
            return CHtml::ajaxLink('Избранное', 
                    $cont->createUrl('post/addfavorite', 
                            array('id' => $id)), 
                    array('update' => '#post' . $id . ' li.favorite'));
        else
            return CHtml::ajaxLink('Убрать из избранного', 
                    $cont->createUrl('post/removefavorite', 
                            array('id' => $fav_id)), 
                    array('method' => 'post', 'update' => '#post' . $id . ' li.favorite'));
    }

    protected function afterFind(){       
          $this->editText();
    }
    public function editText(){
        return;//redaktirovat' text;
    }
}