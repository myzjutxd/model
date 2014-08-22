<?php
class UserAction extends  BaseAction {
	
    public function index() {
 
		$data = $this -> _get();
		unset($data["_URL_"]);
		//$page=$this->_get();
		$page=$data["page"];
        if($page==null)
            $page=1;
		$data["page"]=$page;
		$this->page=$page;
		$data["num"]=50;
		if($data["type"]==null) 
			$data["type"]=1;
		unset($M);
        $M = new UserModel();
        
        
        //echo $this->ajaxReturn($data);
        $D = $M->data("get", $data, "Admin", 'list');
     //var_dump($D);//
        $list = json_decode($D, TRUE);
        $num = count($list["user"]);
		$c=ceil($list["count"]/50);
	$this->num=$list["count"];
		$this->c=$c;
		$list=$list["user"]   ;
        for ($i = 0; $i < $num; $i++) {
            if ($list["$i"]["IsShow"] == 1)
                $list["$i"]["IsShow"] = "yes"; 
            else
                $list["$i"]["IsShow"] = "no";

            if ($list["$i"]["IsTop"] == 1)
                $list["$i"]["IsTop"] = "yes";
            else
                $list["$i"]["IsTop"] = "no";

            if ($list["$i"]["IStuijian"] == 1)
                $list["$i"]["IStuijian"] = "yes";
            else
                $list["$i"]["IStuijian"] = "no";
            if ($list["$i"]["IsMediaTop"] == 1)
                $list["$i"]["IsMediaTop"] = "yes";
            else
                $list["$i"]["IsMediaTop"] = "no";
            if ($list["$i"]["check"] == 1)
                $list["$i"]["check"] = "yes";
            else
                $list["$i"]["check"] = "no";
            if ($list["$i"]["Ismodelcardshow"] == 1)
                $list["$i"]["Ismodelcardshow"] = "yes";
            else
                $list["$i"]["Ismodelcardshow"] = "no";


        }
        //$this->ajaxReturn($list);
		//var_dump($list);
		$data["page"]=0;

		$this->pagefirst=http_build_query($data,'',"&");
		$data["page"]=$c;
		$this->pagelast=  http_build_query($data,'',"&");
		$data["page"]=$page-1;
		if($data["page"]<1)
			$data["page"]=1;
		$this->pageprev= http_build_query($data,','&'');
		$data["page"]=$page+1;
		if($data["page"]>$c)
			$data["page"]=$c;
		$this->pagenext= http_build_query($data,','&'');

        $this -> assign("list", $list);
        $this -> display();

    }
	public function action()
	{
		$type=$this->_get("type");
		$data["id"]=$this->_get("id");
		$data["type"]=$type;
		$M = new UserModel();
		//var_dump($data);
		$M->data('post',$data,'Admin','user');
		$this->success("已提交");

	}
//    public function changestate() {
//        $data = $this -> _get();
//
//
//        //echo json_encode($data);
//        $M = new UserModel();
//        $res = $M -> data('get', $data, 'Admin', 'changestate');
//        $this -> success("操作成功");
//
//    }
	public function adminlogin()
	{
		$data['id']=$this->_get("userid");
		$M = new UserModel();
		$info=json_decode($M->data('get',$data,"Admin",'User'),1);
		if ($info != "false") {

			session("id", $info["id"]);
			session("usertype",$info["usertype"]);
			session("token", $info["token"]);
			//echo session("id");
			$this->success("登陆成功", "../../User/index");
		}
	}
	public function delete()
	{
		$data=$this->_post();
		var_dump($data);
	}
    public function test() {
        $data = $this->_get();
        echo json_encode($data);
    }

}
