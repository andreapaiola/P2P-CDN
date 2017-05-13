<?php

/**
 * P2P-CDN
 * =======
 *
 * The LAMP hosting is the most popular choice when you want to public on the Web.
 * It's cheap and it's fast, but if you want to publish heavy contents like high
 * quality photos, audio or videos with many users could become a problem.
 * Maybe you want to reduce your hosting bill.
 * This is an easy and accessible (lazy load with graceful degradation) fix:
 * you can stream all you're heavy files (PDFs included, see examples!) with much
 * less bandwidth, sharing the bandwidth of your users.
 * Without Javascript or WebRTC all the contents load normally: it's SEO friendly.
 *
 * @author      Current authors: Andrea Paiola <andreapaiola@gmail.com>
 *
 * @license     Code and contributions have 'MIT License'
 *              More details: https://github.com/andreapaiola/P2P-CDN/blob/master/LICENSE.txt
 *
 * @link        Homepage:     https://andreapaiola.name
 *              Examples:     https://github.com/andreapaiola/P2P-CDN/blob/master/examples
 *              GitHub Repo:  https://github.com/andreapaiola/P2P-CDN
 */

class P2p_Cdn
{
    public function file($filename)
    {
        if (file_exists($filename) && is_file($filename)) {
            $file = array(
             'size' => filesize($filename)
            , 'lastmod' => filemtime($filename)
            , 'filename' => $filename
            , 'realpath' => realpath($filename)
            , 'basename' => basename($filename)
            , 'dirname' => dirname($filename)
            );
            return $file;
        } else {
            return false;
        }
    }

    public function render($files, $format)
    {
        $html = '<span class="p2p-cdn"';

            if( is_array($files) ) {
                if (is_string($format)) {
                    foreach ($files as $key=>$value){
                        if(is_array($value) && isset($value['filename']))
                        {
                            $value = $value['filename'];
                        }
                        $format = str_replace('{'.$key.'}',$value,$format);
                    }
                    $formatted = $format;
                    $html .= ' data-torrents="';
                    $values = array();
                    foreach ($files as $key=>$value){
                        if(is_array($value) && isset($value['filename']))
                        {
                            $value = $value['filename'];
                        }
                        $values[] = 't.php?file='.$value;
                    }
                    $html .= implode(',',$values);
                    $html .= '"';
                    $html .= '>'; // close p2p-cdn span opening
                    $html .= '<noscript>'; // Lazy load, see https://andreapaiola.name/2015-01-13-lazy-load/
                    $html .= $formatted;
                    $html .= '</noscript>';
                }
                else{
                    return '';
                }
            }
            else{
                return '';
            }

        $html .= '</span>';
        return $html;
    }
}
