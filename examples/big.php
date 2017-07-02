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

<h1>WebTorrent CDN Examples (PHP) BIG FILE</h1>
<p>See the browser console :)</p>
<?php
/* Define the common endpoint */
$p2pCdn = new P2p_Cdn((isset($_SERVER['HTTPS']) ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1).'t.php?file=');

$file1=$p2pCdn->file('ubuntu-17.04-desktop-amd64.iso');

/* Ubuntu http://ba.mirror.garr.it/mirrors/ubuntu-releases/17.04/ubuntu-17.04-desktop-amd64.iso.torrent */

?>

<?php echo $p2pCdn->render(array('file1'=>$file1),'<a href="{file1}" target="_blank">BIG FILE ubuntu-17.04-desktop-amd64 (not versioned)</a>'); ?>

<script src="webtorrent.min.js"></script>
<script src="../P2p-Cdn.js"></script>
<script>
    // Custom endpoint
    // Look at the scope of the variable!
    var P2PCDNEndpoint = '<?php echo $p2pCdn->getEndpoint(); ?>';
</script>

</body>
</html>
