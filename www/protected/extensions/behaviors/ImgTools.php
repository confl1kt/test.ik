<?php
class ImgTools extends CActiveRecordBehavior
{
    public $location = 'photo';
    public $noimage = '/images/nophoto.jpg';
    public $field = '';
    public $cacheSizes = array();
    public $nophoto = '/images/nophoto.jpg';
    
    public function getImgSrc($width, $height)
    {
        try
        {
        yii::import('pictures.components.helpers.ImageHelper');
        
        $field = $this->field;
        
        if(!file_exists('images/'.$this->location.'/'.$this->owner->$field) ||
                is_dir('images/'.$this->location.'/'.$this->owner->$field)
                )
        {
            return $this->nophoto;
        }

        $img = 'http://' . $_SERVER['HTTP_HOST'] . Yii::app()->request->baseUrl .
                ImageHelper::thumb($width, $height, '/' . 'images/'.$this->location.'/'.$this->owner->$field, array('method' => 'adaptiveResize'));
        
        return $img;
        }catch(Exception $e)
        {
            return $this->nophoto;
        }
    }
    
    public function cacheThumbs()
    {
        foreach($this->cacheSizes as $size)
        {
            $this->getImgSrc($size[0],$size[1]);
        }
    }
    
    public function onAfterSave()
    {
         $this->cacheThumbs();
    }
}

?>