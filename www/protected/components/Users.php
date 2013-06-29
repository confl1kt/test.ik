<?php

class Users {
    private $_userList = null;

    public function init() {
        if ($this->_userList === null) {
            $this->_userList=array();
            if (!$this->_userList = yii::app()->cache->get('userList')) {
                $this->_userList = CHtml::listData(User::model()->findAll(), 'id', 'username');
                $this->save();
            }
        }
    }

    public function save() {
        yii::app()->cache->set('userList', $this->_userList, 3600);
    }

    public function is($id) {
        $this->init();
        if (isset($this->_userList[$id]) && $this->_userList[$id]!=null)
            return true;
        return false;
    }

    public function get($id) {
        $this->init();
        if ($this->is($id))
            return $this->_userList[$id];
        return false;
    }
    public function add($id,$name){
        $this->init();
        $this->_userList[$id]=$name;
        $this->save();
    }
    public function remove($id){
        $this->init();
        $this->_userList[$id]=null;
        $this->save();
    }
}

?>
