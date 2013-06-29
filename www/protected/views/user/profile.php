<div class="usr_profile">
    <div class='info'>
        <h1><?=$model->username;?></h1>
    </div>
    <div class="footer">
        <ul>
            <li>
                <?php if($model->postsCount)
                    echo CHtml::link ('<strong>'.$model->postsCount.'</strong><br/> POSTS', array('/'.$model->username));
                    
                    ?>
            </li>
            <li>
                <?php if($model->followsCount)
                    echo CHtml::link ('<strong>'.$model->followsCount.'</strong><br/> FOLLOWS', array('/'.$model->username.'/follow'));?>
            </li>
            <li>
                <?php if($model->followersCount)
                    echo CHtml::link ('<strong>'.$model->followersCount.'</strong><br/> FOLLOWERS', array('/'.$model->username.'/followers'));
                    ?>                
            </li>
        </ul>
        <div class="actions">
            <?php if(!yii::app()->user->isGuest && $model->id!=yii::app()->user->id)$model->getButtonTitle4profile($this);?>
        </div>
    </div>
</div>
<div style="display: none;">
</div>
        <style>
            .actions{
                float:right;
                margin:10px;
            }
            .usr_profile{
                width:520px;
                border:1px solid rgba(0, 0, 0, 0.102);
                
                border-radius: 6px;
                line-height: 16px;
                margin-bottom: 10px;
                position: relative;
                background-clip: padding-box;
                float: right;
                
            }
            .info{
                height: 260px;
                overflow: hidden;
                text-align: center;
                padding: 0;
                border-top-right-radius: 6px;
                border-top-left-radius: 6px;
                background-color: grey;
                display: block;
            }
            .footer ul li{
                float:left;
                list-style: none;
                display: inline;
                margin: 0;
                border-left: 1px solid #E8E8E8;
            }
            .footer ul li:first-child{
                border-left: none;
            }
            .footer ul{
                margin:0;
            }
            .footer ul li a{
                padding: 10px 30px 8px 12px;
                float:left;
                display: inline;
                list-style: none;
                font-size: 10px;
                line-height: 16px;
            }
           
            .footer ul li:hover a{
                color: #038543;
                text-decoration: none;
            }
            .footer strong{
                font-size:14px;
            }
        </style>