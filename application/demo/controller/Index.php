<?php
namespace app\demo\controller;

use think\Db;
use think\Controller;
use app\demo\model;
use app\demo\model\User;

class Index  extends Controller
{
    public function index()
    {
        $user=new User;
        echo $user->where('userID',1)->find();
        #$user->addOne(2,2,2,2,2);
        $date=Db::table('user')->select();
        $this->assign('data',$date);
       
        
        return view();
    }
}
