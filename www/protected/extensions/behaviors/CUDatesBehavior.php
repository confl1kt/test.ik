<?php

class CUDatesBehavior extends CActiveRecordBehavior {

    public $insert = true;
    public $update = false;

    public function beforeSave($event) {
        if ($this->owner->isNewRecord) {
            if ($this->insert)
                $this->owner->insert_date = time();
        }
        else if ($this->update)
            $this->owner->update_date = time();
    }

}

?>