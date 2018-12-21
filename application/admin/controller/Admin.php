<?php
namespace app\admin\controller;


use app\admin\model\Information;
use think\Controller;
use think\Request;

class Admin extends Controller
{
    public function index()
    {
        
        return view();
    }
    public function home(){
        return view();
    }
    public function content1(){
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
            }
        }
        
        $info=new Information();
        $infos=$info->select();
        $this->assign('infos',$infos);
        return view();
    }
    public function content2(){
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
}
