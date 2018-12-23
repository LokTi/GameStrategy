<?php
namespace app\index\controller;

use app\admin\model\Information;

use app\admin\model\Comment;
use app\admin\model\Game;
use app\admin\model\User;
use think\Controller;
use think\Request;
use think\Cookie;


class Index extends Controller
{
    public function index()
    {
        $user=new User();
        $request=Request::instance();

        if($request->has("type","get")){
            if($request->get("type")=="logout"){
                Cookie::set("userID",null);
                $this->redirect("index/index");
            }
        }
        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        return view();
    }
    public function information_page(){

        $request = Request::instance();
        $infoID = $request->get('infoID');
        $info = new Information();
        $comment = new Comment();
        $user=new User();
        $request=Request::instance();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }

        if($request->has("type","get")){
            if($request->get("type")=="check"){
                if(null == $request->cookie('userID')){
                    $this->redirect("index/login");
                }else{
                    $userID = $request->cookie('userID');
                    $commentContent = $request->post('content');
                    $newComment = new Comment();
                    $newComment->addComment( $infoID, $userID, $commentContent);
                }
            }
        }

        $infos = $info->where('infoID',$infoID)->select();
        $comments = $comment->where('infoID',$infoID)->select();
        $latestInfos = $info->order('infoID desc')->limit(5)->select();
        $this->assign('infos',$infos);
        $this->assign('latestInfos',$latestInfos);
        $this->assign('comments',$comments);

       return view();
    }



    public function contact()
    {
        $user=new User();
        $request=Request::instance();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        return view();
    }



    public function hostgame_index(){

        $game = new Game();
        $info = new Information();
        $user=new User();
        $request=Request::instance();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        $infosh1 = $info->limit(1)->select();
        $infosh2 = $info->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->order('infoDate desc')->limit(2,4)->select();
        $infos2 = $info->order('infoClick desc')->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);



        //热门游戏
        $hotgames = $game->limit(1,5)->select();
        $this->assign('hotgames',$hotgames);
        //新游上市
        $newgames = $game->order('gameID desc')->where("gamePlat LIKE '%PS4%'")->limit(5)->select();
        $this->assign('newgames',$newgames);
        //XBOX热门
        $xboxgames = $game->where("gamePlat LIKE '%XBOX%'")->limit(5)->select();
        $this->assign('xboxgames',$xboxgames);
        //PS4热门
        $ps4games = $game->where("gamePlat LIKE '%PS4%'")->limit(5)->select();
        $this->assign('ps4games',$ps4games);
        //Wii游戏
        $wiigames = $game->where("gamePlat LIKE '%Wii%'")->limit(5)->select();
        $this->assign('wiigames',$wiigames);
        //NS推荐
        $nsgames = $game->where("gamePlat LIKE '%NS%'")->limit(5)->select();
        $this->assign('nsgames',$nsgames);

        return view();
    }
    public function personal_page()
    {
        $request = Request::instance();
        $userID_p = $request->get('userID');
        $user = new User();
        $info = new Information();

        //登陆注销
        if($request->has("type","get")){
            if($request->get("type")=="logout"){
                Cookie::set("userID",null);
                $this->redirect("index/personal_page");
            }
        }
        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }

        //用户
        $userInfomation = $user->where('userID',$userID_p)->find();
        $userImg_PATH="../../../../public/uploads/userImg/".$userInfomation["userID"].".jpg";
        $this->assign('user',$userInfomation);
        $this->assign('img',$userImg_PATH);

        //推荐文章
        $inforHot = $info->where('userID',$userID_p)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //最新发表
        $infoNew = $info->where('userID',$userID_p)->order('infoDate desc')->limit(2)->select();
        $this->assign('infoNew',$infoNew);

        return view();
    }
    public function onlinegame_index()
    {
        $user=new User();
        $request=Request::instance();
        $game = new Game();
        $info = new Information();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
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
        //新游上市
        $newgames = $game->order('gameID desc')->limit(5)->select();
        $this->assign('newgames',$newgames);
        //角色扮演
        $rpggames = $game->where("gameType = 'RPG'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //动作游戏
        $actgames = $game->where("gameType = 'ACT'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //竞技游戏
        $mobagames = $game->where("gameType = 'MOBA'")->limit(5)->select();
        $this->assign('mobagames',$mobagames);
        //休闲游戏
        $fpsgames = $game->where("gameType = 'FPS'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);

        return view();
    }
    public function mobilegame_index()
    {
        $user=new User();
        $request=Request::instance();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        return view();
    }
    public function game_page()
    {
        $request=Request::instance();
        $user=new User();
        $game = new Game();
        $gameID = $request->get('gameID');
        $info = new Information();

        if($request->has("type","get")){
            if($request->get("type")=="logout"){
                Cookie::set("userID",null);
                $this->redirect("index/index");
            }
        }
        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }

        //游戏讯息
        $gameinfo = $game->where('gameID',$gameID)->find();
        $gameImg_PATH = "../../../../public/uploads/".$gameinfo["gameID"]."/0.jpg";
        $this->assign('game',$gameinfo);
        $this->assign('img',$gameImg_PATH);

        //热门攻略
        $inforHot = $info->where("gameID",$gameID)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //最新发表
        $infoNew = $info->where("gameID",$gameID)->order('infoDate desc')->limit(5)->select();
        $this->assign('infoNew',$infoNew);

        return view();
    }

    public function singlegame_index()
    {
        $user=new User();
        $request=Request::instance();
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

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        return view();
    }
    public function login()
    {
        $user=new User();
        $request = Request::instance();

        if(null!==$request->cookie('userID')){
            $this->redirect("index/index");
        }
        if($request->has("userName","post")&&$request->has("password","post")){
            $userName=$request->post("userName");
            $password=$request->post("password");
            $userInfo=$user->where("userName",$userName)->find();
            if($userInfo!==null&&$password!==''){
                if($userInfo["password"]==$password){
                    Cookie::set("userID",$userInfo["userID"]);
                    $this->redirect("index/index");
                }
            }
            else{
                echo "<script>
                     alert('登陆失败！');
                    </script>";
            }
        }
        return view();
    }
    public function register()
    {
        $user=new User();
        $request=Request::instance();

        if($request->has("userName","post")&&$request->has("password","post")){
            $userName=$request->post("userName");
            $password=$request->post("password");
            $password1=$request->post("password1");
            $img=$request->file("userImg");
            $userInfo=$user->where("userName",$userName)->find();
            if($userName!==''){
                if($password!==''){
                    if(null!=$userInfo){
                        echo "<script>
                     alert('该用户名被占用！');
                    </script>";
                    }else if($password!=$password1){
                        echo "<script>
                     alert('请输入相同的密码！');
                    </script>";
                    }else{
                        $userID=$user->max("userID")+1;
                        $user->addUser($userID, $userName, $password, 1, 1);
                        $imgInfo = $img->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'userImg',$userID.".jpg");
                        echo "<script>
                     alert('注册成功！');
                    </script>";
                        Cookie::set("userID",$userInfo["userID"]);
                        $this->redirect("index/login");
                    }
                }else{
                    echo "<script>
                     alert('密码不能为空！');
                    </script>";
                }
            }else{
                echo "<script>
                     alert('用户名不能为空！');
                    </script>";
            }
        }
        return view();
    }
}
