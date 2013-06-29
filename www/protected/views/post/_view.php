<div class="view" id="post<?=$data->id?>">
    <div class="view_head">
        <?php echo CHtml::link(CHtml::encode($data->authorName), array('/' . $data->authorName)); ?>
        <small class="time">
            <?php echo CHtml::encode($data->time); ?>
        </small>
    </div>
    <p class="text">
        <?php echo CHtml::encode($data->postText); ?>
    </p>    

    <?php if ($data->isRepost): ?>
        <small class="repost">    
            репост: 
            <?php echo CHtml::link(CHtml::encode(yii::app()->users->get($data->author_id)), array('/' . yii::app()->users->get($data->author_id))); ?>
        </small>
    <?php endif; ?>
    <div class="pysto"></div>
   
    <div class="btm_mnu">
        <ul>
            <li class="favorite">
                <?php if(!yii::app()->user->isGuest)echo $data->checkFavorite($this); ?>
            </li>
            <li class="repost">
                <?php if(!yii::app()->user->isGuest && $data->author_id!=yii::app()->user->id){
                    echo CHtml::ajaxLink('Repost', 
                        CController::createUrl('post/repost',array('id'=>$data->idRepost)), 
                        array('update' => '#post' . $data->id . ' li.repost'));
                } ?>
            </li>
            <li class="delete">
                <?php if(!yii::app()->user->isGuest && $data->author_id==yii::app()->user->id){
                    echo CHtml::ajaxLink('Delete', 
                        CController::createUrl('post/delete',array('id'=>$data->id)), 
                        array('method'=>'post','update' => '#post' . $data->id . ' li.delete'));
                }?>
            </li>
            <li>

            </li>
        </ul>
    </div>
</div>
