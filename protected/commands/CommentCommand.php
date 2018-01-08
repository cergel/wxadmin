<?php

class CommentCommand extends CConsoleCommand
{

    /**
     * 全量刷新明星信息到redis
     * 通过commoncgi透传service完成
     */
    public function actionSaveCommentStar()
    {
        $arrData = CommentStar::model()->findAll();
        foreach($arrData as $val){
//            echo $val->id."\n";
            CommentStar::model()->saveCache($val->id,1);
        }
    }

}
