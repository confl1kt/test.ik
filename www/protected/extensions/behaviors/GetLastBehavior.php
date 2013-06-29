<?php

class GetLastBehavior extends CActiveRecordBehavior
{

    public function last($c = 3)
    {
        $this->owner->getDbCriteria()->mergeWith(array(
            'order'=>'id DESC',
	    'limit'=>$c,
        ));
        return $this->owner;
    }

}

?>