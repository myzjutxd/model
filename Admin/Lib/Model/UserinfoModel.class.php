<?php
class UserinfoModel extends Model 
{

    public $server;
    // --------------------------------------------------
    // 分析返回用户操作系统名称
    // --------------------------------------------------
    function getSystem()
    {
        $sys = $this->server['HTTP_USER_AGENT'];
        if (stripos($sys, "NT 6.1"))
            $os = "Windows 7";
        elseif (stripos($sys, "NT 6.0"))
            $os = "Windows Vista";
        elseif (stripos($sys, "NT 5.1"))
            $os = "Windows XP";
        elseif (stripos($sys, "NT 5.2"))
            $os = "Windows Server 2003";
        elseif (stripos($sys, "NT 5"))
            $os = "Windows 2000";
        elseif (stripos($sys, "NT 4.9"))
            $os = "Windows ME";
        elseif (stripos($sys, "NT 4"))
            $os = "Windows NT 4.0";
        elseif (stripos($sys, "98"))
            $os = "Windows 98";
        elseif (stripos($sys, "95"))
            $os = "Windows 95";
        elseif (stripos($sys, "Mac"))
            $os = "Mac";
        elseif (stripos($sys, "Linux"))
            $os = "Linux";
        elseif (stripos($sys, "Unix"))
            $os = "Unix";
        elseif (stripos($sys, "FreeBSD"))
            $os = "FreeBSD";
        elseif (stripos($sys, "SunOS"))
            $os = "SunOS";
        elseif (stripos($sys, "BeOS"))
            $os = "BeOS";
        elseif (stripos($sys, "OS/2"))
            $os = "OS/2";
        elseif (stripos($sys, "PC"))
            $os = "Macintosh";
        elseif (stripos($sys, "AIX"))
            $os = "AIX";
        else
            $os = "未知操作系统";

        return $os;
    }

    // --------------------------------------------------
    // 分析返回用户网页浏览器名称
    // --------------------------------------------------
    function getBrowser()
    {
        $browser = $this->server['HTTP_USER_AGENT'];
        if (strpos(strtolower($browser), "netcaptor"))
            $exp = "NetCaptor";
        elseif (strpos(strtolower($browser), "firefox")) {
            preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
            $exp = "Mozilla Firefox " . $b[1];
        } elseif (strpos(strtolower($browser), "maxthon")) {
            preg_match("/MAXTHON\s+([^;)]+)+/i", $sys, $b);
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp = $b[0] . " (IE" . $ie[1] . ")";
        } elseif (strpos(strtolower($browser), "msie")) {
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp = "Internet Explorer " . $ie[1];
        } elseif (strpos(strtolower($browser), "netscape"))
            $exp = "Netscape";
        elseif (strpos(strtolower($browser), "opera"))
            $exp = "Opera";
        else
            $exp = "未知浏览器";

        return $exp;
    }

    // --------------------------------------------------
    // 分析返回用户ip
    // --------------------------------------------------
    function getIp()
    {
        $ip = '';
        if ($this->server["HTTP_X_FORWARDED_FOR"]) {
            $ip = $this->server["HTTP_X_FORWARDED_FOR"];
        } elseif ($this->server["HTTP_CLIENT_IP"]) {
            $ip = $this->server["HTTP_CLIENT_IP"];
        } elseif ($this->server["REMOTE_ADDR"]) {
            $ip = $this->server["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "unknown";
        }

        return $ip;
    }
}
?>