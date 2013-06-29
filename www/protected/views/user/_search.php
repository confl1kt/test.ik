<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
    'type'=>'search',
	'method'=>'get',
    
)); ?>
	<?php echo $form->textFieldRow(
                $model,
                'user',
                array(
                    'class'=>'input-medium',
                    'maxlength'=>256,
                    'label'=>false,
                    'prepend'=>'<i class="icon-search"></i>',
                    
                    )
                ); ?>	
	
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>


<?php $this->endWidget(); ?>
