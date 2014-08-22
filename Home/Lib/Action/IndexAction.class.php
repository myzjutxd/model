<?php

// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {

    var $model;
    var $M;
    static $price;
    public function _initialize() {
        $this->photourl=C("SERVER_URL");
        $price = C("PRICE");
    //  $this->price;
        if($price==null)
            $this->price=1;
        else
            $this->price=1+$price/100;
        //echo $this->price;
        if(session("id")!=null)
        {
            $u["reg"]="会员中心";
            $u["regurl"]="/User/index";
            $u["login"]="安全退出";
            $u["loginurl"]="/logout";
            
        }
        else
        {
            $u["reg"]="会员注册";
            $u["regurl"]="/reg";
            $u["login"]="会员登录";
            $u["loginurl"]="/login";
        }
        //var_dump($u);
        $M=new Model("help");
        for ($i=1; $i <6 ; $i++) { 
           $help[$i]=$M->where("ClassId=$i and IsTop=1")->select(); # code...
        }
        //var_dump($help);
        unset($M);
        $this->assign('help',$help);
        $this->assign("u",$u);
        $M = new Model("service") ;
        $QQ=$M->where("IsShow=1")->order('SortId asc')->select();
        $this->assign("QQ",$QQ);
        $this->menu();
        $this->ui=".";

    }

    public function menu() {
        $this->M=new UserModel();
        $M = new UserModel();
        
        
        $model = json_decode($M->data("get", "", "Index", 'modeltype'),true);
        //$model = $M->data("get", "", "Index", 'modeltype');
        //echo json
        //var_dump( $model);
        //var_dump($model);
        $this->model = $model;

        $this->assign("model", $model);
        
        $M = new Model("newsclass");
        $helpclass = $M->where("IsShow=1")->select();
        $this->assign("news", $helpclass);
        $M = new Model("friendlink");
        $friendlink=$M->where("IsShow=1")->select();
        $this->assign("friendlink",$friendlink);
        
    }

    public function login() {

        $this->display();
    }

    public function logout() {
        session("id", null);
        session("token", null);
        $this->success("注销成功","/index");
    }

    public function auth() {
        $M = new UserModel();
        $data = $this->_post();

        //$data["password"]=md5($data["password"],TRUE);

        $D = $M->data("post", $data, "User", 'login');
        $data = json_decode($D, 1);
        //var_dump($data);
        if ($data != "false") {

            session("id", $data["id"]);
            session("usertype",$data["usertype"]);
            session("token", $data["token"]);
            //var_dump($data);
            //echo session("id");
            $this->success("登陆成功", "./User/index");
        } else {
            $this->error("用户名或密码错误");
        }
    }

    public function index() {
        //layout(true);

        $M = new Model("banner");
        $banner = $M->where("IsShow=1")->select();
        for($i=0;$i<4;$i++)
        {
            if($banner[$i]==null || $banner[$i]["LogoUrl"]==null)
            {
                $banner[$i]["display"]="none";

            }
            else
            {
                $banner[$i]["display"]="block"; 
            }
        }
        $this->assign("banner", $banner);
        unset($M);
        $M = new Model("video");
        $this->video = $M->where("IsShow=1")->find();     

        $M = new Model("focus");
        unset($focus);
        $focus = $M->where("IsShow=1")->order('SortId asc')->getField("LinkName,LinkUrl,LogoUrl"); 
        //var_dump($focus);
        $this->assign("focus", $focus);
        //var_dump($banner);
        unset($M);
        $M = new Model("notice");
        $notice = $M->where("IsShow=1 and IsTop =1")->field("Id,Title")->select();
        $this->assign("noticeindex", $notice);
        $M = new UserModel();
        $data["istop"] = 1;

        $data["modeltype"] = 1;
        $data["num"] = 8;
        $m = json_decode($M->data("get", $data, "Index", 'list'),1);
        //var_dump($m);
        $mlist[1] = $m["user"];
        $tuijian[1] = json_decode($M->data("get", $data, "Index", 'tuijian'), 1); 
        $data["modeltype"] = 2;
        $data["num"] = 4;
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        $mlist[2] = $m["user"];
        $data["modeltype"] = 3;
        $data["num"] = 8;
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        $mlist[3] = $m["user"];
        $tuijian[2] = json_decode($M->data("get", $data, "Index", 'tuijian'), 1);
        $data["modeltype"] = 4;
        $data["num"] = 4;
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        $mlist[4] = $m["user"];
        $data["modeltype"] = 5;
        $data["num"] = 8;
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        $mlist[5] = $m["user"];
        $tuijian[3] = json_decode($M->data("get", $data, "Index", 'tuijian'), 1);
        $data["modeltype"] = 6;
        $data["num"] = 4;
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        $mlist[6] = $m["user"];

        $data["num"] = 20;
        $piclist = json_decode($M->data("get", $data, "Index", 'piclist'), 1);
        // echo $tuijian[2]["headPiC"];
        $this->assign("tuijian", $tuijian);
        //$this->ajaxReturn($tuijian);
        $this->assign("piclist", $piclist);


        $this->assign("mlist", $mlist);


        $this->display();
    }

    public function notice() {
        $this->narticle();
        //$this->locate='当前位置：<a href="index">首页</a> > <a href="notice">网站公告</a> ';
        $condition["Id"] = $this->_get("id");
        $condition["IsShow"] = 1;
        $M = new Model("notice");

        $notice = $M->where($condition)->find();
        $this->assign("notice", $notice);
        $this->display();
    }

    public function article() {

        $this->narticle();
        $id = $this->_get("cid");
        $M = new Model("newsview");
        if (isset($id)) {
            $condition["ClassId"] = $id;
            $condition["IsShow"]="1";
            $article = $M->where($condition)->order("Id desc")->select();
            $this->classname = $article[0]["ClassName"];
            $classname == $article[0]["ClassName"];
            $this->locate = '当前位置：<a href="index">首页</a> > <a href="article">模特学院</a> ><a href="#">' . $classname . '</a>';
        } else {
            $article = $M->where("IsShow=1")->order("Id desc")->select();
            $this->classname = "模特学院";
            $this->locate = '当前位置：<a href="index">首页</a> > <a href="article">模特学院</a>';
        }
        $this->assign("article", $article);
        $this->display();
    }

    public function articleshow() {

        $this->narticle();
        $condition["Id"] = $this->_get("id");
        $M = new Model("newsview");
        $article = $M->where($condition)->find();
        $M->where($condition)->setInc('ViewCount',1);
        $this->locate = '当前位置：首页 > 模特学院 >' . $article["ClassName"];
        unset($condition);
        $condition = $article["ClassId"];
        $articlelist = $M->where($condition)->order("Id desc")->limit(4)->select();

        $this->article = $article;
        $this->assign("articlelist", $articlelist);

        $this->display();
    }

    public function narticle() {
        $M = new Model("newsview");
        $list = $M->where('IsShow=1')->order("Id desc")->limit(10)->select();
        //var_dump($list);
        $this->assign("narticlelist", $list);

    }

    public function help() {

        $M = new Model("helpclass");
        $M2 = new Model("help");
        $list = $M->field("Id,ClassName")->select();
        for ($i = 0; $i < count($list); $i++) {
            $condition["ClassId"] = $list[$i]["Id"];
            $list[$i]["menu"] = $M2->where($condition)->field("Id,Title")->select();
        }
        $id = $this->_get("id");
        $this->help = $M2->where("Id=$id")->find();

        $this->assign("hlist", $list);
        $this->display();
    }

    public function reg() {

        $this->display();
    }

    public function userreg() {
        $data = $this->_post();
        var_dump($data);
        $server = $this->_server();
        $info = new UserinfoModel();
        $info->server = $server;
        $data["regIP"] = $info->getIp();
        $data["Password"] = md5($data["Password"]);
        $M = new UserModel();
        $D = $M->data("post", $data, "User", 'reg');
       // $this->success("注册成功");
        echo $D;
    }

    public function show() {
        $M = new UserModel();

        $D["id"] = $this->_get("userid");
        $data = json_decode($M->data("get", $D, "Index", 'user'), 1);
       
        $data["modelcardurl"]=C("SERVER_URL")."/ModelCard/". $data["modelcardurl"];
        
       // var_dump($data);
        $style=$data["style"];
        $product=$data["product"];
        $style = json_decode($M->data("get", $D, "User", 'style'), 1);
        $product = json_decode($M->data("get", $D, "User", 'product'), 1);
        $i = 0;

        $j = 0;



        $piclist=json_decode($this->getphoto($D["id"]),1);
    //   echo $piclist;
       //var_dump($piclist);
        $cityId=$data["cityId"];
        $M = new Model("city");
        $city=$M->where("id=$cityId")->find();
        if(!isset($city))
        {
          $city=$M->where("sort_id=$cityId")->find();  
        }
        $data["city"]=$city["sort"];


        $this->assign("data", $data);
        $this->assign("style", $style);
       // $this->assign("istyle", $istyle);
        $this->assign("product", $product);
        $this->assign("piclist", $piclist);

        $this->display();
    }
    public function photomore()
    {

    }

    public function getphoto($id)
    {
        $M = new UserModel();

        $D["id"] = $id;
        $page=cookie("page");
        if ($page==null) {
            $page=0;

        }
        else
        {
            $page=$page+1;
        }
        $data = json_decode($M->data("get", $D, "Index", 'user'), 1);
        $style = json_decode($M->data("get", $D, "User", 'style'), 1);
        $D["page"]=$page;
        $D["style"]=cookie("style");
        if($D["style"]==null)
        {
             $D["style"] = $style[0]["Id"];
        }
     
           
        $D["num"] = 10;  

        cookie("page",$page);
    //    echo $D["style"];
     //   var_dump($D);
        $piclist=$M->data("get", $D, "User", 'photolist');

        //var_dump($piclist);
        return $piclist;
            
       
            

    }

    public function model() {
        $M = new UserModel();

        $modeltype = $this->_get("cid");
        if ($modeltype != null)
            $data["modeltype"] = $modeltype;
        $data["num"] = 10;
        $data["page"] = $this->_get("page");
        if ($data["page"] == null)
            $data["page"] = 1;
        $data["type"]="model";
        $m = json_decode($M->data("get", $data, "Index", 'list'), 1);
        // var_dump($m);
        $mlist = $m["user"];

        $c = ceil($m["count"] / $data["num"]);
        $this->c=$c;
        // echo $c;
        $sdata = $data;
        unset($sdata["num"]);
        for ($i = 1; $i <= $c; $i++) {
            $temp = $sdata;
            if ($sdata["page"] == $i)
                $page[$i]["url"] = "#";
            else {
                $temp["page"] = $i;
                $page[$i]["url"] = "./model?" . http_build_query($temp);
            }
        }
        //  var_dump($page);
        $temp["page"]=$sdata["page"];
        if ($sdata["page"] == 1) {
            $page1["first"]["class"] = "class=nolink";
            $page1["first"]["url"] = "#";
        } else {
            $temp = $sdata;
            $temp["page"] = $temp["page"]-1;
            $page1["first"]["url"] = "./model?" . http_build_query($temp);
        }
        if ($sdata["page"] == $c) {
            $page1["last"]["class"] = "class=nolink";
            $page1["last"]["url"] = "#";
        } else {
            $temp = $sdata;
            $temp["page"] = $temp["page"] + 1;
            $page1["last"]["url"] = "./model?" . http_build_query($temp);
        }

        $this->assign("page", $page);
        $this->assign("page1", $page1);
        //$this->ajaxReturn($mlist);
        $this->assign("modellist", $mlist);
        $this->display();
    }

    public function photo() {
        $M = new UserModel();

        $D["userid"] = $this->_get("userid");
        $D["albumid"] = $this->_get("albumid");
        $plist = json_decode($M->data("get", $D, "User", "photo"), 1);
        $this->assign("plist", $plist);
        //echo json_encode($plist);
        $this->display();
    }

    public function search() {
        //$this->price=1;
        $this->pprice=$this->price;
        //echo $this->pprice;
        unset($this->price);
        $sdata["styleid"] = $this->_get("styleid");
        $sdata["ModelTypeid"] = $this->_get("ModelTypeid");
        $sdata["proid"] = $this->_get("proid");
        //$height=  $this->_get("height");
        $sdata["heightStart"] = $this->_get("heightStart");
        $sdata["heightEnd"] = $this->_get("heightEnd");
        $sdata["OrderCountStart"] = $this->_get("OrderCountStart");
        $sdata["OrderCountEnd"] = $this->_get("OrderCountEnd");
        $sdata["NPriceStart"] = $this->_get("NPriceStart");
        $sdata["NPriceEnd"] = $this->_get("NPriceEnd");
        $sdata["WPriceStart"] = $this->_get("WPriceStart");
        $sdata["WPriceEnd"] = $this->_get("WPriceEnd");
        $sdata["NYPriceStart"] = $this->_get("NYPriceStart");
        $sdata["NYPriceEnd"] = $this->_get("NYPriceEnd");
        $sdata["DPriceStart"] = $this->_get("DPriceStart");
        $sdata["DPriceEnd"] = $this->_get("DPriceEnd");
        $sdata["ordername"]=$this->_get("ordername");
        $sdata["ordertype"]=$this->_get("ordertype");
        $sdata["page"] = $this->_get("page");
        //  var_dump($sdata);
        // $OrderCount = $this->_get("OrderCount");
//        list($sdata["OrderCountStart"], $sdata["OrderCountEnd"]) = split("-", $OrderCount);
//        $NPrice = $this->_get("NPrice");
//        list($sdata["NPriceStart"], $sdata["NPriceEnd"]) = split("-", $NPrice);
//        $WPrice = $this->_get("WPrice");
//        list($sdata["WPriceStart"], $sdata["WPriceEnd"]) = split("-", $WPrice);
//        $NYPrice = $this->_get("NYPrice");
//        list($sdata["NYPriceStart"], $sdata["NYPriceEnd"]) = split("-", $NYPrice);
//        $DPrice = $this->_get("DPrice");
//        list($sdata["DPriceStart"], $sdata["DPriceEnd"]) = split("-", $DPrice);
//        $keyword = $this->_get("keyword");
       // $sdata["orderby"] = $this->_get("orderby");


        if ($sdata["page"] == null) {
            $sdata["page"] = 1;
        }



        if ($sdata["OrderCountStart"] == null)
            $sdata["OrderCountStart"] = 0;
        if ($sdata["OrderCountEnd"] == null)
            $sdata["OrderCountEnd"] = 1000000;

        $OrderCount = $sdata["OrderCountStart"] . "-" . $sdata["OrderCountEnd"];



        if ($sdata["heightStart"] == null)
            $sdata["heightStart"] = 0;
        if ($sdata["heightEnd"] == null)
            $sdata["heightEnd"] = 280;
         //$height = $sdata["heightStart"] . "-" . $sdata["heightEnd"];

        if($sdata["ordername"]==null)//排序方式
        {
            $sdata["ordername"]="LoginTime";
            $sdata["ordertype"]="desc";
        }

        $pricetext=array("NPrice","WPrice","NYPrice","DPrice");
        //$pdata=$sdata;
        for($i=0;$i<4;$i++)
        {
            if ($sdata[$pricetext[$i]."Start"] == null)
                $sdata[$pricetext[$i]."Start"] = 0;
            if ($sdata[$pricetext[$i]."End"] == null)
                $sdata[$pricetext[$i]."End"] = 1000000;
            //$pricerange[$i] = $sdata[$pricetext."Start"] . "-" . $sdata[$pricetext."End"];
        }
        //var_dump($sdata);

        $M = new UserModel();
        $data = json_decode($M->data("get", $sdata, "Index", "search"), 1);
        $ulist = $data["user"];
        // var_dump($ulist);
        $this->count = $data["count"];
        $this->assign("ulist", $ulist);
        $count = $data["count"];
        //  echo $count;
        //var_dump($ulist);
        $ordertext=array("viewcount","DayPrice","OrderCount","LoginTime");

        for($i=0;$i<4;$i++)
        {
            $temp=$sdata;
            $temp["ordername"]=$ordertext[$i];
            if($sdata["ordername"]==$ordertext[$i])
            {
                $orderby[$i]["class"]=$sdata["ordertype"]." on";
                if($sdata["ordertype"]=="desc")
                    $temp["ordertype"]="asc";
                else
                    $temp["ordertype"]="desc";
            }
            else
            {
                $temp["ordertype"]="desc";
                $orderby[$i]["class"]="desc";
            }

            $orderby[$i]["url"]=http_build_query($temp,'','&');//有问题，可以简化
        }
        $this->assign("orderby",$orderby);
        $mcount = count($this->model);

        for ($i = 0; $i < $mcount; $i++) {
            $temp = $sdata;
            if ($sdata["ModelTypeid"] == ($i + 1)) {
                $this->model[$i]["class"] = "class=on";
                unset($temp["ModelTypeid"]);
            } else {

                $temp["ModelTypeid"] = $i + 1;
            }
            $this->model[$i]["url"] = http_build_query($temp, '', '&');
            $this->assign("model", $this->model);
        }
        $style = json_decode($M->data('get', "", "Index", "styleslist"), 1);
        $mcount = count($style);

        for ($i = 0; $i < $mcount; $i++) {
            $temp = $sdata;
            if ($sdata["styleid"] == $style[$i]["Id"]) {
                $style[$i]["class"] = "class=on";
                unset($temp["styleid"]);
            } else {

                $temp["styleid"] = $style[$i]["Id"];
            }
            $style[$i]["url"] = http_build_query($temp, '', '&');
            $this->assign("style", $style);
        }
        $product = json_decode($M->data("get", "", "Index", "productlist"), 1);
        $mcount = count($product);

        for ($i = 0; $i < $mcount; $i++) {
            $temp = $sdata;
            if ($sdata["proid"] == $product[$i]["Id"]) {
                $product[$i]["class"] = "class=on";
                unset($temp["proid"]);
            } else {

                $temp["proid"] = $product[$i]["Id"];
            }
            $product[$i]["url"] = http_build_query($temp, '', '&');
            $this->assign("product", $product);
        }

        $sorder = array(array("0", "5"), array("6", "10"), array("11", "50"), array("50", "1000000"));
        // var_dump($sorder);
        for ($i = 0; $i < 4; $i++) {
            $temp = $sdata;
            if ($sdata["OrderCountStart"] == $sorder[$i][0] && $sdata["OrderCountEnd"] == $sorder[$i][1]) {
                unset($temp["OrderCountStart"]);
                unset($temp["OrderCountEnd"]);
                $order[$i]["class"] = "class=on";
            } else {
                $temp["OrderCountStart"] = $sorder[$i][0];
                $temp["OrderCountEnd"] = $sorder[$i][1];
            }
            $order[$i]["url"] = http_build_query($temp);
            $this->assign("order", $order);
        }


        $between = array(array("0", "165"), array("160", "165"), array("165", "170"), array("170", "175"), array("175", "1000000"));
        //var_dump($temp);
        for ($i = 0; $i < 5; $i++) {
            $temp =$sdata;
            unset($temp["heightStart"]);
            unset($temp["heightEnd"]);
            $height["geturl"] = http_build_query($temp);
            if ($sdata["heightStart"] == $between[$i][0] && $sdata["heightEnd"] == $between[$i][1]) {


                $height[$i]["class"] = "class=on";
            } else {

                $temp["heightStart"] = $between[$i][0];
                $temp["heightEnd"] = $between[$i][1];
            }
            //   var_dump($temp);
            $height[$i]["url"] = http_build_query($temp);

            // var_dump($height);
            $this->assign("height", $height);
        }

        $between = array(array("0", "30"), array("30", "50"), array("50", "80"), array("80", "120"), array("80", "100"), array("120", "1000000"));
        //$between=array(array("0","30"),array("30","50"),array("50","80"),array("80","120"),array("120","1000000"));
        //var_dump($temp);
        for ($n = 0; $n < 3; $n++) {
            switch ($n) {
                case 0:
                    $name = "NPrice";
                    break;
                case 1:
                    $name = "WPrice";
                    break;
                case 2:
                    $name = "NYPrice";
                    break;
            }
            //  var_dump($sdata);
            for ($i = 0; $i < 6; $i++) {
                $temp = $sdata;
                unset($temp[$name . "Start"]);
                unset($temp[$name . "End"]);
                $price[$n]["geturl"] = http_build_query($temp);
                if ($sdata[$name . "Start"] == $between[$i][0] && $sdata[$name . "End"] == $between[$i][1]) {
                    $price[$n][$i]["class"] = "class=on";
                } else {

                    $temp[$name . "Start"] = $between[$i][0];
                    $temp[$name . "End"] = $between[$i][1];
                }
                //   var_dump($temp);
                $price[$n][$i]["url"] = http_build_query($temp);
                // var_dump($height);
            }
        }
        $n = 3;
        $name = "DPrice";
        $between = array(array("0", "1000"), array("1000", "2000"), array("2000", "3000"), array("3000", "4000"), array("4000", "5000"), array("5000", "1000000"));
        for ($i = 0; $i < 6; $i++) {

            $temp = $sdata;
            unset($temp[$name . "Start"]);
            unset($temp[$name . "End"]);
            $price[$n]["geturl"] = http_build_query($temp);
            if ($sdata[$name . "Start"] == $between[$i][0] && $sdata[$name . "End"] == $between[$i][1]) {
                //     echo "if_$i";

                $price[$n][$i]["class"] = "class=on";
            } else {

                $temp[$name . "Start"] = $between[$i][0];
                $temp[$name . "End"] = $between[$i][1];
            }
            //   var_dump($temp);
            $price[$n][$i]["url"] = http_build_query($temp);
            // var_dump($height);
        }
        $this->assign("price", $price);
        $c = ceil($count / 30);
        // echo $c;
        $this->c=$c;
        for ($i = 1; $i <= $c; $i++) {
            $temp = $sdata;
            if ($sdata["page"] == $i)
                $page[$i]["url"] = "#";
            else {
                $temp["page"] = $i;
                $page[$i]["url"] = "./search?" . http_build_query($temp);
            }
        }
        //  var_dump($page);
        $temp["page"]=$sdata["page"];
        if ($sdata["page"] == 1) {
            $page1["first"]["class"] = "class=nolink";
            $page1["first"]["url"] = "#";
        } else {
            $temp = $sdata;
            $temp["page"] = $temp["page"]-1;
            $page1["first"]["url"] = "./search?" . http_build_query($temp);
        }
        if ($sdata["page"] == $c) {
            $page1["last"]["class"] = "class=nolink";
            $page1["last"]["url"] = "#";
        } else {
            $temp = $sdata;
            $temp["page"] = $temp["page"] + 1;
            $page1["last"]["url"] = "./search?" . http_build_query($temp);
        }

        $this->assign("page", $page);
        $this->assign("page1", $page1);
        $this->display();
//
//
//
//url="?"
//if keyword!=null then 
//  title=title&keyword&"_"
//  url=url&"&keyword="&keyword
//  sqlwhere=sqlwhere & " and (NickName like '%"&keyword&"%' or content   like '%"&keyword&"%')"
//end if
    }
    public function schedule()
    {
        
        $this->id=$this->_get("userid");
        $this->today=date("Y-m-d");
        $this->display();
    }
    public function order()
    {
        $action=$this->_get("action");
        $M = new UserModel();

        // $D["id"] = $this->_get("userid");
        // $data = json_decode($M->data("get", $D, "Index", 'user'), 1);
        if($action=="getorderlist")
        {
            $data["id"]=$this->_get("userid");

           // $data["OrderStart"]=$this->_get("OrderDay");
            $OrderDay=$M->data('get',$data, "Index","orderlist");
            echo $OrderDay; 
        }
         if($action=="getordercount")
        {
             //echo "hello";
            $data["userid"]=$this->_get("userid");
            $data["OrderDay"]=$this->_get("OrderDay");
            $res=json_decode($M->data('get',$data, "Index","order"),1);
            for($i=0;$i<count($data);$i++)
            {
                $html="产品类型:".$res[$i]["PhotoType"]."&nbsp&nbsp&nbsp&nbsp"."数量：".$res[$i]["PhotoCount"]."<br />";
            }
  //  echo $res;
            echo $html;  
        }
        if($action==null)
        {
            
            $usertype=session("usertype");
            if($usertype==2)
            {
                $data["id"]=session("id");
                $user= json_decode($this->M->data('get', $data, "User","user"),1);
                $this->companybalance=$user["balance"];
                $data["id"]=$this->_get("userid");
                $info=  json_decode($this->M->data('get', $data, "User","user"),1);
               // $info["headPiC"]=C("SERVER").$info["headPiC"];
                $this->assign("info",$info);
                $this->display();
            }
            else
            {
                $this->success("您未登陆或不是企业用户，请重新登陆","./login");
            }
        }
        if ($action=="add") {
              $data = $this->_post();
            $M = new UserModel();
            
            $M->data("post", $data, "User", 'order');
            $this->success('添加成功');
            # code...
        }
        
    }
    public function test()
    {
        $M = new UserModel();
        $data = $M->data("get", $data, "Index", 'list');
        for($i=0;$i<3;$i++)
        {
            echo ord($data);
            $data=substr($data, 1);
        }
       // printf("%b",$data);
        //print_r(unpack("c*", $data));
  //      $data = iconv('gb2312','utf-8', $data);
      //  var_dump($data); 
       //$data=substr($data,3);
       //  echo $data;
       // $dedata=json_decode($data,true);
        //var_dump($dedata);

    }

}
