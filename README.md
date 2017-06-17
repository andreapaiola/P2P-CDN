[P2P-CDN](https://github.com/andreapaiola/P2P-CDN) — WebTorrent CDN with graceful degradation
==================================================

How to use it?
--------------------------------------

See examples folder, you can try [here: look that it's an heavy page with images and video (30MB)!](https://andreapaiola.name/P2P-CDN/examples/)


Why?
--------------------------------------

The LAMP hosting is the most popular choice when you want to public on the Web.

It's cheap and it's fast, but if you want to publish heavy contents like high quality photos, audio or videos with many 
users could become a problem. Maybe you want to reduce your hosting bill.

This is an easy and accessible ([lazy load with graceful degradation](https://andreapaiola.name/2015-01-13-lazy-load/)) fix: 
you can stream all you're heavy files (PDFs included, see examples!) with much less bandwidth, sharing the bandwidth
of your users.

Without Javascript or [WebRTC](http://caniuse.com/#search=webrtc) all the contents load normally: it's SEO friendly.


What is WebTorrent?
--------------------------------------

BitTorrent over WebRTC (data channels) to stream in the browser.

See [WebTorrentFAQ](https://webtorrent.io/faq) and [WebTorrent@GitHub](https://github.com/webtorrent/webtorrent).


Generate the torrent with PHP
--------------------------------------

[Torrent.php used in examples by Adrien Gibrat](https://github.com/adriengibrat/torrent-rw/blob/master/Torrent.php)
 or... ?

Submit your suggestion!


Torrent announce trackers
--------------------------------------

Default torrent announce/trackers:

udp://tracker.openbittorrent.com:80
udp://tracker.internetwarriors.net:1337
udp://tracker.leechers-paradise.org:6969
udp://tracker.coppersurfer.tk:6969
udp://exodus.desync.com:6969
wss://tracker.btorrent.xyz
wss://tracker.openwebtorrent.com
wss://tracker.fastcast.nz

You can setup a private tracker with [bittorrent-tracker](https://github.com/webtorrent/bittorrent-tracker) or
what you prefer.

Apache notes
--------------------------------------

Disabled gzip compression for .mp4 files with

SetOutputFilter DEFLATE

SetEnvIfNoCase Request_URI \.mp4$ no-gzip dont-vary

in the .htaccess if you see [this error](https://github.com/webtorrent/webtorrent/issues/1080)

TODO - wish list
--------------------------------------

Configurable torrent trackers - done

Configurable torrent endpoint - done

WebRTC Peer-to-peer in Safari 11 - Test!

composer.json

documentation

check/enforce Drupal 8 / Symfony 2 coding standard
https://www.drupal.org/docs/develop/development-tools/configuring-phpstorm
https://www.drupal.org/docs/develop/standards/coding-standards
https://confluence.jetbrains.com/display/PhpStorm/Drupal+Development+using+PhpStorm#DrupalDevelopmentusingPhpStorm-CoderandPHPCodeSnifferIntegration

Define and expand compatibility

Explore platforms other than LAMP, maybe Node.js?
[webtorrent-hybrid](https://github.com/webtorrent/webtorrent-hybrid)

Tests (PHPUnit? Karma? Jasmine?)

Integration with popular frameworks and CMS like Laravel, Symfony, Wordpress and Drupal, plugins and modules

A logo maybe? :P


### License

MIT. Copyright (c) [Andrea Paiola](https://andreapaiola.name).