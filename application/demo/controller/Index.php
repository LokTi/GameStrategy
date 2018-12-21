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
        $user->addUser(2,2,2,2,2);
        $user->changUser(2, 3, 3, 3, 3);
        $date=Db::table('user')->select();
        $this->assign('data',$date);
        $user->deleteUser(2);
        echo $user->count();
        return view();
    }
}
