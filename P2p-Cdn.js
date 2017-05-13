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
 * This is ECMAScript 6
 * https://youtrack.jetbrains.com/issue/WI-31003
 *
 * You can transpile with Babel https://babeljs.io/
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


'use strict';
(function (root, p2pCdn) {

    // RequireJS
    if (typeof define === 'function' && define.amd) {
        define(p2pCdn);

        // CommonJS
    } else if (typeof exports === 'object' && typeof module === 'object') {
        module.exports = p2pCdn();

    } else {
        root.p2pCdn = p2pCdn();
    }
})(this, function () {
    'use strict';

    // Do not initialize p2pCdn when running server side, handle it in client:
    if (typeof window !== 'object' || (!WebTorrent.WEBRTC_SUPPORT)) return;

    document.addEventListener("DOMContentLoaded", function (event) {

        var client = new WebTorrent();
        var torrents = [];

        var DOMElements = document.getElementsByClassName('p2p-cdn');

        [].forEach.call(DOMElements, function (el) {

            el.dataset.formatted = el.firstChild.textContent || el.innerHTML;

            if (WebTorrent.WEBRTC_SUPPORT) {

                [].forEach.call(el.dataset.torrents.split(','), function(torrentFileURL) {
                    if (torrents[torrentFileURL]===undefined) {
                        torrents[torrentFileURL] = '';
                        client.add(location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '')+location.pathname+torrentFileURL, {
                            announce: [
                                'udp://tracker.openbittorrent.com:80'
                                ,'udp://tracker.internetwarriors.net:1337'
                                ,'udp://tracker.leechers-paradise.org:6969'
                                ,'udp://tracker.coppersurfer.tk:6969'
                                ,'udp://exodus.desync.com:6969'
                                ,'wss://tracker.btorrent.xyz'
                                ,'wss://tracker.openwebtorrent.com'
                                ,'wss://tracker.fastcast.nz'
                            ]
                        }, function (torrent) {
                            torrent.files[0].getBlobURL(function (err, url) {
                                if (err) throw err;
                                torrents[torrentFileURL] = url;
                                [].forEach.call(DOMElements, function (el) {
                                    var eLTorrents = el.dataset.torrents.split(',');
                                    if( eLTorrents.every(x => torrents[x]!='' ) && el.firstElementChild.tagName.toLowerCase()==='noscript' )
                                    {
                                        var formatted = el.dataset.formatted;
                                        [].forEach.call(eLTorrents, function (elT) {
                                            console.log(elT);
                                            formatted = formatted.replace(new RegExp(elT.replace('t.php?file=',''), "g"), torrents[elT]);
                                        });
                                        el.innerHTML = formatted;
                                    }
                                });
                            });

                            torrent.on('noPeers', function (announceType) {
                                console.log(announceType);
                                [].forEach.call(DOMElements, function (el) {
                                    el.innerHTML = formatted;
                                });
                            });

                            torrent.on('done', function () {
                                console.log('done',torrent.files[0].path);
                            });

                            torrent.on('download', function (bytes) {
                                console.log('download','numPeers: ' + torrent.numPeers,'downloadSpeed: ' + torrent.downloadSpeed);
                            });

                            torrent.on('upload', function (bytes) {
                                console.log('upload','numPeers: ' + torrent.numPeers,'uploadSpeed: ' + torrent.uploadSpeed);
                            });

                        });
                    }
                });
            }
            else {
                el.innerHTML = el.dataset.formatted;
            }
        });
    });
});