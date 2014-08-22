<?php

class SystemAction extends BaseAction
{

	public function index()
    {
        $this->display();

    }
    public function loadmenu()
    {
        $id=$this->_get("id");
        $data=new Model("menu");
        $res=$data->field("id,menuname,parentid,linkurl,target,isfolder")->where("parentid=$id and isshow=true")->order("id desc")->select();
        $max=count($res);
        $res["max"]=$max;
        echo json_encode((object)$res);
        //$this->ajaxReturn($res);
    }
    public function loadnav()
    {
        $menuid=$this->_get("id");
        echo "<strong>当前位置</strong>>".$this->findnav($menuid);
		

    }
    public function findnav($menuid)
    {
        
        $data=new Model("menu");
        $res=$data->field('id,menuname,parentid')->where("id=$menuid")->find();
        $id=$res["id"];
        $parentid=$res["parentid"];
        if($res==false)
		{ 
			return "<strong>出现错误!</strong>";
        }
		elseif($parent==0)
		{
            return "<a href='javascript:get_menu($id,tree,0);'>".$res["menuname"]."</a>";
        }
		else
            return $this->findnav($parentid).">"."<a href='javascript:get_menu($id,tree,0);'>".$res["menuname"]."</a>";
        
		
    }
    
    
    public function main()
    {
        $id=session("id");
        
        $user= new Model("manager");
        $data= $user->where("id=$id")->find();
        if($data["role"]==0)
            $data["role"]="超级管理员";
        $this->assign("admin",$data);
        
        $this->display();
        
        
    }
    public function loadmemo()
    {
        
        
        
        $action=$this->_get("action");
        
        $memo=new Model("memo");
        $uid=session("id");
        if($action=="get")
        {
            
            
            
            $res=$memo->where("uid=$uid")->find();
            if($res==null)
            {
                $data="你还未使用过备忘录！";
            }
            else
            {
                $data=$res["memo"];
            }
            
            
            echo $data;
        }
        if($action=="set")
        {
            
            $data["memo"]=$this->_post("data");
            
            $data["uid"]=$uid;
            
            $res=$memo->where("uid=$uid")->find();
            if($res==null)
            {
                $memo->add($data);
            }
            else
            {
                $memo->save($data);
            }
           
        }
    }
    function file()
    {
             $this->display();
    }
    function info()
    {
        phpinfo();
    }
	function update()
	{
		import('ORG.Http');
		import('ORG.PclZip');
		$http = new Http();
		$url = C("SERVER_URL")."/dymtw.zip";
		$http->curlDownload($url,"./dymtw.zip");
		if(is_file("./dymtw"))
			echo '下载成功';
		else
		{
			echo '未有更新';
			exit();
		}
		$zip = new PclZip("./dymtw.zip");
		$zip->extract();
		echo '更新成功';


	}
}