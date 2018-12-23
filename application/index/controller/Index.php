<?php
namespace app\index\controller;

use app\admin\model\Game;
use think\Controller;
use app\admin\model\Information;

class Index extends Controller
{
    public function index()
    {
        return view();
    }
    public function contact()
    {
        return view();
    }
    public function intgame_index()
    {
        return view();
    }
    public function hostgame_index()
    {
        return view();
    }
    public function onlinegame_index()
    {
        return view();
    }
    public function mobilegame_index()
    {
        return view();
    }
    public function personal_page()
    {
        return view();
    }
    public function game_page()
    {
        return view();
    }
    public function information_page()
    {
        return view();
    }
    public function singlegame_index()
    {
        $game = new Game();
        $info = new Information();


        $infosh1 = $info->limit(1)->select();
        $infosh2 = $info->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->limit(2,4)->select();
        $infos2 = $info->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);



        //热门游戏
        $hotgames = $game->limit(1,5)->select();
        $this->assign('hotgames',$hotgames);
        //角色扮演游戏
        $rpggames = $game->where("gameType = 'RPG'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //即时战略游戏
        $rtsgames = $game->where("gameType = 'RST'")->limit(5)->select();
        $this->assign('rtsgames',$rtsgames);
        //动作游戏
        $actgames = $game->where("gameType = 'ACT'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //竞速游戏
        $racgames = $game->where("gameType = 'RAC'")->limit(5)->select();
        $this->assign('racgames',$racgames);
        //射击游戏
        $fpsgames = $game->where("gameType = 'FPS'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);
        //冒险游戏
        $avggames = $game->where("gameType = 'AVG'")->limit(5)->select();
        $this->assign('avggames',$avggames);
        //体育游戏
        $spggames = $game->where("gameType = 'SPG'")->limit(5)->select();
        $this->assign('spggames',$spggames);

        return view();
    }
}
