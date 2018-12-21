<?php
namespace app\admin\model;

use think\Model;

class Information extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'information';
    
    function addInformation($infoID,$gameID,$infoTitle,$userID,$infoKey,$infoContent){       //增加新资讯
        $information=new Information();
        $information->infoID=$infoID;
        $information->gameID=$gameID;
        $information->infoTitle=$infoTitle;
        $information->userID=$userID;
        $information->infoKey=$infoKey;
        $information->infoDate=date("Y-m-d");
        $information->infoClick=0;
        $information->infoContent=$infoContent;
        return $information->save();
    }
    function changeInformation($infoID,$infoTitle,$infoKey,$infoContent){                   //修改资讯
        $information=Information::get($infoID);
        $information->infoTitle=$infoTitle;
        $information->infoKey=$infoKey;
        $information->infoContent=$infoContent;
        return $information->save();
    }
    function clickInformation($infoID){                                                     //点击资讯
        $information=Information::get($infoID);
        $information->infoClick++;
        return $information->save();
    }
    function deleteInformation($infoID){                                                    //删除资讯
        $information=new Information();
        return $information->where('infoID',$infoID)->delete();
    }
}