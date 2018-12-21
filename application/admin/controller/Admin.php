<?php
namespace app\admin\controller;


use app\admin\model\Information;
use think\Controller;

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
