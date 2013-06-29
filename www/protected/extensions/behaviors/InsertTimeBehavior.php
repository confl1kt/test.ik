<?php


	class InsertTimeBehavior extends CActiveRecordBehavior
	{
	    public function beforeSave($event)
	    {
	        if($this->owner->isNewRecord)
	            $this->owner->insert_date=time();
	        //else
	        //    if(property_exists($this->owner,'update_date'))$this->owner->update_date=time();
	    }
	}

?>