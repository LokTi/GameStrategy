<?php
namespace app\admin\controller;


use app\admin\model\Information;
use app\admin\model\Game;
use app\admin\model\User;
use app\admin\model\Comment;
use think\Controller;
use think\Request;

class Admin extends Controller
{
    public function index()
    {
        
        return view();
    }
    public function home(){
        $user = new User();
        $users = $user->select();
        
        $comment = new Comment();
        
        $a = 0;
        $b = 0;
        foreach($users as $value){
            if($value->userType == 0){
                $a += $comment->where('userID',$value->userID)->count();
            }
            else if($value->userType == 1){
                $b += $comment->where('userID',$value->userID)->count();
                
            }
        }

        $this->assign('a',$a);
        $this->assign('b',$b);

        return view();
    }
    public function content1(){
        $info=new Information();
        $infos=$info->select();
        $this->assign('infos',$infos);
        return view();
    }
    public function content2(){
        return view();
    }
    
    
    
    public function content3(){
        
        
        
        //按钮选择
        $game = new Game();
        $request = Request::instance();
        
        if($request->has('type','get')){
            $type = $request->get('type');
            if($type == "add"){
                $game->addGame($request->post('gameID'),$request->post('gameName'),$request->post('gameInfo'),$request->post('gameImg'),$request->post('gameType'),$request->post('gamePlat'));
            }
            else if($type == "change"){
                $game->changeGame($request->post('gameID'),$request->post('gameName'),$request->post('gameInfo'),$request->post('gameImg'),$request->post('gameType'),$request->post('gamePlat'));
            }else if($type == "delete"){
                $game->deleteGame($request->get('gameID'));
            }
        }
        
        //分页
        $game = new Game();
        $list = $game->paginate(3);
        $this->assign('list',$list);
        
        //输出内容
        $game = new Game();
        $games = $game->select();
        $this->assign('games',$games);
        return view();
    }
    
    
    public function content4(){
        $user = new User();
        $request = Request::instance();
        if($request->has('type','get')){
            $type = $request->get('type');
            if($type == "on"){
                $user->commentOn($request->get('userID'));
            }
            else if($type == "off"){
                $user->commentOff($request->get('userID'));
            }
        }
        
        //分页
        $user = new User();
        $list = $user->where('userType = 1')->paginate(3);
        $this->assign('list',$list);
        
        
        $user = new User();
        $users = $user->where('userType = 1')->select();
        $this->assign('users',$users);
        return view();
    }
    public function admin_msg1(){
        $comment = new Comment();
        $request = Request::instance();
        if($request->has('type','get')){
            $type = $request->get('type');
            if($type == "delete"){
                $comment->deleteComment($request->get('commentID'));
            }
        }
        
        
        $user = new User();
        $users = $user->where('userType = 0')->select();
        
        $comment = new Comment();
        
        $flag = 0;
        foreach($users as $value){
            if($flag == 0){
                $comments = $comment->where('userID',$value->userID)->select();
                $flag = 1;
            }
            else{
                $commentx = $comment->where('userID',$value->userID)->select();
                $comments = array_merge($comments,$commentx);
            }
            
        }
        
        //分页
        
        /* foreach ($comments as $comment){
            $list = $comment->paginate(3);
        }
        
        $this->assign('list',$list); */
        $this->assign('comments',$comments);
        
        return view();
    }
    public function admin_msg2(){
        $comment = new Comment();
        $request = Request::instance();
        if($request->has('type','get')){
            $type = $request->get('type');
            if($type == "delete"){
                $comment->deleteComment($request->get('commentID'));
            }
        }
        
        $user = new User();
        $users = $user->where('userType = 1')->select();
        
        $comment = new Comment();
        
        $flag = 0;
        foreach($users as $value){
            if($flag == 0){
                $comments = $comment->where('userID',$value->userID)->select();
                $flag = 1;
            }
            else{
                $commentx = $comment->where('userID',$value->userID)->select();
                $comments = array_merge($comments,$commentx);
            }
            
        }
        
        $this->assign('comments',$comments);
        return view();
    }
}
