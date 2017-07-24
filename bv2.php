<?php 
header('Access-Control-Allow-Origin: *');  
// header('Access-Control-Allow-Origin: http://mysite1.com', false);
// header('Access-Control-Allow-Origin: http://example.com', false);
// header('Access-Control-Allow-Origin: https://www.mysite2.com', false);
// header('Access-Control-Allow-Origin: http://www.mysite2.com', false);
class xmlreturn {
    public $url;
    public $author;
}
function getSongMP3($url,$isAlbum)
{
    $link=str_replace(' mp3.zing.vn',' m.mp3.zing.vn',$url); 
    $content_encode=file_get_contents($link); 
    $content = gzdecode($content_encode);
    $xml=explode('xml="',$content); 
    $xml=explode('"',$xml[1]); 
    $xml_sub = 'http://mp3.zing.vn'.$xml[0];
    $xml_desub = $xml[0];
    if ($isAlbum)
    {
        $xml_sub = $xml_desub;
    }
   /* $data=file_get_contents($xml_sub);
    print $data;
    preg_match('/"source":"(.*)=?"/U',$data,$link); 
    $url=str_replace('\/','/',$link[1]); 
    print $data;*/
    $jsonrt = array("error" => 0,"url" => $xml_sub, "author" => "Xuan Pham");
    header('Content-type: application/json');
    return json_encode($jsonrt);
}
function _getMp3($link){ 
    $source = _viewSource($link); 
    $xml = explode('&amp;xmlURL=',$source); 
    $xml = explode('&amp;',$xml[1]); 
    $xml = $xml[0]; 
    $sourceXML = _viewSource($xml); 
    $dl = explode('<source><![CDATA[',$sourceXML); 
    $dl = explode(']]></source>',$dl[1]); 
    $dl = $dl[0]; 
    return $dl; 
} 

function _viewSource($url){ 
    $parse_url = parse_url($url); 
    $headers = array("Host: mp3.zing.vn",
                    "Cookie:tuser=0; _znu=1; fuid=50996e0e5b1fe277076f8cefed4da311; SRVID=s65174_8132; _zploc=A1984242600; __mp3sessid=5D5E6614B2A9; adtimaUserId=2000.22f17a8d8d8765d93c96.1499077512785.b7271d3b; BANNER_OFF=; _zmp3=0.4289175017668234; __sessid=1834.2575336417.3484762826.1500518392; ___sessid=5543.2575340126.3484766535.1500518392.2467534993; __zi=2000.22f17a8d8d8765d93c96.1499077512785.b7271d3b; _ga=GA1.2.183300528.1498535280; _gid=GA1.2.1748553486.1500446424; atmpv=1"
    ); 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL,$url); 
    curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  
    curl_setopt($ch, CURLOPT_REFERER, $url); 
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
    curl_setopt($ch, CURLOPT_HEADER, false); 
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch); 
    curl_close($ch); 
    return $result; 
}

if ($_GET["url"] && $_GET["keyapi"] && $_GET["type"])
{
    if ($_GET['keyapi'] == 'xuandeptraikhoaito'){
        $sl = strpos($_GET["url"],'album');
        $isAlbum = false;
        if ($sl !== false)
        {
            $isAlbum = true;
        }
        $loginUrl = $_GET["url"];
        if($_GET["type"] == "1")
        {
             $xml_source = getSongMP3($loginUrl,$isAlbum);
             echo $xml_source;
        }
        if($_GET["type"] == "2")
        {
            $result = _viewSource($loginUrl);
            echo json_encode(($result));
        }
        //print $datad;
        //print_r(getSongMP3($_GET['url']));
    }
    else {
        $jsonrt = array("error" => 1 , "message" => "Sai api");
        print_r(json_encode($jsonrt));
    }
}else {
        $jsonrt = array("error" => 1 , "message" => "Lỗi param");
        print_r(json_encode($jsonrt));
}
//$content = file_get_contents('http://mp3.zing.vn/bai-hat/Doi-Mat-Wanbi-Tuan-Anh/ZWZAOZEW.html');
//$decoded_content = gzdecode($content);
//echo ($decoded_content);
?>