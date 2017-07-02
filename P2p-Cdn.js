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
 * @author      Current authors: Andrea Paiola <andrea.paiola@gmail.com>
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

        // Define torrent trackers, if you haven't
        if( typeof window.P2PCDNTrackers=="undefined" ){
            var P2PCDNTrackers = [
                'udp://tracker.openbittorrent.com:80'
                ,'udp://tracker.internetwarriors.net:1337'
                ,'udp://tracker.leechers-paradise.org:6969'
                ,'udp://tracker.coppersurfer.tk:6969'
                ,'udp://exodus.desync.com:6969'
                ,'wss://tracker.btorrent.xyz'
                ,'wss://tracker.openwebtorrent.com'
                ,'wss://tracker.fastcast.nz'
            ];
            console.log('Default P2PCDNTrackers',P2PCDNTrackers);
        }
        else{
            var P2PCDNTrackers = window.P2PCDNTrackers;
            console.log('Custom P2PCDNTrackers',P2PCDNTrackers);
        }

        if( typeof window.P2PCDNEndpoint=="undefined" ){
            const P2PCDNEndpoint = '?file=';
            console.log('Default P2PCDNEndpoint',P2PCDNEndpoint);
        }
        else
        {
            const P2PCDNEndpoint = window.P2PCDNEndpoint;
            console.log('Custom P2PCDNEndpoint',P2PCDNEndpoint);
        }

        const client = new WebTorrent();
        const torrents = [];

        const DOMElements = document.getElementsByClassName('p2p-cdn');

        [].forEach.call(DOMElements, function (el) {

            el.dataset.formatted = el.firstChild.textContent || el.innerHTML;

            if (WebTorrent.WEBRTC_SUPPORT) {

                [].forEach.call(el.dataset.torrents.split(','), function(torrentFileURL) {
                    if (torrents[torrentFileURL]===undefined) {
                        torrents[torrentFileURL] = '';
                        client.add(torrentFileURL, {
                            announce: P2PCDNTrackers
                        }, function (torrent) {
                            torrent.files[0].getBlobURL(function (err, url) {
                                if (err) throw err;
                                torrents[torrentFileURL] = url;
                                [].forEach.call(DOMElements, function (el) {
                                    const eLTorrents = el.dataset.torrents.split(',');
                                    if( eLTorrents.every(x => torrents[x]!='' ) && el.firstElementChild.tagName.toLowerCase()==='noscript' )
                                    {
                                        let formatted = el.dataset.formatted;
                                        [].forEach.call(eLTorrents, function (elT) {
                                            console.log(elT);
                                            console.log('P2PCDNEndpoint',P2PCDNEndpoint);
                                            formatted = formatted.replace(new RegExp(elT.replace(P2PCDNEndpoint,''), "g"), torrents[elT]);
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
                                console.log('infoSpeedDownload',torrent.infoSpeedDownload);
                                //console.log('infoSpeedUpload',torrent.infoSpeedUpload);
                                let average = (array) => array.reduce((a, b) => a + b) / array.length;
                                console.log('Average download speed',average(torrent.infoSpeedDownload));
                            });

                            torrent.on('download', function (bytes) {
                                console.log('download','numPeers: '+torrent.numPeers,'downloadSpeed: '+torrent.downloadSpeed+' bytes/sec');
                                if( typeof torrent.infoSpeedDownload=='undefined' ){
                                    torrent.infoSpeedDownload=[];
                                }
                                torrent.infoSpeedDownload.push(torrent.downloadSpeed);
                            });

                            torrent.on('upload', function (bytes) {
                                console.log('upload','numPeers: '+torrent.numPeers,'uploadSpeed: '+torrent.uploadSpeed+' bytes/sec');
                                /*
                                if( typeof torrent.infoSpeedUpload=='undefined' ){
                                    torrent.infoSpeedUpload=[];
                                }
                                torrent.infoSpeedUpload.push(torrent.infoSpeedUpload);
                                */
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