<?php

class Favorites {

    private $_favoritesPosts = null;

    public function init() {
        if ($this->_favoritesPosts === null) {
            $this->_favoritesPosts = array();
            if (!$this->_favoritesPosts = yii::app()->cache->get('favorites-' . yii::app()->user->name)) {
                $this->_favoritesPosts = CHtml::listData(
                                Favorite::model()->findAll(
                                        'author_id=:id', array(':id' => yii::app()->user->id)), 'post_id', 'id');
                $this->save();
            }
        }
    }

    public function save() {
        yii::app()->cache->set('favorites-' . yii::app()->user->name, $this->_favoritesPosts, 3600);
    }

    public function is($id) {
        $this->init();
        if (isset($this->_favoritesPosts[$id]))
            return $this->_favoritesPosts[$id];
        else
            return false;
    }

    public function add($id, $post_id) {
        $this->init();
        $this->_favoritesPosts[$post_id] = $id;
        $this->save();
    }

    public function remove($id) {
        $this->init();
        $this->_favoritesPosts[$id] = null;
        $this->save();
    }

}

?>
