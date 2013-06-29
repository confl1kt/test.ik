<?php

class PublishedBehavior extends CActiveRecordBehavior
{

    public function published()
    {
        $this->owner->getDbCriteria()->mergeWith(array(
            'condition' => 'published = "Y"',
        ));
        return $this->owner;
    }
    
    public function notpublished()
    {
        $this->owner->getDbCriteria()->mergeWith(array(
            'condition' => 'published = "N"',
        ));
        return $this->owner;
    }
    
    public function setPublished($P)
    {
        $this->owner->published = $P;
        $this->owner->save();
    }


}

?>