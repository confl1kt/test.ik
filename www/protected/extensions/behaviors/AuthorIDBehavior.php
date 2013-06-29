<?php

class AuthorIDBehavior extends CActiveRecordBehavior {

    public $name = true;

    public function beforeSave($event) {
        if ($this->owner->isNewRecord) {
            if ($this->owner->author_id==null)
                $this->owner->author_id = yii::app()->user->id;
            if ($this->name)
                if ($this->owner->author_name == '')
                    $this->owner->author_name = yii::app()->user->getModel()->username;
        }
    }

}

?>