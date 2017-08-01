<?php

ini_set('display_startup_errors', "1");
ini_set("display_errors", "1");
error_reporting(E_ALL);

require_once '../P2p-Cdn.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>WebTorrent CDN Examples (PHP) BIG FILE</title>

  <link rel="icon" href="data:;base64,iVBORw0KGgo=">

  <style type="text/css">
    html,body{margin: 0;padding:0;}
    html {
      box-sizing: border-box;
    }
    *, *:before, *:after {
      box-sizing: inherit;
    }
    a,img,video,picture,audio{
      display:block;
      max-width:80vw;
      max-height: 80vh;
    }
    textarea{
      width: 80vw;
      height: 10vh;
    }
  </style>

</head>
<body>

<h1>WebTorrent CDN Examples (PHP) with partial content update policy</h1>

<?php
/* Define the common endpoint */
$p2pCdn = new P2p_Cdn((isset($_SERVER['HTTPS']) ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1).'t.php?file=');

//$file1=$p2pCdn->file('IMG_20170218_122147549.jpg');
?>

<h2>A video with preview</h2>
<h3>partial download update example https://github.com/andreapaiola/P2P-CDN/issues/6</h3>
<?php $file3=$p2pCdn->file('test.mp4'); ?>
<?php $html='<video src="{video}" autoplay muted>
Sorry, your browser doesnt support embedded videos, 
but dont worry, you can <a href="{video}">download it</a>
and watch it with your favorite video player!
</video>'; ?>

<?php echo $p2pCdn->render(array('video'=>$file3),$html,true); // updatePartial: true ?>

<script src="webtorrent.min.js"></script>
<script src="../P2p-Cdn.js"></script>
<script>
    // Define torrent trackers, here you can define you're private trackers if you want
    // Look at the scope of the variable!
    var P2PCDNTrackers = [
        'udp://tracker.openbittorrent.com:80'
        ,'udp://tracker.internetwarriors.net:1337'
        ,'wss://tracker.openwebtorrent.com'
        ,'wss://tracker.fastcast.nz'
        ,'udp://tracker.leechers-paradise.org:6969'
    ];
    // Custom endpoint
    // Look at the scope of the variable!
    var P2PCDNEndpoint = '<?php echo $p2pCdn->getEndpoint(); ?>';
</script>

</body>
</html>
