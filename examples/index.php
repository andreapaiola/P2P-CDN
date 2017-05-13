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

    <title>WebTorrent CDN Examples (PHP)</title>

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

<h1>WebTorrent CDN Examples (PHP)</h1>
<p>See the browser console :)</p>
<?php
$p2pCdn = new P2p_Cdn();
$file1=$p2pCdn->file('IMG_20170218_122147549.jpg');
?>

<?php echo $p2pCdn->render(array('file1'=>$file1),'<a href="{file1}" target="_blank"><img src="{file1}" alt="Photo" /></a>'); ?>


<h2>Output multiple times the same file</h2>

<?php for($i=0;$i<13;$i++): ?>
    <p><?php echo $p2pCdn->render(array('image'=>$file1),'<img src="{image}" alt="Photo" />'); ?></p>
<?php endfor; ?>

<h2>A more complex output with multiple files</h2>

<?php $file2=$p2pCdn->file('IMG_20170311_092817396.jpg'); ?>
<?php if( $file1 && $file2 ): ?>
<?php $html='
<picture>
    <source media="(min-width: 40em)"
            srcset="{img1} 1x, {img2} 2x">
    <source
            srcset="{img2} 1x, {img1} 2x">
    <img src="{img1}" alt="Photo">
</picture>
<img src="{img1}"
     srcset="{img2} 1024w, {img1} 640w, {img1} 320w"
     sizes="(min-width: 36em) 33.3vw, 100vw"
     alt="Photo">
';
?>

<?php echo $p2pCdn->render(array('img1'=>$file1,'img2'=>$file2),$html); ?>
<?php endif; ?>

<h2>A video with preview</h2>

<?php $file3=$p2pCdn->file('test.mp4'); ?>
<?php $html='<video src="{video}" autoplay poster="{posterImg}">
Sorry, your browser doesnt support embedded videos, 
but dont worry, you can <a href="{video}">download it</a>
and watch it with your favorite video player!
</video>'; ?>

<?php echo $p2pCdn->render(array('posterImg'=>$file1,'video'=>$file3),$html); ?>


<?php $pdf=$p2pCdn->file('codice-libero.pdf'); ?>

<h2>Formatting a link to a PDF...</h2>

<?php
function formatFileSize($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
?>

<?php echo $p2pCdn->render(array('PDF'=>$pdf),'<a href="{PDF}" target="_blank">
Codice Libero ('.formatFileSize($pdf['size']).') - last modified on '.date('l jS \of F Y h:i:s A',$pdf['lastmod']).'</a>'); ?>


<h2>Audio with autoplay</h2>
<?php $audio=$p2pCdn->file('AudioTest.ogg'); ?>

<?php echo $p2pCdn->render(array('audio'=>$audio),'<audio controls="controls" autoplay>
  Il tuo browser non supporta l\'elemento <code>audio</code>.
  <source src="{audio}" type="audio/ogg">
</audio>'); ?>



<script src="webtorrent.min.js"></script>
<script>

</script>
<script src="../P2p-Cdn.js"></script>


</body>
</html>
