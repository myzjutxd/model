<?php
class UserAction extends Action
{
    var $id;
    var $token;
    var $data = array();
    var $info = array();
    var $M;
    // var $photourl;
    public function _initialize()
    {
        $this->ui="..";
        $this->id = session("id");
        $this->token = session("token");
        if ($this->id == null) {
            $this->error("请先登录", "../login");
            exit();
        }
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
        $this->assign("u",$u);
        $this->menu();

        $this->data["id"] = $this->id;
        $this->data["token"] = $this->token;
        //echo $this->id;
        $M = new UserModel();
        $this->photourl = C("SERVER_URL");
        $D = $M->data("get", $this->data, "User", 'user');
        $this->info = json_decode($D, 1);
        $M = new Model("service") ;
        $QQ=$M->where("IsShow=1")->order('SortId asc')->select();
        $this->assign("QQ",$QQ);
        $this -> assign("info", $this -> info);
        $M=new Model("help");
        for ($i=1; $i <6 ; $i++) { 
           $help[$i]=$M->where("ClassId=$i and IsTop=1")->select(); # code...
        }
        //var_dump($help);
        unset($M);
        $this->assign('help',$help);
    }

    public function menu()
    {

        $M = new Model("modeltype");
        $model = $M->where("IsShow=1")->select();
        $this->assign("model", $model);

        $M = new Model("newsclass");
        $helpclass = $M->where("IsShow=1")->select();
        $this->assign("news", $helpclass);
    }

    public function index()
    {

        $this->data["id"] = $this->id;

        $M = new UserModel();
        $style = json_decode($M->data("get", $this->data, "User", 'style'), 1);
        for ($i = 0; $i < count($style); $i++) {
            $this->info["stylename"] .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $style[$i]["ClassName"];
        }

        $product = json_decode($M->data("get", $this->data, "User",
            'product'), 1);
        for ($i = 0; $i < count($product); $i++) {
            $this->info["product"] .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $style[$i]["ClassName"];
        }
        //echo $D;&nbsp;&nbsp;
        $this->assign("info", $this->info);
        $this->display();

    }

    public function info()
    {

        //echo $this->id;
        $M = new UserModel();

        //$this -> data["id"] = $this -> id;
        ///   $D = $M -> data("get", $this->data, "User", 'user');
        $mlist = json_decode($M->data("get", $this->data, "Index", 'modeltypelist'), 1);
        //	$this->info = json_decode($D,1);
        $this->assign("info", $this->info);
        //echo $this->info["styles"];
        $this->assign("mlist", $mlist);
        $this->display();
    }

    public function album()
    {
        $M = new UserModel();
        $style = json_decode($M->data("get","", "Index", 'styleslist'), 1); 
       // var_dump($style);
        $i = 0;
        $photourl=C("SERVER");
        //$j = 0;
        $D=$this->data;
        for ($i = 0; $i <= count($style); $i++) {
           
            $D["style"] = $style[$i]["Id"];
            //echo $temp;
            $D["num"] = 6;
            $temp = json_decode($M->data("get", $D, "User", 'photolist'), 1); 
            //var_dump($temp);
            if ($temp != null) {
                // $istyle[$j]["ClassName"] = $style[$i]["ClassName"];
                // $istyle[$j]["Id"] = $style[$i]["Id"];
                //$piclist[$j] = $temp;
                $style[$i]["pic"]=$temp;
                unset($temp);
               // $j++;
                
            }

        }
        //var_dump($piclist);
        $this->assign("slist", $style);
        //var_dump($style);
        //$this->assign('piclist',$piclist);
        $this->display();
    }
    public function photoedit()
    {
        $this->aid = $this->_get("albumid");
        $photoid = $this->_get("photoid");
        if ($photoid != null) {
            $data["Id"] = $photoid;
            $M = new UserModel();
            $pdata = json_decode($M->data("get", $data, "User", 'photo'), 1);
            //var_dump($pdata);
            $this->pdata = $pdata[0];
        }
        $this->display();
    }
    public function photo()
    {
        $action = $this->_get("action");
        $M = new UserModel();
        if ($action == "edit") {

            $data = $this->_post();
            $data["userid"] = session("id");
            $M->data("post", $data, "User", 'photo');
            //  var_dump($data);
            $this->success("提交成功");
        }
        if ($action == "delete") {
            $data = $this->_get();
            $pdata["action"] = $action;
            $pdata["userid"] = session("id");

            $pdata["Id"] = $data["photoid"];
            //var_dump($pdata);
            $M->data("post", $pdata, "User", 'photo');
            $this->success("删除成功");
        }


    }
    public function photolist()
    {


        $this->aid = $this->_get("albumid");
        $data["style"] = $this->_get("albumid");
        $data["id"] = $this->id;
        $M = new UserModel();
        $plist = json_decode($M->data("get", $data, "User", 'photolist'), 1);
        $this->assign("plist", $plist);
        $this->display();
    }
    public function comment()
    {
        $this->display();
    }

    public function content()
    {

        $M = new UserModel();
       
        if($this->_post()!=null)
        {
            $data = $this->_post();
            $data['id'] = session("id");
            $res = $M->data("post", $data, "User", 'user');
            $this->info["Content"]=$data["Content"];
        }
        //var_dump($this->info);
        $this->assign('info',$this->info);
        $this->display();
    }

    public function headpic()
    {
        $this->display();
    }

    public function modelcard()
    {
        $this->assign("info", $this->info);
        $this->display();
    }

    public function order()
    {
        $M = new UserModel();
        $olist = json_decode($M->data("get", $this->data, "User", 'order'), 1);
        $this->assign("olist", $olist);
        $this->display();
    }

    public function orderadd()
    {
        $this->assign("info", $this->info);
        $this->display();
    }

    public function password()
    {
        $this->display();
    }

    public function pay()
    {
        $this->display();
    }

    public function tixian()
    {
        $M = new Model("tixian");
        $tlist = $M->select();
        $this->assign("tlist", $tlist);
        $this->assign("info", $this->info);
        $this->display();
    }

    public function update()
    {

        //$this->data["token"]=session("token");
        //$this->ajaxReturn($this->data);
        $action = $this->_get("action");
        // unset($this->data);

        $M = new UserModel();
        if ($action == "user") {
            $data = $this->_post();
            $data['id'] = session("id");
            //data
            //var_dump($this->data);
           //var_dump($data);
            $res = $M->data("post", $data, "User", 'user');
            $this->success("提交成功");
        }
        if ($action == "tixian") {
            $post = $this->_post();
            $this->data["userid"] = session("id");
            $D = json_decode($M->data("get", $this->data, "User", 'user'), 1);
            $balance = $D["balance"];
            if ($post["money"] > $balance) {
                $this->error("余额不足");
            } else {
                $this->data["balance"] = $balance - $post["money"];
                $M->data("post", $this->data, "User", 'user');
                $M = new Model("tixian");
                $post["userid"] = session("id");

                $post["AddTime"] = time("Ymd hms");
                $M->add($post);
            }
        }


    }

    public function ajax()
    {
        //$action = $this->_get("action");
        $D = $this->_get();
        if ($D['action'] == "company") {
            //unset($data);
            $M = new UserModel();
            //$D = $M -> data("post", $data, "User", 'search');
            foreach ($D as $key => $value) {
                if ($key != "action" && $key != "_URL_") {
                    $str = $key . '=' . $value . " ";
                    //echo $str;
                }

            }
            $str .= "and UserType=2";
            //echo $str;
            $str = urlencode($str);
            //echo $str;
            $data["condition"] = $str;
            unset($D);
            $D = $M->data("get", $data, "User", 'search');
            $D = json_decode($D, 1);

            if ($D != null) {
                $str1 = "<option value=0>请选择企业</option> ";
                for ($i = 0; $i < count($D); $i++) {
                    $str1 = $str1 . "<option value=" . $D[$i]["id"] . ">" . $D[$i]["CompanyName"] .
                        "</option>";
                }
                echo $str1;
            } else {
                echo '<option value="0">该区无企业,您可以输入</option>';
            }
        }
        if ($D["action"] == "getcompany") {
            $data["id"] = $this->_get("id");
            //echo $data["id"];
            $M = new UserModel();
            unset($D);
            $D = $M->data("get", $data, "User", 'user');
            $D = json_decode($D, 1);
            $data["Address"] = $D["Address"];
            $data["ProductTypes"] = $D["ProductTypes"];
            $data["CompanyName"] = $D["CompanyName"];
            echo json_encode($data);
        }
    }

    public function addtixian()
    {
        $M = new UserModel();
        $post = $this->_post();
        //$D =json_decode( $M -> data("get", $this -> data, "User", 'user'),1);
        $balance = $this->info["balance"];
        if ($post["money"] > $balance) {
            $this->error("余额不足");
        } else {
            $this->data["balance"] = $balance - $post["money"];
            $M->data("post", $this->data, "User", 'tixian');
            $M = new Model("tixian");
            $post["userid"] = session("id");

            $post["AddTime"] = time("Ymd hms");
            $M->add($post);
        }
    }

    public function addorder()
    {
        $data = $this->_post();
        $M = new UserModel();
        $data["modelid"] = session("id");
        $data["token"] = session("token");
        $M->data("post", $data, "User", 'order');
        $this->success('添加成功');

    }
    public function media()
    {
            $this->assign("info", $this->info);
        $this->display();
    }
    public function upload()
    {


        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array(
                'gif',
                'jpg',
                'jpeg',
                'png',
                'bmp',
                'swf',
                'flv',
                'mp3',
                'wav',
                'wma',
                'wmv',
                'mid',
                'avi',
                'mpg',
                'asf',
                'rm',
                'rmvb'),
            'file' => array(
                'doc',
                'docx',
                'xls',
                'xlsx',
                'ppt',
                'htm',
                'html',
                'txt',
                'zip',
                'rar',
                'gz',
                'bz2'),
            );
        //最大文件大小
        $max_size = 1000000;

        //$save_path = realpath($save_path) . '/';

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch ($_FILES['imgFile']['error']) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                    //case '999' :
                default:
                    $error = '未知错误。';
            }
            $this->alert($error);
        }

        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                $this->alert("请选择文件。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                $this->alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $max_size) {
                $this->alert("上传文件大小超过限制。");
            }

            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //echo json_encode(array($file_ext,$tmp_name));
            //检查扩展名
            if (in_array($file_ext, $ext_arr["image"]) === false) {
                $this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr["image"]) . "格式。");
            }

            $data["type"] = $this->_get("type");

            move_uploaded_file($tmp_name, $tmp_name . ".$file_ext");
            $data["img"] = '@' . $tmp_name . ".$file_ext";
            //$data["file_ext"]=$file_ext;
            // var_dump($data);
            //  $data["type"]="photo";
            // var_dump($data);
            //var_dump($data);
            $M = new UserModel();
            $D = json_decode($M->data('post', $data, 'User', "image"), 1);

            //dump($D);
            if ($D["status"] === 0) {
                alert($D["msg"]);
                //exit;
            }
            echo json_encode(array('error' => 0, 'url' => $D["filename"]));
            //exit ;
        }
    }

    public function alert($msg)
    {

        echo json_encode(array('error' => 1, 'message' => $msg));
        exit;
    }
    public function crop()
    {
        $data=$this->_post();
        $data["id"]=session("id");
       // var_dump($data);
        $M = new UserModel;
        $M->data("post",$data,"User","crop");
        $this->success("上传成功");
        //$data["type"]="photo
        
    }


}
