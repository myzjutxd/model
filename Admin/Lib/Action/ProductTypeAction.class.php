<?php
class ProductTypeAction extends BaseAction
{
	public function index()
    {
        $M = new Model("producttype");
        $list=$M->select();
        $num=count($list);
        for($i=0;$i<$num;$i++)
        {
            if($list["$i"]["IsShow"]==1)
                $list["$i"]["show"]="yes";
            else
                $list["$i"]["show"]="no";
        }
        $this->assign("list",$list);
        $this->display();
    }
    public function edit()
    {
        $id=$this->_get("Id");
        if($id==null)
        {
            $this->display();
        }
        else
        {
            $M = new Model("producttype");
            $data = $M->where("Id=$id")->find();
            $this->data=$data;
            $this->display();
            
        }
    }
    public function save()
    {
        $data=$this->_post();
        $M = new Model("producttype");
        if($data["Id"]==null)
        {
            $M->add($data);
        }
        else
        {
            $M->save($data);
        }
        $this->success("修改成功");
    }
    public function delete()
    {
        $id=$this->_get("Id");
        $M = new Model("producttype");
        $M->where("Id=$id")->delete();
        $this->success("删除成功");
    }
    public function IsShow()
    {
        $id=$this->_get("Id");
        if($id!=null)
        {
            $M = new Model("producttype");
            $value=$M->where("Id=$id")->getField("IsShow");
            if($value==1)
                $value=0;
            else
                $value=1;
            $data["IsShow"]=$value;
            $data["Id"]=$id;
            $M->save($data);
            
        }
        $this->success("操作成功");
    }
}
