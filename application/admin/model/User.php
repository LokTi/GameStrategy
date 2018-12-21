<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{   
    //设置主键
    # protected $pk = 'userID';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user';
    
    function addUser($userID,$userNamen,$password,$userType,$userStatus){                   //增加用户
        $user= new User;
        $user->userID= $userID;
        $user->userName= $userNamen;
        $user->password=$password;
        $user->userType=$userType;
        $user->userStatus=$userStatus;
        return $user->save();
    }
    function changUser($userID,$userName,$password,$userType,$userStatus){                  //修改用户
        $user=User::get($userID);
        $user->userName= $userName;
        $user->password=$password;
        $user->userType=$userType;
        $user->userStatus=$userStatus;
        return $user->save();
    }
    
    function commentOn($userID){
        $user=User::get($userID);
        $user->userStatus='1';
        return $user->save();
    }
    
    function commentOff($userID){
        $user=User::get($userID);
        $user->userStatus='0';
        return $user->save();
    }
    
    function deleteUser($userID){                                                            //删除用户
        $user = new User();
        return $user->where('userID',$userID)->delete();
    }
}