<?php
/**
 * Created by IntelliJ IDEA.
 * User: XD
 * Date: 1/4/14
 * Time: 11:36 PM
 * To change this template use File | Settings | File Templates.
 */
class SetupAction extends  BaseAction
{
	public function index()
	{
		if($this->_post()!=null)
		{
			$config=$this->_post();
			$config["DB_PREFIX"]='';
			$this->saveSetting($config);
			$this->assign("config",$config);
			$this->display();
			unset($config);
			echo '<script>alert("参数设置成功");window.location.href=window.location.href;</script>';
			exit();

		}
		$config=C();

		//var_dump($config);

		$this->assign("config",$config);
		$this->display();
	}
	function saveSetting($config){
		$settingstr = "<?php \n return \n".var_export($config,true)." \n ?>";
		$setfile = "./config.php";
		file_put_contents($setfile,$settingstr); //通过file_put_contents保存
	}
}