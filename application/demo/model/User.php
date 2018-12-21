<?php
namespace app\demo\model;

use think\Model;

class User extends Model
{
    //设置主键
   # protected $pk = 'userID';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user';
    
    function addUser($userID,$userNamen,$password,$userType,$userStatus){
        $user= new User;
        $user->userID= $userID;
        $user->userName= $userNamen;
        $user->password=$password;
        $user->userType=$userType;
        $user->userStatus=$userStatus;
        $user->save();
    }
    function changUser($userID,$userNamen,$password,$userType,$userStatus){
        $user=User::get($userID);
        $user->userName= $userNamen;
        $user->password=$password;
        $user->userType=$userType;
        $user->userStatus=$userStatus;
        $user->save();
    }
    function deleteUser($userID){
        $user = User::get($userID);
        $user->delete();
    }
    
}