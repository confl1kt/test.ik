<?php

$this->menu=array(
	array('label'=>'Посты','url'=>array('/'.$model->username)),
	array('label'=>'Читает','url'=>array($model->username.'/follow')),
	array('label'=>'Followers','url'=>array($model->username.'/followers')),
	array('label'=>'Favorite','url'=>array($model->username.'/favorite')),	
);
?>


<?php
$this->renderPartial('/user/profile',array(
    'model'=>$model,
));
?>
<?php
$this->renderPartial('/favorite/index',array(
    'dataProvider'=>$favorites,
));
?>