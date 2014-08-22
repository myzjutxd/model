<?php
	class AuthAction extends Action
	{

		private function admin($username,$password) //验证
		{
			$M = new Model("manager");
			$condition["username"]=$username;
			$condition["password"]=$password;
			$condition["islocked"]=0;
			$admin=$M->where($condition)->find();
			//var_dump($admin);
			//echo $password;
			//var_dump($username);
			$id=$admin["id"];
			//exit();
			if($password==$admin["password"])
			{
				$token=md5($admin["username"].time().$password);

				$res["status"]=1;//登录成功
				$res["token"]=$token;
			}
			else
			{
				$res["status"]=0;
			}
			return $res;
		}
		private function logadd($id,$status)//日志记录
		{
			$server=$this->_server();
			$info=new UserinfoModel();
			$info->server=$server;
			//echo $server;
			$admin=new Model("loginlog");
			if($status!=0)//如果登录成功，更新上次登录信息
			{
				$manager = new Model("manager");
				$res=$admin->where("uid=$id and status=1")->order("logid desc")->find();
				$data=$manager->where("id=$id")->find();
				if($res!=null)
				{
					$data["id"]=$id;
					//echo $data["logincount"];
					$data["logincount"]=$data["logincount"]+1;
					//echo $data["logincount"];
					$data["lastloginIP"]=$res["loginIP"];
					$data["lastlogintime"]=$res["logintime"];
					$manager->save($data);
					unset($data);
				}

			}
			$data["uid"]=$id;
			$data["status"]=$status;
			$data["loginOS"]=$info->getSystem();
			$data["loginIP"]=$info->getIp();
			$data["browser"]=$info->getBrowser();
			$admin->add($data);
		}

		public function auth()
		{
			//验证，添加登录记录，更新登录信息
			$data=$this->_post();
			$username=$data["username"];
			$password=$data["password"];
			$password=md5($password);
			$user=new Model("manager");
			//$id = $user->where("username='$username'")->getField("id");

			$res=$this->admin($username,$password);//验证
			$id=$res["id"];
			unset($res["id"]);
			$this->logadd($id,$res["status"]);//登录记录
			if($res["status"]!=0)
			{

				$token=$res["token"];
				session("uid",$id);
				session("utoken",$token);

				unset($data);
				$data["id"]=$id;
				$data["token"]=$token;
				$user->save($data);
				$this->success("登录成功","../System");
			}
			else
			{
				$this->error("用户名或密码错误","./login");


			}
			//echo $token;

		}
		public function login()
		{
			$this->display();
		}
		public function logout()
		{
			session(null);
			$this->success("退出登录成功，跳转到登陆页","./login");
		}

	}
?>