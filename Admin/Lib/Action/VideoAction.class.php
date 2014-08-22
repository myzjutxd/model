<?php

class VideoAction extends BaseAction

{

	public function index()

    {

        $M = new Model("video");

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

            $M = new Model("video");

            $data = $M->where("Id=$id")->find();

            $this->data=$data;

            $this->display();

            

        }

    }

    public function save()

    {

        $data=$this->_post();

        $M = new Model("video");

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

        $M = new Model("video");

        $M->where("Id=$id")->delete();

        $this->success("删除成功");

    }

    public function IsShow()

    {

        $id=$this->_get("Id");

        if($id!=null)

        {

            $M = new Model("video");

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

	public function update()

	{

		$data=$this->_post();

		$M = new Model("video");

		if($data["Action"]=="update")

		{

			$U["IsShow"]=$data["ChangeState"];

			$D=$data["ListId"];

			$num=count($D);

			for($i=0;$i<$num;$i++)

			{

				$U["Id"]=$D[$i];

				$M->save($U);

			}

			

		}

		if($data["Action"]=="delete")

		{

			$D=$data["ListId"];

			$num=count($D);

			for($i=0;$i<$num;$i++)

			{

				$id=$D[$i];

				$M->where("Id=$id")->delete();

			}

			

			

		}

		//echo $num;

		//echo $U["IsShow"];

		//$this->ajaxReturn($D);

		$this->success("操作成功");

	}

}

