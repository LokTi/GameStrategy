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
        $game = new Game();

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

        $games = $game->order('gameClick DESC')->limit(5)->select();
        $this->assign('hotgames',$games);

        return view();
    }

    public function information_page(){

        $request = Request::instance();
        $infoID = $request->get('infoID');
        $info = new Information();
        $user = new User();
        $comment = new Comment();
        $request=Request::instance();

        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }

        if($request->has("type","get")){
            if($request->get("type")=="check"){
                if($request->has("userID","cookie")){
                    $userID = $request->cookie('userID');
                    $commentContent = $request->post('content');
                    $newComment = new Comment();
                    $newComment->addComment( $request->get('infoID'), $userID, $commentContent);
                }else{
                    $this->error('用户未登录！', 'login');
                }
            }
        }
        $info->clickInformation($infoID);
        $infos = $info->where('infoID',$infoID)->select();
        $comments = $comment->where('infoID',$infoID)->select();
        $latestInfos = $info->order('infoID desc')->limit(5)->select();
        $users = $user->select();
        $this->assign('infos',$infos);
        $this->assign('latestInfos',$latestInfos);
        $this->assign('comments',$comments);
        $this->assign('users',$users);


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
        $infosh1 = $info->where('infoStatusReason',1)->limit(1)->select();
        $infosh2 = $info->where('infoStatusReason',1)->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->where('infoStatusReason',1)->limit(2,4)->select();
        $infos2 = $info->where('infoStatusReason',1)->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);



        //热门游戏
        $hotgames = $game->where('gameImg',1)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //新游上市
        $newgames = $game->order('gameID desc')->where('gameImg',1)->limit(5)->select();
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
        $inforHot = $info->where('userID',$userID_p)->where('infoStatusReason',1)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //最新发表
        $infoNew = $info->where('userID',$userID_p)->where('infoStatusReason',1)->order('infoDate desc')->limit(2)->select();
        $this->assign('infoNew',$infoNew);

        return view();
    }

    public function personal_bpage()
    {
        $request = Request::instance();
        $userID_p = $request->get('userID');
        $user = new User();
        $info = new Information();

        //鐧婚檰娉ㄩ攢
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

        //鐢ㄦ埛
        $userInfomation = $user->where('userID',$userID_p)->find();
        $userImg_PATH="../../../../public/uploads/userImg/".$userInfomation["userID"].".jpg";
        $this->assign('user',$userInfomation);
        $this->assign('img',$userImg_PATH);

        //鎺ㄨ崘鏂囩珷
        $inforHot = $info->where('userID',$userID_p)->where('infoStatusReason',1)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //鏈�鏂板彂琛�
        $infoNew = $info->where('userID',$userID_p)->where('infoStatusReason',1)->order('infoDate desc')->limit(2)->select();
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
        $infosh1 = $info->where('infoStatusReason',1)->limit(1)->select();
        $infosh2 = $info->where('infoStatusReason',1)->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->where('infoStatusReason',1)->limit(2,4)->select();
        $infos2 = $info->where('infoStatusReason',1)->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);


        //热门游戏
        $hotgames = $game->where('gameImg',3)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //新游上市
        $newgames = $game->where('gameImg',3)->order('gameID desc')->limit(5)->select();
        $this->assign('newgames',$newgames);
        //角色扮演
        $rpggames = $game->where('gameImg',3)->where("gameType = 'RPG'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //动作游戏
        $actgames = $game->where('gameImg',3)->where("gameType = 'ACT'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //竞技游戏
        $mobagames = $game->where('gameImg',3)->where("gameType = 'MOBA'")->limit(5)->select();
        $this->assign('mobagames',$mobagames);
        //休闲游戏
        $fpsgames = $game->where('gameImg',3)->where("gameType = 'FPS'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);

        return view();
    }

    public function mobilegame_index()
    {
        $game = new Game();
        $info = new Information();

        $infosh1 = $info->where('infoStatusReason',1)->limit(1)->select();
        $infosh2 = $info->where('infoStatusReason',1)->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->where('infoStatusReason',1)->limit(2,4)->select();
        $infos2 = $info->where('infoStatusReason',1)->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);



        //热门游戏
        $hotgames = $game->where('gameImg',4)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //新游上市
        $rpggames = $game->where('gameImg',4)->where("gameType = 'RPG' or gameType = 'RST'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //角色扮演
        $rtsgames = $game->where('gameImg',4)->where("gameType = 'RST' or gameType = 'ACT' or gameType = 'SPG'")->limit(5)->select();
        $this->assign('rtsgames',$rtsgames);
        //动作游戏
        $actgames = $game->where('gameImg',4)->where("gameType = 'ACT' or gameType = 'RAC'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //模拟经营
        $racgames = $game->where('gameImg',4)->where("gameType = 'RAC' or gameType = 'FPS' or gameType = 'SPG'")->limit(5)->select();
        $this->assign('racgames',$racgames);
        //策略益智
        $fpsgames = $game->where('gameImg',4)->where("gameType = 'FPS' or gameType = 'AVG' or gameType = 'SPG'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);
        //冒险游戏
        $avggames = $game->where('gameImg',4)->where("gameType = 'AVG' or gameType = 'SPG'")->limit(5)->select();
        $this->assign('avggames',$avggames);
        //桌游棋牌
        $spggames = $game->where('gameImg',4)->where("gameType = 'SPG' or gameType = 'RPG'")->limit(5)->select();

        $this->assign('spggames',$spggames);


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

        $game->clickGame($gameID);

        //游戏讯息
        $gameinfo = $game->where('gameID',$gameID)->select();
        $this->assign('game',$gameinfo);


        //热门攻略
        $inforHot = $info->where("gameID",$gameID)->where('infoStatusReason',1)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //最新发表
        $infoNew = $info->where("gameID",$gameID)->where('infoStatusReason',1)->order('infoDate desc')->limit(5)->select();
        $this->assign('infoNew',$infoNew);

        return view();
    }

    public function singlegame_index()
    {
        $user=new User();
        $request=Request::instance();
        $game = new Game();
        $info = new Information();

        $infosh1 = $info->where('infoStatusReason',1)->limit(1)->select();
        $infosh2 = $info->where('infoStatusReason',1)->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->where('infoStatusReason',1)->limit(2,4)->select();
        $infos2 = $info->where('infoStatusReason',1)->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);




        //热门游戏
        $hotgames = $game->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(1,5)->select();
        $this->assign('hotgames',$hotgames);
        //角色扮演游戏
        $rpggames = $game->where("gameType LIKE '%RPG%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('rpggames',$rpggames);
        //即时战略游戏

        $rtsgames = $game->where("gameType LIKE '%RTS%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('rtsgames',$rtsgames);
        //动作游戏
        $actgames = $game->where("gameType LIKE '%ACT%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('actgames',$actgames);
        //竞速游戏
        $racgames = $game->where("gameType LIKE '%RAC%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('racgames',$racgames);
        //射击游戏
        $fpsgames = $game->where("gameType LIKE '%FPS%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('fpsgames',$fpsgames);
        //冒险游戏
        $avggames = $game->where("gameType LIKE '%AVG%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('avggames',$avggames);
        //体育游戏
        $spggames = $game->where("gameType LIKE '%SPG%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

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

    public function index_user()
    {
        $request = Request::instance();
        $user = new User();
        $userID = $request->cookie('userID');

        //连接主页
        $userInfo = $user->where("userID",$userID)->find();
        $this->assign("user",$userInfo);

        $this->assign("loginTime",date('Y-m-d H:i:s',time()));

        return view();
    }

    public function content_1()
    {
        $request = Request::instance();
        $info = new Information();
        $userID = $request->cookie('userID');
        $game = new Game();

        if($request->has('type','get')){

            $type = $request->get('type');
            $infoID = $info->max("infoID")+1;

            if($type==="add"){
                $gameInfo=$game->where("gameName",$request->post('gameName'))->find();
                $gameID=$gameInfo['gameID'];
                $info->addInformation($infoID, $gameID, $request->post('infoTitle'), $request->cookie('userID'), $request->post('infoKey'), $request->post('infoContent'));

            }else if($type==="change"){

                $info->changeInformation($request->post('infoID'), $request->post('infoTitle'), $request->post('infoKey'), $request->post('infoContent1'));

            }else if($type==="delete"){

                $info->deleteInformation($request->get('infoID'));

            }else if($type==="search"){

                $list=$info->where("infoTitle",$request->post('infoTitle'))->paginate(8);
                $this->assign('list',$list);
                return  view();

            }

        }

        $list = $info->where("userID",$userID)->paginate(8);
        $this->assign('list',$list);

        return  view();
    }

    public function Allgame()
    {
        $game = new Game();

        $hotgames = $game->order('gameClick DESC')->limit(5)->select();
        $newgames = $game->order('gameID DESC')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        $this->assign('newgames',$newgames);

        $list = $game->paginate(8);
        $this->assign('list',$list);
        return view();
    }

    public function news(){
        $user = new User();
        $request = Request::instance();
        if($request->has("userID","cookie")){
            $userID=$request->cookie("userID");
            $userInfo=$user->where("userID",$userID)->find();
            $this->assign("userInfo",$userInfo);
        }
        $info = new Information();
        $list = $info->paginate(5);
        $this->assign('list',$list);
        return view();
    }
}
