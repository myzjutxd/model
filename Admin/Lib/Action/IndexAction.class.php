<?php// 本类由系统自动生成，仅供测试用途class IndexAction extends BaseAction {    public function index()    {        $this->success("跳转至管理首页","/system.php/System");    }	public function menu()	{		$M = new Model("modeltype");		$model= $M->where("IsShow=1")->select();		$this->assign("model",$model);		 		$M = new Model("newsclass");		$helpclass= $M->where("IsShow=1")->select();		$this->assign("news",$helpclass);	}    public function index(){    	    	    				$this->display();    }    public function indexshow(){    	//layout(true);    	$this->menu();    	$M = new Model("banner");    	$banner= $M->where("IsShow=1")->getField("LinkName,LinkUrl,LogoUrl");    	$this->assign("banner",$banner);    	unset($M);    	$M=new Model("notice");    	$notice = $M->where("IsShow=1")->field("Id,Title")->select();    	$this->assign("noticeindex",$notice);    	$M = new UserModel();        $data["istop"]=1;                $data["modeltype"]=1;        $data["num"]=8;        $mlist[1] = json_decode($M -> data("get", $data, "Index", 'list'),1);        $tuijian[1] = json_decode($M -> data("get", $data, "Index", 'tuijian'),1);        $data["modeltype"]=2;        $data["num"]=4;        $mlist[2] = json_decode($M -> data("get", $data, "Index", 'list'),1);        $data["modeltype"]=3;        $data["num"]=8;        $mlist[3] = json_decode($M -> data("get", $data, "Index", 'list'),1);        $tuijian[2]=json_decode($M -> data("get", $data, "Index", 'tuijian'),1);        $data["modeltype"]=4;        $data["num"]=4;        $mlist[4] = json_decode($M -> data("get", $data, "Index", 'list'),1);        $data["modeltype"]=5;        $data["num"]=8;        $mlist[5] = json_decode($M -> data("get", $data, "Index", 'list'),1);        $tuijian[3]=json_decode($M -> data("get", $data, "Index", 'tuijian'),1);        $data["modeltype"]=6;        $data["num"]=4;        $mlist[6] = json_decode($M -> data("get", $data, "Index", 'list'),1);                $data["num"]=20;        $piclist = json_decode($M->data("get",$data,"Index",'piclist'),1);       // echo $tuijian[2]["headPiC"];        $this->assign("tuijian",$tuijian);        //$this->ajaxReturn($tuijian);        $this->assign("piclist",$piclist);                        $this->assign("mlist",$mlist);    	    	    	$this->display('indexshow');    }    public function noticeshow()    {    	$this->menu();    	$condition=$this->_get(Id);    	$M = new Model("notice");    	$notice = $M->where($condition)->find();    	$this->assign("notice",$notice);    	$this->display();    }    public function article()    {        $this->menu();        $this->narticle();        $id=$this->_get("cid");        $M = new Model("newsview");        if($id!=null)        {            $condition["ClassId"]=$id;            $article = $M->where($condition)->order("Id desc")->select();            $this->classname=$article[0]["ClassName"];            $classname==$article[0]["ClassName"];            $this->locate='当前位置：<a href="index">首页</a> > <a href="article">模特学院</a> ><a href="#">'.$classname.'</a>';        }        else        {            $article = $M->order("Id desc")->select();            $this->classname="模特学院";            $this->locate='当前位置：<a href="index">首页</a> > <a href="article">模特学院</a>';        }        $this->assign("article",$article);            $this->display();                }    public function articleshow()    {        $this->menu();        $this->narticle();        $condition["Id"]=$this->_get("id");        $M = new Model("newsview");        $article = $M->where($condition)->find();        $this->locate='当前位置：首页 > 模特学院 >'.$article["ClassName"];        unset($condition);        $condition=$article["ClassId"];        $articlelist = $M->where($condition)->order("Id desc")->limit(4)->select();        $this->article=$article;        $this->assign("articlelist",$articlelist);                $this->display();            }    public function narticle()    {         $M = new Model("newsview");         $list = $M->order("Id desc")->limit(10)->select();         $this->assign("narticlelist",$list);    }    public function help()    {        $this->menu();        $M = new Model("helpclass");        $M2 = new Model("help");        $list = $M->field("Id,ClassName")->select();        for($i=0;$i<count($list);$i++)        {            $condition["ClassId"]=$list[$i]["Id"];            $list[$i]["menu"]=$M2->where($condition)->field("Id,Title")->select();        }        $id=$this->_get("id");        $this->help=$M2->where("Id=$id")->find();                $this->assign("hlist",$list);        $this->display();    }    public function reg()    {        $this->menu();        $this->display();            }    public function userreg()    {        $data=$this->_post();        $server=$this->_server();        $info=new UserinfoModel();        $info->server=$server;        $data["regIP"]=$info->getIp();        $data["Password"]=md5($data["Password"]);        $M = new UserModel();        $D = $M -> data("post", $data, "User", 'reg');        echo $D;    }    public function show()    {        $M = new UserModel();        $this->menu();        $D["id"]=$this->_get("userid");        $data = json_decode($M -> data("get", $D, "User", 'user'),1);        $style =  json_decode($M -> data("get", $D, "User", 'style'),1);                for($i=0;$i<count($style);$i++)        {            $D["style"]=$style[$i]["ProClass"];            $D["num"]=5;            $piclist[$i]=json_decode($M -> data("get", $D, "User", 'photolist'),1);        }                $this->assign("data",$data);        $this->assign("style",$style);        $this->assign("piclist",$piclist);        $this->display();               }    public function model()    {        $M = new UserModel();        $this->menu();        $modeltype=$this->_get("cid");        if($modeltype!=null)            $data["modeltype"]=$modeltype;        $data["num"]=10;        $mlist = json_decode($M -> data("get", $data, "Index", 'list'),1);        for($i=0;$i<count($mlist);$i++)        {            $D["id"]=$mlist[$i]['id'];            $mlist[$i]["style"]=json_decode($M -> data("get", $D, "User", 'style'),1);        }        //$this->ajaxReturn($mlist);        $this->assign("modellist",$mlist);        $this->display();    }}