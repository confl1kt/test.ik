<?php

class DateModBehavior extends CModelBehavior
{
    public $fields;
    
    public function beforeValidate($event)
    {
        foreach($this->fields as $field)
        {
            if(!is_numeric($this->owner->$field)&&!is_null($this->owner->$field))
            {
                $this->owner->$field = strtotime($this->owner->$field);
            }
        }
    }

}

?>