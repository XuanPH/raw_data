<?php
    header('Access-Control-Allow-Origin: *'); 
    function get_list_mp3($url)
     {
        $link=str_replace('youtube.com','youtube.com',$url); 
        $content_encode=file_get_contents($link);
        $dom = new DOMDocument();
        @$dom->loadHTML($content_encode);
        $xpath = new DOMXpath($dom);
        $domMP3 = $xpath->query('//a[contains(@class, "yt-uix-tile-link")]');
        $jsonrs_list = [];
        for($i = 0;$i< $domMP3->length;$i++)
        {

            $item = $domMP3->item($i);
            // var_dump(utf8_decode($item->nodeValue)); 
            // print '|| ';
            // print ($item->getAttribute('href'));
            // print '||';
            // var_dump (checkisLinkSong($item->getAttribute('href')));
            // print '<br>';
            if ($item->nodeValue != "" && checkisLinkSong($item->getAttribute('href')))
            {
                // print("link: <a href='http://mp3.zing.vn". $item->getAttribute('href')."'>".$item->getAttribute('title')."</a></br>");
                $jsonrs = array("error"=> 0,"url" =>  'https://youtube.com'.$item->getAttribute('href'), "title" => utf8_decode($item->nodeValue));
                array_push($jsonrs_list,$jsonrs);
            }
        }
        return $jsonrs_list;
        //return json_encode($jsonrt);
     }
      function checkisLinkSong($link){
         return count(split('/',$link)) === 2;
     }
     function getDomFromXpath($element,$query){
         $dom = new DOMDocument();
     }
    $url_filter = 'https://www.youtube.com/results?search_query=tuyen tap mr siro'; 
    if ($_GET["q"] && $_GET["a"])
    {
        if ($_GET["a"] == 'xuandeptraikhoaito')
        {
            $rpe = str_replace(" ","+",$_GET["q"]);
            $url_filter = 'https://www.youtube.com/results?search_query='.$rpe;
            echo json_encode(get_list_mp3($url_filter));
        }
        else {
            echo 'Sai API';
        }
    }else {
        echo 'Sai param';
    }
?>