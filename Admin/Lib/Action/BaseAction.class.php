<?php
class BaseAction extends Action
{
	public function _initialize()
	{
		//echo "test";
		unset($token);
		$token=$_SESSION["utoken"];

		if(isset($token)==false)
		{

			$this->error("未登录或超时，请重新登录","./Auth/login");
		}
	}
}

?>