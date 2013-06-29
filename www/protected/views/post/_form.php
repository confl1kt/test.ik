<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'post-form',
        'action'=>'/post/create',
	'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'focus'=>array($model,'text'),
    'htmlOptions'=>array('class'=>'well span12'),
)); ?>
<legend>Оставить сообщение</legend>
	<?php echo $form->errorSummary($model); ?>	
	<?php echo $form->textAreaRow($model,'text',
                array(
                    'label'=>false,
                    'class'=>'span10',
                    'maxlength'=>140,
                    'rows'=>5,
                    'style'=>'width:270px;max-width:270px;max-height:200px;')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
