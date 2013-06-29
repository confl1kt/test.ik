<div class="items-list">
    <div class="items-title">Posts</div>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'/post/_view',
        'template'=>'{items}<div class="items-footer">{pager}</div>',
    'emptyText'=>'',
)); ?>
    <div class="items-footer">
        
    </div>
</div>







<style>
    .items-footer{
        height: 52px;
    }
    .items-list{
        float:right;
        width:520px;
        border: 1px solid #E8E8E8;
        border-radius: 6px;
    }
    .items-title{
        padding: 12px;
        border-radius: 6px 6px 0 0 ;
        font-size: 18px;
        font-weight: 700;
        line-height: 20px;
        border-bottom: 1px solid #E8E8E8;
    }
    .view{
        cursor: pointer;
        border-bottom: 1px solid #E8E8E8;
        padding: 9px 12px;
        min-height: 51px;
        display: block;
        font-family: "Arial", sans-serif;
        color: #333333;
        font-size: 14px;         
    }
    .pysto{
        margin-bottom: 20px;
    }
    .view_head{
        display: block;
        line-height: inherit;
    }
    .time{
        color: #999999;
        float:right;
    }
    .author{
        float:left;
    }
    .view_head a{
        color: #0084B4;
        font-weight: bold;
    }
    .btm_mnu{
        display: none;
    }
    .view:hover .btm_mnu{
        display: block;
        margin-top: -20px;
    }
    .btm_mnu ul{
        margin:0;
        padding:0;
    }
    .btm_mnu ul li{
        list-style: none;display: inline;
    }
</style>