<?php
$this->menu=array(
	array('label'=>'Посты','url'=>array('/'.$model->username)),
	array('label'=>'Читает','url'=>array($model->username.'/follow')),
	array('label'=>'Followers','url'=>array($model->username.'/followers')),
	array('label'=>'Favorite','url'=>array($model->username.'/favorite')),	
);

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => yii::app()->createUrl('user/changepassword'),
    'id' => 'verticalForm',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'htmlOptions'=>array('class'=>'well'),
));
?>
<h1>Смена пароля</h1>
<?php echo $form->passwordFieldRow($model, 'checkPassword', array('class' => 'span5', 'maxlength' => 256)); ?>
<?php echo $form->passwordFieldRow($model, 'newpassword', array('class' => 'span5', 'maxlength' => 256)); ?>
<?php echo $form->passwordFieldRow($model, 'newpassword2', array('class' => 'span5', 'maxlength' => 256)); ?>

<div class="form-actions">

    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Сменить пароль' ,
    ));
    ?>
</div>


<?php $this->endWidget(); ?>
