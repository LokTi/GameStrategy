<?php
namespace app\admin\controller;

use app\admin\model\Game;
use think\Controller;
use app\admin\model\Comment;
use app\admin\model\Information;
use app\admin\model\User;

class Index extends Controller
{
    public function index()
    {
        $user=new User();
        $u=$user->addUser(2, 2, 2, 2, 2);
        $this->assign('user',$u);
        
        $game=new Game();
        $g=$game->addGame(2, 2, 2, 2, 2, 2);
        $this->assign('game',$g);
        
        
        $information=new Information();
        $i=$information->addInformation(2, 2, 2, 2, 2, 2, 2);
        $this->assign('information',$i);
        
        $comment=new Comment();
        $c=$comment->addComment(2, 2, 2, 2);
        $this->assign('comment',$c);
        
        return view();
    }
}
