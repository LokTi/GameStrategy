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
                    $this->error('璇峰厛鐧诲綍锛�', 'login');
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
        $infosh1 = $info->limit(1)->select();
        $infosh2 = $info->order('infoClick desc')->limit(1)->select();
        $infos1 = $info->order('infoDate desc')->limit(2,4)->select();
        $infos2 = $info->order('infoClick desc')->limit(6,4)->select();
        $this->assign('infosh1',$infosh1);
        $this->assign('infosh2',$infosh2);
        $this->assign('infos1',$infos1);
        $this->assign('infos2',$infos2);



        //鐑棬娓告垙
        $hotgames = $game->where('gameImg',1)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //鏂版父涓婂競
        $newgames = $game->order('gameID desc')->where('gameImg',1)->limit(5)->select();
        $this->assign('newgames',$newgames);
        //XBOX鐑棬
        $xboxgames = $game->where("gamePlat LIKE '%XBOX%'")->limit(5)->select();
        $this->assign('xboxgames',$xboxgames);
        //PS4鐑棬
        $ps4games = $game->where("gamePlat LIKE '%PS4%'")->limit(5)->select();
        $this->assign('ps4games',$ps4games);
        //Wii娓告垙
        $wiigames = $game->where("gamePlat LIKE '%Wii%'")->limit(5)->select();
        $this->assign('wiigames',$wiigames);
        //NS鎺ㄨ崘
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
        $inforHot = $info->where('userID',$userID_p)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //鏈�鏂板彂琛�
        $infoNew = $info->where('userID',$userID_p)->order('infoDate desc')->limit(2)->select();
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
        $inforHot = $info->where('userID',$userID_p)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //鏈�鏂板彂琛�
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


        //鐑棬娓告垙
        $hotgames = $game->where('gameImg',3)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //鏂版父涓婂競
        $newgames = $game->where('gameImg',3)->order('gameID desc')->limit(5)->select();
        $this->assign('newgames',$newgames);
        //瑙掕壊鎵紨
        $rpggames = $game->where('gameImg',3)->where("gameType = 'RPG'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //鍔ㄤ綔娓告垙
        $actgames = $game->where('gameImg',3)->where("gameType = 'ACT'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //绔炴妧娓告垙
        $mobagames = $game->where('gameImg',3)->where("gameType = 'MOBA'")->limit(5)->select();
        $this->assign('mobagames',$mobagames);
        //浼戦棽娓告垙
        $fpsgames = $game->where('gameImg',3)->where("gameType = 'FPS'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);

        return view();
    }

    public function mobilegame_index()
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


        //鐑棬娓告垙
        $hotgames = $game->where('gameImg',4)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //鏂版父涓婂競
        $rpggames = $game->where('gameImg',4)->where("gameType = 'RPG'")->limit(5)->select();
        $this->assign('rpggames',$rpggames);
        //瑙掕壊鎵紨
        $rtsgames = $game->where('gameImg',4)->where("gameType = 'RST'")->limit(5)->select();
        $this->assign('rtsgames',$rtsgames);
        //鍔ㄤ綔娓告垙
        $actgames = $game->where('gameImg',4)->where("gameType = 'ACT'")->limit(5)->select();
        $this->assign('actgames',$actgames);
        //妯℃嫙缁忚惀
        $racgames = $game->where('gameImg',4)->where("gameType = 'RAC'")->limit(5)->select();
        $this->assign('racgames',$racgames);
        //绛栫暐鐩婃櫤
        $fpsgames = $game->where('gameImg',4)->where("gameType = 'FPS'")->limit(5)->select();
        $this->assign('fpsgames',$fpsgames);
        //鍐掗櫓娓告垙
        $avggames = $game->where('gameImg',4)->where("gameType = 'AVG'")->limit(5)->select();
        $this->assign('avggames',$avggames);
        //妗屾父妫嬬墝
        $spggames = $game->where('gameImg',4)->where("gameType = 'SPG'")->limit(5)->select();
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

        //娓告垙璁伅
        $gameinfo = $game->where('gameID',$gameID)->select();
        $this->assign('game',$gameinfo);

        //鐑棬鏀荤暐
        $inforHot = $info->where("gameID",$gameID)->order('infoClick desc')->limit(5)->select();
        $this->assign('infoHot',$inforHot);

        //鏈�鏂板彂琛�
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



        //鐑棬娓告垙
        $hotgames = $game->where('gameImg',2)->order('gameClick desc')->limit(5)->select();
        $this->assign('hotgames',$hotgames);
        //角色扮演游戏
        $rpggames = $game->where("gameType LIKE '%RPG%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

        $this->assign('rpggames',$rpggames);
        //即时战略游戏
        $rtsgames = $game->where("gameType LIKE '%RST%'")->where('gamePlat','not in',['ANDROID/IOS','ANDROID','IOS'])->limit(5)->select();

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
                     alert('请输入相同的密码');
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
                     alert('请输入密码！');
                    </script>";
                }
            }else{
                echo "<script>
                     alert('请输入用户名！');
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

                $infos=$info->where('infoStatusReason',1)->where("infoTitle",$request->post('infoTitle'))->select();

                $this->assign('infos',$infos);

                return view();

            }

        }

        //文章
        $list = $info->where("userID",$userID)->paginate(8);
        $this->assign('list',$list);

        return  view();
    }
}
