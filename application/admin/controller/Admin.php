<?php
namespace app\admin\controller;


use app\admin\model\Information;
use app\admin\model\Game;
use app\admin\model\User;
use app\admin\model\Comment;
use think\Controller;
use think\Request;
use think\Cookie;

class Admin extends Controller
{
    public function index()
    {

        $request = Request::instance();
        $user=new User();
        $userName=$request->cookie('administrator');

        if(null==$userName){
            $this->redirect("admin/login");
        }else{
            if($request->has("type","get")){
                if($request->get("type")=="changePassword"){
                    $old=$request->post("oldPassword");
                    $new=$request->post("newPassword");
                    $new2=$request->post("newPassword2");
                    $userInfo=$user->where("userName",$userName)->find();
                    if($userInfo["password"] == $old){
                        if($new == $new2){
                            $user->changePassword($userInfo["userID"], $new);
                            Cookie::set("administrator",null);
                            $this->redirect("admin/login");
                        }else{
                            echo "<script>
                                     alert('请输入相同的密码！');
                                    </script>";
                        }
                    }else{
                        echo "<script>
                                     alert('当前密码错误！');
                                    </script>";
                    }
                }else if($request->get("type")=="logout"){
                    Cookie::set("administrator",null);
                    $this->redirect("admin/login");
                }
            }
        }
        $this->assign('userName',$userName);
        $this->assign("loginTime",date('Y-m-d H:i:s',time()));
        return view();
    }
    public function home(){
        $user = new User();
        $users = $user->select();
        
        $comment = new Comment();
        
        $a = 0;
        $b = 0;
        foreach($users as $value){
            if($value->userType == 3){
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
        $request = Request::instance();
        
        if(null==$request->cookie('administrator')){
            
            $this->redirect("admin/login");
            
        }
        
        $info=new Information();
        
        $request = Request::instance();
        
        #setcookie('userID',1);//测试使用
        
        if($request->has('type','get')){
            
            $type=$request->get('type');
            if($type==="add"){
                $info->addInformation($request->post('infoID'), $request->post('gameID'), $request->post('infoTitle'), $request->cookie('userID'), $request->post('infoKey'), $request->post('infoContent1'));
                
            }else if($type==="change"){
                
                $info->changeInformation($request->post('infoID'), $request->post('infoTitle'), $request->post('infoKey'), $request->post('infoContent'));
                
            }else if($type==="delete"){
                
                $info->deleteInformation($request->get('infoID'));
                
            }else if($type==="search"){
                
                $infos=$info->where('infoStatusReason',1)->where("infoTitle",$request->post('infoTitle'))->select();
                
                $this->assign('infos',$infos);
                
                return view();
                
            }
            
        }
        
        $info = new Information();
        $list = $info->where('infoStatusReason',1)->paginate(3);
        $this->assign('list',$list);
        
        $infos=$info->where('infoStatusReason',1)->select();
        
        $this->assign('infos',$infos);
        
        return view();
    }
    public function content2(){
        $request = Request::instance();
        
        if(null==$request->cookie('administrator')){
            
            $this->redirect("admin/login");
            
        }
        
        
        
        $info=new Information();
        
        $request = Request::instance();
        
        
        
        if($request->has('type','get')){
            
            $type=$request->get('type');
            
            if($request->has('infoID','get')){
                
                $infoID=$request->get('infoID');
                
                if($type==="allow"){
                    
                    $info->changeInfoStatus($infoID, 1);
                    
                }else if($type==="reject"){
                    
                    $info->changeInfoStatus($infoID, $request->post('reason'));
                    
                }
                
            }
            
            else if($type==="search"){
                
                $infos=$info->where('infoStatusReason',0)->where("infoTitle",$request->post('infoTitle'))->select();
                
                $this->assign('infos',$infos);
                
                return view();
                
            }
            
        }
        
        $info = new Information();
        $list = $info->where('infoStatusReason',0)->paginate(3);
        $this->assign('list',$list);
        
        $infos=$info->where('infoStatusReason',0)->select();
        
        $this->assign('infos',$infos);
        
        return view();
    }
    
    
    
    public function content3(){
        
        
        
        //按钮选择
        $game = new Game();
        $request = Request::instance();
        
        if($request->has('type','get')){
            $type = $request->get('type');
            if($type == "add"){
                
                
                $files = request()->file('img');
                foreach ($files as $file){
                    $imgInfo = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if($imgInfo){
                        echo  $imgInfo->getExtension();
                        echo  $imgInfo->getFilename();
                    }
                    else {
                        echo $file->getError();
                    }
                }
                $game->addGame($request->post('gameID'),$request->post('gameName'),$request->post('gameInfo1'),$request->post('gameType'),$request->post('gameType'),$request->post('gamePlat'));
                
            }
            else if($type == "change"){
                $game->changeGame($request->post('gameID'),$request->post('gameName'),$request->post('gameInfo2'),$request->post('gameImg'),$request->post('gameType'),$request->post('gamePlat'));
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
        $users = $user->where('userType = 3')->select();
        
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
    public function login(){
        $request = Request::instance();
        
        if(null!==$request->cookie('administrator')){
            $this->redirect("admin/index");
        }
        $user=new User();
        if($request->has("userName","post")&&$request->has("password","post")){
            $userName=$request->post("userName");
            $password=$request->post("password");
            $userInfo=$user->where("userName",$userName)->find();
            if($userInfo["password"]==$password&&$userInfo["userType"]==3){
                Cookie::set("administrator",$userInfo["userName"]);
                $this->redirect("admin/index");
            }
        }
        
        return view();
    }
}
