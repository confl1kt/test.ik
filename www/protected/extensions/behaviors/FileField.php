<?php

/**
 *  Safe file behavior.
 *  (c) Maximus < ezhov.maxim@gmail.com >, 2011
 */
class FileField extends CActiveRecordBehavior
{

    /**
     * @var string location - folder with read\write rights for saving files
     */
    public $location;
    /**
     * @var array fields  - fields with files, key - attr name, value - rule
     */
    public $_fields = array();
    
    public $_fieldsValues = array();
    
    
    /**
     *
     * @param CEvent $event 
     */
    public function afterFind($event)
    {
        foreach($this->_fields as $k=>$v)
        {
            $this->_fieldsValues[$k] = $this->owner->$k;
        }
    }

    /**
     * Setter for fields param
     * Fields can be in next string format :
     * <pre>
     *   fieldname(format1,format2),fieldname2(etc,test)
     *   'fieldname'=>'ext,ext','fieldname2'=>'ext,ext'
     * </pre>
     * 
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        if(!is_array($fields))
        {
            
            $fields = preg_replace('/([a-z0-9_]+)\(([a-z0-9_,:]+)\)/i',"'$1'=>'$2'",$fields);

            if(strpos($fields, '=>')!==false)
            {
                eval('$fields = array('.$fields.');');
            }
            else
                $fields = explode(',',$fields);
        }
        
        $this->_fields = $fields;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Saves uploaded file.
     */
    public function saveFile($attribute, $params)
    {
        $file = CUploadedFile::getInstance($this->owner, $attribute);
        // testing if file was uploaded
        if (!$file)
        {
            if(isset($this->_fieldsValues[$attribute])&&$this->_fieldsValues[$attribute])
            $this->owner->$attribute = $this->_fieldsValues[$attribute];
            return;
        }

        // delete file of old picture
        if (!$this->owner->isNewRecord)
        {
             $pic = $this->_fieldsValues[$attribute];
             if (file_exists(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic)
                    && !is_dir(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic)
                    )
            {
                unlink(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic);
            }
        }

        $fileName = md5(time() . uniqid()) . '.' . $file->extensionName;
        $this->owner->$attribute = $fileName;

        //TODO: refactor into more flexable pathes
        @mkdir(yii::app()->getBasePath() . '/../images/' . $this->location . '/');
        // checks if location is writable
        if (!is_writable(yii::app()->getBasePath() . '/../images/' . $this->location . '/'))
            throw new CHttpException(500, 'Location ' . $this->location . ' is not writable');
        // saves file
        $file->saveAs(yii::app()->getBasePath() . '/../images/' . $this->location . '/' . $fileName);
    }

    /**
     * Deletes files of pictures
     */
    public function deletePicture($pic = null)
    {
        foreach ($this->_fields as $field => $attrs)
        {
            if (!$pic)
                $pic = $this->owner->$field;
            elseif (is_numeric($pic))
                $pic = CActiveRecord::model(get_class($this->owner))->findByPK($pic)->$field;

            //TODO: decide what to do with locations
            if (file_exists(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic)
                    && !is_dir(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic)
                    )
            {
                unlink(yii::app()->basePath . '/../images/' . $this->location . '/' . $pic);
            }
        }
    }

    public function beforeValidate($event)
    {    
        foreach ($this->_fields as $f => $rule)
        {
            $file = CUploadedFile::getInstance($this->owner, $f);
            // testing if file was uploaded
            if ($file)
            {
                if(is_array($rule))
                    $rule = array_slice($rule, 1);
                
                if(strpos($rule,':')!==false)
                        list($rule,$size) = explode(':',$rule,2);
                
                $params = array();
                $params['types']=$rule;
                $params['allowEmpty']=true;
                if(isset($size))
                    $params['maxSize']=$size;

                $v = CValidator::createValidator('file', $event->sender, $f, $params);
//                $v = CValidator::createValidator('file', $event->sender, $f, array('types'=>$rule));
                $event->isValid &= $v->validate($this->owner, null);
            }
            $this->saveFile($f,array());
        }
        $event->isValid = true;
        return true;
    }

    public function beforeDelete()
    {
        $this->deletePicture();
        return true;
    }

}

?>