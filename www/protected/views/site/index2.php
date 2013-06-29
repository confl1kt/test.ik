<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php
$this->renderPartial('/post/index',array(
    'dataProvider'=>$posts,
));
?>
