<?php

class Follows {

    private $_follows = null;

    public function init() {
        if ($this->_follows === null){
            $this->_follows=array();
            if (!$this->_follows = yii::app()->cache->get('follow-' . yii::app()->user->name)) {
                $this->_follows = CHtml::listData(Follower::model()->findAll('author_id=:id', array(':id' => yii::app()->user->id)), 'user_id', 'id');
                $this->save();
            }
        }
    }

    public function save() {
        yii::app()->cache->set('follow-' . yii::app()->user->name, $this->_follows, 3600);
    }

    public function is($id) {
        $this->init();
        if (isset($this->_follows[$id]))
            return $this->_follows[$id];
        else
            return false;
    }

    public function getList($withUser = false) {
        $this->init();
        $array = array();
        if ($withUser)
            $array[] = yii::app()->user->id;
        foreach ($this->_follows as $key => $value) {
            if($value!=null)
                $array[] = $key;
        }
        return $array;
    }
    
    public function add($id,$follow_id){
        $this->init();
        $this->_follows[$id]=$follow_id;
        $this->save();
    }
    public function remove($id){
        $this->init();
        $this->_follows[$id]=null;
        $this->save();
    }
}

?>
