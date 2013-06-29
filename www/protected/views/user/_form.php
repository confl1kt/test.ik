<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'action' => yii::app()->createUrl('user/register'),
    'id' => 'user-form',
    //'enableAjaxValidation' => true,
    //'enableClientValidation' => true,
    'htmlOptions'=>array('class'=>'well'),
    'type'=>'horizontal',
));
?>

<legend>Регистрация</legend>

<?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 256)); ?>
<?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span5', 'maxlength' => 256)); ?>
<?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 256)); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Зарегистрироваться' ,
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
