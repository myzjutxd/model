<?php
class AdminAction extends BaseAction
{
    public function index()
    {
        $M = new Model("manager");
        $admin = $M->select();
        $num = count($admin);
        for ($i = 0; $i < $num; $i++) {

            if ($admin["$i"]["islocked"] == 1)

                $admin["$i"]["islocked"] = "yes";

            else

                $admin["$i"]["islocked"] = "no";

        }
        $this->assign("admin", $admin);

        $this->display();
    }

    public function islocked()

    {

        $id = $this->_get("id");

        if ($id != null) {

            $M = new Model("manager");

            $value = $M->where("id=$id")->getField("islocked");

            if ($value == 1)

                $value = 0;

            else

                $value = 1;

            $data["islocked"] = $value;

            $data["id"] = $id;

            $M->save($data);


        }

        $this->success("操作成功");

    }
    public function delete()
    {
       $id=$this->_get("id");
       $M = new Model("manager");
       $M->where("id=$id")->delete();
       $this->success("删除管理员成功");
    }
    public function  edit()
    {
        $M = new Model("manager");
        if($this->_post()!=null)
        {
            $admin=$this->_post();
			$admin["updatetime"]=date("Y-m-d h:m:s");

            $this->assign("admin",$admin);
            if($admin["password"]!=$admin["password2"])
            {
                echo "<script>alert('两次密码不相同，请重新输入')</script>";
                $this->display();
                exit();
            }
			$password= $admin["password"];
			$admin["password"]=md5($password);

			unset($admin["password2"]);
			//var_dump($admin);
            if($admin["id"]!=null)
            {
                $M ->save($admin);
            }
            else
            {
                $M->add($admin);
            }

            echo "<script>alert('提交成功')</script>";
            $this->assign("admin",$admin);
            $this->display();
            exit();
        }
        $id=$this->_get("id");
        $admin=$M->where("id=$id")->find();
		$this->assign("admin",$admin);
        $this->display();
    }

}


?>