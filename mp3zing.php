<?php
 
### Tác giả: Jkey C Phong
### Vui lòng giữ lại header này khi re-up lên các site khác!
 
Class FPTPlay
{
        Private $__Link = 'http://fptplay.net/show/getlink';
 
        Private Static Function __Curl($Url, $PostData)
        {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Url);
                $Headers   = array();
                $Headers[] = 'Host: fptplay.net';
                $Headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0';
                $Headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
                $Headers[] = 'Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3';
                $Headers[] = 'Accept-Encoding: gzip, deflate';
                $Headers[] = 'DNT: 1';
                $Headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
                $Headers[] = 'X-Requested-With: XMLHttpRequest';
                $Headers[] = 'Referer: ' . $Url;
                $Headers[] = 'Conten-Length: ' . strlen($PostData);
                $Headers[] = 'Connection: keep-alive';
                $Headers[] = 'Pragma: no-cache';
                $Headers[] = 'Cache-Control: no-cache';
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $PostData);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_TIMEOUT, 400);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                $Data = curl_exec($ch);
                if (!$Data) {
                        die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
                }
                $HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return ($HttpCode >= 200 && $HttpCode < 300) ? $Data : False;
        }
 
        Public Function __GetLink($Url)
        {
                preg_match('#http://fptplay.net/xem-video/(.*?)-([a-z0-9]+).html(.*)#', $Url, $Match);
                $Episode = (!empty($Match[3])) ? explode('-',$Match[3])[1] : 1;
                $Source = FPTPlay::__Curl($this->__Link, 'id=' . $Match[2] . '&type=newchannel&quality=3&episode=' . $Episode . '&mobile=web');
                                        if ($Source == False) {
                                                exit('Lỗi trong quá trình get source');
                                        }
                                        $Js = json_decode($Source, 1);
                $Player = "
                <script src='http://www.yan.vn/scripts/jwplayer.js' type='text/javascript'></script>
                <script type='text/javascript'>jwplayer.key = 'FtQ+ubCVmOF2aj8ALHMi/lGfO4o7Oy7xpKmePA==';</script>
                <div id='flashplayer'>Loading...</div>
                <script type='text/javascript'>
                        var playerInstance = jwplayer('flashplayer');
                        playerInstance.setup({
                                aspectratio: '16:9',
                                controls: true,
                                displaydescription: false,
                                displaytitle: false,
                                mute: false,
                                plugins: {
                                        'http://assets-jpcust.jwpsrv.com/player/6/6124956/ping.js': {'pixel': 'http://content.jwplatform.com/ping.gif'}
                                },
                                primary: 'flash',
                                repeat: true,
                                stagevideo: true,
                                stretching: 'uniform',
                                visualplaylist: true,
                                androidhls: true,
                                width: 1E3,
                                height: 556,
                                ph: 1,
                                flashplayer: 'http://static.thanhnien.com.vn/Jscripts/jwplayerads/jwplayer.flash.swf',
                                playlist: [{
                                        sources: [{
                                        file: '" .$Js['stream']. "',
                                        type: 'video/m3u8'
                                        }]
                                }]
                        });
                </script>";
        return $Player;
        }
}
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Jwplayer</title>
</head>
 
<body>
        <style type='text/css'>
                body {
                        font-family: Arial, sans-serif;
                        background: #777;
                }
                .info {
                        background-color: #eee;
                        border: thin solid #333;
                        border-radius: 3px;
                        padding: 0 5px;
                        text-align: center;
                }
                #flashplayer_wrapper {
                        margin: 40px auto;
                }
        </style>
        <?php
 
        /* Example Get Link FPT Play */
        $Object = New FPTPlay();
        $Source = $Object->__GetLink('http://fptplay.net/xem-video/sep-lon-va-toi-sam-sam-den-roi-thuyet-minh-boss-and-me-55e7b1d917dc13129ea53abc.html');
        Echo $Source;
        /* Example Get Link FPT Play */
       
        ?>
</body>
</html>