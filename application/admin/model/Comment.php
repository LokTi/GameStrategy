<?php
namespace app\admin\model;

use think\Model;
use think\Exception;

class Comment extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'comment';
    
    function addComment($infoID,$userID,$commentContent){            //增加评论
        $comment=new Comment();
        $comment->commentID=$comment->max(commentID);
        $comment->infoID=$infoID;
        $comment->userID=$userID;
        $comment->commentContent=$commentContent;
        $comment->commentDate=date('Y-m-d H:i:s',time());
        
        return $comment->save();            
    }
    function changeComment($commentID,$commentContent){                            //修改评论
        $comment=Comment::get($commentID);
        $comment->commentContent=$commentContent;
        
        return $comment->save();
    }
    function deleteComment($commentID){                                             //删除评论
        $comment=new Comment();
        return $comment->where('commentID',$commentID)->delete();
    }
}