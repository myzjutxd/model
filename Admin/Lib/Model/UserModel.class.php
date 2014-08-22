<?php
class UserModel extends Model {
    public function data($fun='get', $data,$class='Admin',$function) {
        $url = C("SERVER_URL")."/";
        $token = C("APP_TOK");
        $url=$url.'/index.php/'.$class.'/'.$function."?"."token=$token";
		//echo $url;
        $ch =curl_init();
        $timeout = 5;
        //curl_setopt($ch, CURLOPT_USERPWD, "test:test");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if($fun=="get")
        {
            $data = http_build_query($data, '', '&');
             curl_setopt($ch, CURLOPT_URL, $url.'&'.$data);
        }
        else {
        	curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        //设置post请求
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        //param为请求的参数
        $file_contents = curl_exec($ch);
        curl_close($ch);
		//var_dump($file_contents);
      //  $file_contents=substr($file_contents,3);
        return $file_contents;

    }
   
   
}
