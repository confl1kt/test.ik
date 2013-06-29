<div class="view" id="post<?= $data->id ?>">
    <div class="view_head">
        <?php echo CHtml::link(CHtml::encode($data->username), array('/' . $data->username)); ?>        
    </div>
    <p class="text">
        <?php if($data->id!=yii::app()->user->id)
        $data->getButtonTitle($this);
        ?>
    </p>    
</div>
