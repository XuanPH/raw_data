<?php
//header('Access-Control-Allow-Origin: *');  
// header('Access-Control-Allow-Origin: http://mysite1.com', false);
// header('Access-Control-Allow-Origin: http://example.com', false);
// header('Access-Control-Allow-Origin: https://www.mysite2.com', false);
// header('Access-Control-Allow-Origin: http://www.mysite2.com', false);
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);        
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
header('Access-Control-Max-Age: 1');
function curl($url) {
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}
function getIdYoutube($link){
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $id);
    if(!empty($id)) {
        return $id = $id[0];
    }
    return $link;
}
function getVideoYoutube($link) {
    $id = getIdYoutube($link);
    $getlink = "https://www.youtube.com/watch?v=".$id;
    if ($get = curl($getlink )) {
        $return = array();
        if (preg_match('/;ytplayer\.config\s*=\s*({.*?});/', $get, $data)) {
            $jsonData  = json_decode($data[1], true);
            $streamMap = $jsonData['args']['url_encoded_fmt_stream_map'];
            foreach (explode(',', $streamMap) as $url)
            {
                $url = str_replace('\u0026', '&', $url);
                $url = urldecode($url);
                parse_str($url, $data);
                $dataURL = $data['url'];
                unset($data['url']);
                $return[$data['quality']."-".$data['itag']] = $dataURL.'&'.urldecode(http_build_query($data));
            }
        }
        return $return;
    }else{
        return 0;
    }
}
// function getipReq(){
//     $myfile = fopen("ipreq.txt", "r") or die("Unable to open file!");
//     $listip = fgets($myfile);
//     fclose($myfile);
//     return explode(';',$listip);
// }
// function getUserIP() {
//     $ipaddress = '';
//     if (isset($_SERVER['HTTP_CLIENT_IP']))
//         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
//     else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     else if(isset($_SERVER['HTTP_X_FORWARDED']))
//         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
//     else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
//         $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
//     else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
//         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
//     else if(isset($_SERVER['HTTP_FORWARDED']))
//         $ipaddress = $_SERVER['HTTP_FORWARDED'];
//     else if(isset($_SERVER['REMOTE_ADDR']))
//         $ipaddress = $_SERVER['REMOTE_ADDR'];
//     else
//         $ipaddress = 'UNKNOWN';
//     return $ipaddress;
// }
// function getpubip() {
//     $ip_pub = file_get_contents("http://bot.whatismyipaddress.com"); 
//     $array_ip = getipReq();
//     return $ip_pub;
//     if (in_array($ip_pub,$array_ip))
//     {
//         return true;
//     }else {
//         return false;
//     }
// }
// if (getpubip())
// {
//     if($_GET['u'])
//     {
//         $out = array_values(getVideoYoutube($_GET['u']));
//         echo json_encode($out);
//     }else {
//         echo 'Không tìm thấy link';
//     }
// }else {
//     echo 'Bạn không có quyền truy cập';
// }
if($_GET['u'])
    {
        $out = array_values(getVideoYoutube($_GET['u']));
        echo json_encode($out);
    }else {
        echo 'Không tìm thấy link';
    }
?>