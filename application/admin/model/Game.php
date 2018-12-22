<?php
namespace app\admin\model;

use think\Model;

class Game extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'game';
    
    function addGame($gameID,$gameName,$gameInfo,$gameImg,$gameType,$gamePlat){             //增加新的游戏
        $game=new Game();
        $game->gameID=$gameID;
        $game->gameName=$gameName;
        $game->gameInfo=$gameInfo;
        $game->gameImg=$gameImg;
        $game->gameType=$gameType;
        $game->gamePlat=$gamePlat;
        return $game->save();
    }
    function changeGame($gameID,$gameName,$gameInfo,$gameImg,$gameType,$gamePlat){          //修改游戏信息
        $game=Game::get($gameID);
        $game->gameName=$gameName;
        $game->gameInfo=$gameInfo;
        $game->gameImg=$gameImg;
        $game->gameType=$gameType;
        $game->gamePlat=$gamePlat;
        return $game->save();
    }
    function clickGame($gameID){                    //游戏点击量
        $game=Game::get($gameID);
        /*$game->gameClick++;
        if($game->save()){
            return true;
        }else{
            return false;
        }*/
    }
    function deleteGame($gameID){                   //删除游戏
        $game=new Game();
        return $game->where('gameID',$gameID)->delete();
    }
}