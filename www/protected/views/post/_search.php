<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'author_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'insert_date',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'update_date',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'text',array('class'=>'span5','maxlength'=>1024)); ?>

	<?php echo $form->textFieldRow($model,'repost_id',array('class'=>'span5','maxlength'=>10)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
