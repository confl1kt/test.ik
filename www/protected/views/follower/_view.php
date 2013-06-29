<div class="view" id="post<?= $data->id ?>">
    <div class="view_head">
        <?php echo CHtml::link(CHtml::encode($data->userName), array('/' . $data->userName)); ?>        
    </div>
    <p class="text">
        <?php
            $data->getButtonTitle($this);
        ?>
    </p>    
</div>
