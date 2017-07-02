<?php

ini_set('display_startup_errors', "1");
ini_set("display_errors", "1");
error_reporting(E_ALL);

if( isset($_GET['file']) && !empty($_GET['file'])){

    $file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING);

    $filterFiles = array();
    function isOk($file){
        if(is_file($file)){

            $ok = array('pdf','ogg','rar','tar','mp4','mp3','m3u','mpeg','mpg','jpeg','jpg','gif','png','bmp','psd','svg','zip','iso','webm','webp','weba','avi','tiff','tif','iso');

            foreach($ok as $ext){
                $ext = '.'.$ext;
                if( substr_compare( $file, $ext, -strlen( $ext ) ) === 0 )
                {
                    return true;
                }
            }
        }
        return false;
    }

    foreach (glob("*.*") as $filename) {
        if(isOk($filename))
        {
            $filterFiles[]=$filename;
        }
    }

    if( in_array($file, $filterFiles) ){

        require_once 'Torrent.php';

        $torrent = new Torrent( $file , array(
            'udp://tracker.openbittorrent.com:80'
            ,'udp://tracker.internetwarriors.net:1337'
            ,'udp://tracker.leechers-paradise.org:6969'
            ,'udp://tracker.coppersurfer.tk:6969'
            ,'udp://exodus.desync.com:6969'
            ,'wss://tracker.btorrent.xyz'
            ,'wss://tracker.openwebtorrent.com'
            ,'wss://tracker.fastcast.nz') );

        $url = str_replace('t.php?file='.$file,$file,(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

        $torrent->httpseeds($url);
        $torrent->url_list(array($url));

        $torrent->send();

    }
}

