<?php
namespace app\admin\controller;


use app\admin\model\Information;
use think\Controller;
use think\Request;
use app\admin\model\User;
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
                    if($userInfo["password"]===$old){
                        if($new===$new2){
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
        
        $infos=$info->where('infoStatusReason',0)->select();
        $this->assign('infos',$infos);
        return view();
    }
    public function content3(){
        return view();
    }
    public function content4(){
        return view();
    }
    public function admin_msg1(){
        return view();
    }
    public function admin_msg2(){
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
