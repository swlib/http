<?php
/**
 * Copyright: Swlib
 * Author: Twosee <twose@qq.com>
 * Date: 2018/3/25 下午7:09
 */

namespace Swlib\Http;

class ContentType
{
    const TXT = 'text/plain';
    const HTML = 'text/html';
    const XML = 'application/xml';
    const JSON = 'application/json';
    const CSS = 'text/css';
    const JS = 'text/javascript';
    const URLENCODE = 'application/x-www-form-urlencoded';
    const MULTIPART = 'multipart/form-data';
    const BINARY = 'application/octet-stream';

    public static $Map = [
        'ez' => 'application/andrew-inset',
        'aw' => 'application/applixware',
        'atom' => 'application/atom+xml',
        'atomcat' => 'application/atomcat+xml',
        'atomsvc' => 'application/atomsvc+xml',
        'bdoc' => 'application/bdoc',
        'ccxml' => 'application/ccxml+xml',
        'cdmia' => 'application/cdmi-capability',
        'cdmic' => 'application/cdmi-container',
        'cdmid' => 'application/cdmi-domain',
        'cdmio' => 'application/cdmi-object',
        'cdmiq' => 'application/cdmi-queue',
        'cu' => 'application/cu-seeme',
        'mpd' => 'application/dash+xml',
        'davmount' => 'application/davmount+xml',
        'dbk' => 'application/docbook+xml',
        'dssc' => 'application/dssc+der',
        'xdssc' => 'application/dssc+xml',
        'ecma' => 'application/ecmascript',
        'emma' => 'application/emma+xml',
        'epub' => 'application/epub+zip',
        'exi' => 'application/exi',
        'pfr' => 'application/font-tdpfr',
        'woff' => 'application/font-woff',
        'geojson' => 'application/geo+json',
        'gml' => 'application/gml+xml',
        'gpx' => 'application/gpx+xml',
        'gxf' => 'application/gxf',
        'gz' => 'application/gzip',
        'hjson' => 'application/hjson',
        'stk' => 'application/hyperstudio',
        'ink' => 'application/inkml+xml',
        'inkml' => 'application/inkml+xml',
        'ipfix' => 'application/ipfix',
        'jar' => 'application/java-archive',
        'war' => 'application/java-archive',
        'ear' => 'application/java-archive',
        'ser' => 'application/java-serialized-object',
        'class' => 'application/java-vm',
        'js' => 'application/javascript',
        'mjs' => 'application/javascript',
        'json' => 'application/json',
        'map' => 'application/json',
        'json5' => 'application/json5',
        'jsonml' => 'application/jsonml+json',
        'jsonld' => 'application/ld+json',
        'lostxml' => 'application/lost+xml',
        'hqx' => 'application/mac-binhex40',
        'cpt' => 'application/mac-compactpro',
        'mads' => 'application/mads+xml',
        'webmanifest' => 'application/manifest+json',
        'mrc' => 'application/marc',
        'mrcx' => 'application/marcxml+xml',
        'ma' => 'application/mathematica',
        'nb' => 'application/mathematica',
        'mb' => 'application/mathematica',
        'mathml' => 'application/mathml+xml',
        'mbox' => 'application/mbox',
        'mscml' => 'application/mediaservercontrol+xml',
        'metalink' => 'application/metalink+xml',
        'meta4' => 'application/metalink4+xml',
        'mets' => 'application/mets+xml',
        'mods' => 'application/mods+xml',
        'm21' => 'application/mp21',
        'mp21' => 'application/mp21',
        'mp4s' => 'application/mp4',
        'm4p' => 'application/mp4',
        'doc' => 'application/msword',
        'dot' => 'application/msword',
        'mxf' => 'application/mxf',
        'bin' => 'application/octet-stream',
        'dms' => 'application/octet-stream',
        'lrf' => 'application/octet-stream',
        'mar' => 'application/octet-stream',
        'so' => 'application/octet-stream',
        'dist' => 'application/octet-stream',
        'distz' => 'application/octet-stream',
        'pkg' => 'application/octet-stream',
        'bpk' => 'application/octet-stream',
        'dump' => 'application/octet-stream',
        'elc' => 'application/octet-stream',
        'deploy' => 'application/octet-stream',
        'exe' => 'application/octet-stream',
        'dll' => 'application/octet-stream',
        'deb' => 'application/octet-stream',
        'dmg' => 'application/octet-stream',
        'iso' => 'application/octet-stream',
        'img' => 'application/octet-stream',
        'msi' => 'application/octet-stream',
        'msp' => 'application/octet-stream',
        'msm' => 'application/octet-stream',
        'buffer' => 'application/octet-stream',
        'oda' => 'application/oda',
        'opf' => 'application/oebps-package+xml',
        'ogx' => 'application/ogg',
        'omdoc' => 'application/omdoc+xml',
        'onetoc' => 'application/onenote',
        'onetoc2' => 'application/onenote',
        'onetmp' => 'application/onenote',
        'onepkg' => 'application/onenote',
        'oxps' => 'application/oxps',
        'xer' => 'application/patch-ops-error+xml',
        'pdf' => 'application/pdf',
        'pgp' => 'application/pgp-encrypted',
        'asc' => 'application/pgp-signature',
        'sig' => 'application/pgp-signature',
        'prf' => 'application/pics-rules',
        'p10' => 'application/pkcs10',
        'p7m' => 'application/pkcs7-mime',
        'p7c' => 'application/pkcs7-mime',
        'p7s' => 'application/pkcs7-signature',
        'p8' => 'application/pkcs8',
        'ac' => 'application/pkix-attr-cert',
        'cer' => 'application/pkix-cert',
        'crl' => 'application/pkix-crl',
        'pkipath' => 'application/pkix-pkipath',
        'pki' => 'application/pkixcmp',
        'pls' => 'application/pls+xml',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'pskcxml' => 'application/pskc+xml',
        'raml' => 'application/raml+yaml',
        'rdf' => 'application/rdf+xml',
        'rif' => 'application/reginfo+xml',
        'rnc' => 'application/relax-ng-compact-syntax',
        'rl' => 'application/resource-lists+xml',
        'rld' => 'application/resource-lists-diff+xml',
        'rs' => 'application/rls-services+xml',
        'gbr' => 'application/rpki-ghostbusters',
        'mft' => 'application/rpki-manifest',
        'roa' => 'application/rpki-roa',
        'rsd' => 'application/rsd+xml',
        'rss' => 'application/rss+xml',
        'rtf' => 'application/rtf',
        'sbml' => 'application/sbml+xml',
        'scq' => 'application/scvp-cv-request',
        'scs' => 'application/scvp-cv-response',
        'spq' => 'application/scvp-vp-request',
        'spp' => 'application/scvp-vp-response',
        'sdp' => 'application/sdp',
        'setpay' => 'application/set-payment-initiation',
        'setreg' => 'application/set-registration-initiation',
        'shf' => 'application/shf+xml',
        'smi' => 'application/smil+xml',
        'smil' => 'application/smil+xml',
        'rq' => 'application/sparql-query',
        'srx' => 'application/sparql-results+xml',
        'gram' => 'application/srgs',
        'grxml' => 'application/srgs+xml',
        'sru' => 'application/sru+xml',
        'ssdl' => 'application/ssdl+xml',
        'ssml' => 'application/ssml+xml',
        'tei' => 'application/tei+xml',
        'teicorpus' => 'application/tei+xml',
        'tfi' => 'application/thraud+xml',
        'tsd' => 'application/timestamped-data',
        'vxml' => 'application/voicexml+xml',
        'wasm' => 'application/wasm',
        'wgt' => 'application/widget',
        'hlp' => 'application/winhlp',
        'wsdl' => 'application/wsdl+xml',
        'wspolicy' => 'application/wspolicy+xml',
        'xaml' => 'application/xaml+xml',
        'xdf' => 'application/xcap-diff+xml',
        'xenc' => 'application/xenc+xml',
        'xhtml' => 'application/xhtml+xml',
        'xht' => 'application/xhtml+xml',
        'xml' => 'application/xml',
        'xsl' => 'application/xml',
        'xsd' => 'application/xml',
        'rng' => 'application/xml',
        'dtd' => 'application/xml-dtd',
        'xop' => 'application/xop+xml',
        'xpl' => 'application/xproc+xml',
        'xslt' => 'application/xslt+xml',
        'xspf' => 'application/xspf+xml',
        'mxml' => 'application/xv+xml',
        'xhvml' => 'application/xv+xml',
        'xvml' => 'application/xv+xml',
        'xvm' => 'application/xv+xml',
        'yang' => 'application/yang',
        'yin' => 'application/yin+xml',
        'zip' => 'application/zip',
        '*3gpp' => 'audio/3gpp',
        'adp' => 'audio/adpcm',
        'au' => 'audio/basic',
        'snd' => 'audio/basic',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'kar' => 'audio/midi',
        'rmi' => 'audio/midi',
        '*mp3' => 'audio/mp3',
        'm4a' => 'audio/mp4',
        'mp4a' => 'audio/mp4',
        'mpga' => 'audio/mpeg',
        'mp2' => 'audio/mpeg',
        'mp2a' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'm2a' => 'audio/mpeg',
        'm3a' => 'audio/mpeg',
        'oga' => 'audio/ogg',
        'ogg' => 'audio/ogg',
        'spx' => 'audio/ogg',
        's3m' => 'audio/s3m',
        'sil' => 'audio/silk',
        'wav' => 'audio/wav',
        '*wav' => 'audio/wave',
        'weba' => 'audio/webm',
        'xm' => 'audio/xm',
        'ttc' => 'font/collection',
        'otf' => 'font/otf',
        'ttf' => 'font/ttf',
        '*woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'apng' => 'image/apng',
        'bmp' => 'image/bmp',
        'cgm' => 'image/cgm',
        'g3' => 'image/g3fax',
        'gif' => 'image/gif',
        'ief' => 'image/ief',
        'jp2' => 'image/jp2',
        'jpg2' => 'image/jp2',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'jpm' => 'image/jpm',
        'jpx' => 'image/jpx',
        'jpf' => 'image/jpx',
        'ktx' => 'image/ktx',
        'png' => 'image/png',
        'sgi' => 'image/sgi',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'webp' => 'image/webp',
        'disposition-notification' => 'message/disposition-notification',
        'u8msg' => 'message/global',
        'u8dsn' => 'message/global-delivery-status',
        'u8mdn' => 'message/global-disposition-notification',
        'u8hdr' => 'message/global-headers',
        'eml' => 'message/rfc822',
        'mime' => 'message/rfc822',
        'gltf' => 'model/gltf+json',
        'glb' => 'model/gltf-binary',
        'igs' => 'model/iges',
        'iges' => 'model/iges',
        'msh' => 'model/mesh',
        'mesh' => 'model/mesh',
        'silo' => 'model/mesh',
        'wrl' => 'model/vrml',
        'vrml' => 'model/vrml',
        'x3db' => 'model/x3d+binary',
        'x3dbz' => 'model/x3d+binary',
        'x3dv' => 'model/x3d+vrml',
        'x3dvz' => 'model/x3d+vrml',
        'x3d' => 'model/x3d+xml',
        'x3dz' => 'model/x3d+xml',
        'appcache' => 'text/cache-manifest',
        'manifest' => 'text/cache-manifest',
        'ics' => 'text/calendar',
        'ifb' => 'text/calendar',
        'coffee' => 'text/coffeescript',
        'litcoffee' => 'text/coffeescript',
        'css' => 'text/css',
        'csv' => 'text/csv',
        'html' => 'text/html',
        'htm' => 'text/html',
        'shtml' => 'text/html',
        'jade' => 'text/jade',
        'jsx' => 'text/jsx',
        'less' => 'text/less',
        'markdown' => 'text/markdown',
        'md' => 'text/markdown',
        'mml' => 'text/mathml',
        'n3' => 'text/n3',
        'txt' => 'text/plain',
        'text' => 'text/plain',
        'conf' => 'text/plain',
        'def' => 'text/plain',
        'list' => 'text/plain',
        'log' => 'text/plain',
        'in' => 'text/plain',
        'ini' => 'text/plain',
        'rtx' => 'text/richtext',
        '*rtf' => 'text/rtf',
        'sgml' => 'text/sgml',
        'sgm' => 'text/sgml',
        'shex' => 'text/shex',
        'slim' => 'text/slim',
        'slm' => 'text/slim',
        'stylus' => 'text/stylus',
        'styl' => 'text/stylus',
        'tsv' => 'text/tab-separated-values',
        't' => 'text/troff',
        'tr' => 'text/troff',
        'roff' => 'text/troff',
        'man' => 'text/troff',
        'me' => 'text/troff',
        'ms' => 'text/troff',
        'ttl' => 'text/turtle',
        'uri' => 'text/uri-list',
        'uris' => 'text/uri-list',
        'urls' => 'text/uri-list',
        'vcard' => 'text/vcard',
        'vtt' => 'text/vtt',
        '*xml' => 'text/xml',
        'yaml' => 'text/yaml',
        'yml' => 'text/yaml',
        '3gp' => 'video/3gpp',
        '3gpp' => 'video/3gpp',
        '3g2' => 'video/3gpp2',
        'h261' => 'video/h261',
        'h263' => 'video/h263',
        'h264' => 'video/h264',
        'jpgv' => 'video/jpeg',
        '*jpm' => 'video/jpm',
        'jpgm' => 'video/jpm',
        'mj2' => 'video/mj2',
        'mjp2' => 'video/mj2',
        'ts' => 'video/mp2t',
        'mp4' => 'video/mp4',
        'mp4v' => 'video/mp4',
        'mpg4' => 'video/mp4',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'm1v' => 'video/mpeg',
        'm2v' => 'video/mpeg',
        'ogv' => 'video/ogg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'webm' => 'video/webm'
    ];

    public static $suffixMap = [
        'application/andrew-inset' => [
            'ez',
        ],
        'application/applixware' => [
            'aw',
        ],
        'application/atom+xml' => [
            'atom',
        ],
        'application/atomcat+xml' => [
            'atomcat',
        ],
        'application/atomsvc+xml' => [
            'atomsvc',
        ],
        'application/bdoc' => [
            'bdoc',
        ],
        'application/ccxml+xml' => [
            'ccxml',
        ],
        'application/cdmi-capability' => [
            'cdmia',
        ],
        'application/cdmi-container' => [
            'cdmic',
        ],
        'application/cdmi-domain' => [
            'cdmid',
        ],
        'application/cdmi-object' => [
            'cdmio',
        ],
        'application/cdmi-queue' => [
            'cdmiq',
        ],
        'application/cu-seeme' => [
            'cu',
        ],
        'application/dash+xml' => [
            'mpd',
        ],
        'application/davmount+xml' => [
            'davmount',
        ],
        'application/docbook+xml' => [
            'dbk',
        ],
        'application/dssc+der' => [
            'dssc',
        ],
        'application/dssc+xml' => [
            'xdssc',
        ],
        'application/ecmascript' => [
            'ecma',
        ],
        'application/emma+xml' => [
            'emma',
        ],
        'application/epub+zip' => [
            'epub',
        ],
        'application/exi' => [
            'exi',
        ],
        'application/font-tdpfr' => [
            'pfr',
        ],
        'application/font-woff' => [
            'woff',
        ],
        'application/geo+json' => [
            'geojson',
        ],
        'application/gml+xml' => [
            'gml',
        ],
        'application/gpx+xml' => [
            'gpx',
        ],
        'application/gxf' => [
            'gxf',
        ],
        'application/gzip' => [
            'gz',
        ],
        'application/hjson' => [
            'hjson',
        ],
        'application/hyperstudio' => [
            'stk',
        ],
        'application/inkml+xml' => [
            'ink',
            'inkml',
        ],
        'application/ipfix' => [
            'ipfix',
        ],
        'application/java-archive' => [
            'jar',
            'war',
            'ear',
        ],
        'application/java-serialized-object' => [
            'ser',
        ],
        'application/java-vm' => [
            'class',
        ],
        'application/javascript' => [
            'js',
            'mjs',
        ],
        'application/json' => [
            'json',
            'map',
        ],
        'application/json5' => [
            'json5',
        ],
        'application/jsonml+json' => [
            'jsonml',
        ],
        'application/ld+json' => [
            'jsonld',
        ],
        'application/lost+xml' => [
            'lostxml',
        ],
        'application/mac-binhex40' => [
            'hqx',
        ],
        'application/mac-compactpro' => [
            'cpt',
        ],
        'application/mads+xml' => [
            'mads',
        ],
        'application/manifest+json' => [
            'webmanifest',
        ],
        'application/marc' => [
            'mrc',
        ],
        'application/marcxml+xml' => [
            'mrcx',
        ],
        'application/mathematica' => [
            'ma',
            'nb',
            'mb',
        ],
        'application/mathml+xml' => [
            'mathml',
        ],
        'application/mbox' => [
            'mbox',
        ],
        'application/mediaservercontrol+xml' => [
            'mscml',
        ],
        'application/metalink+xml' => [
            'metalink',
        ],
        'application/metalink4+xml' => [
            'meta4',
        ],
        'application/mets+xml' => [
            'mets',
        ],
        'application/mods+xml' => [
            'mods',
        ],
        'application/mp21' => [
            'm21',
            'mp21',
        ],
        'application/mp4' => [
            'mp4s',
            'm4p',
        ],
        'application/msword' => [
            'doc',
            'dot',
        ],
        'application/mxf' => [
            'mxf',
        ],
        'application/octet-stream' => [
            'bin',
            'dms',
            'lrf',
            'mar',
            'so',
            'dist',
            'distz',
            'pkg',
            'bpk',
            'dump',
            'elc',
            'deploy',
            'exe',
            'dll',
            'deb',
            'dmg',
            'iso',
            'img',
            'msi',
            'msp',
            'msm',
            'buffer',
        ],
        'application/oda' => [
            'oda',
        ],
        'application/oebps-package+xml' => [
            'opf',
        ],
        'application/ogg' => [
            'ogx',
        ],
        'application/omdoc+xml' => [
            'omdoc',
        ],
        'application/onenote' => [
            'onetoc',
            'onetoc2',
            'onetmp',
            'onepkg',
        ],
        'application/oxps' => [
            'oxps',
        ],
        'application/patch-ops-error+xml' => [
            'xer',
        ],
        'application/pdf' => [
            'pdf',
        ],
        'application/pgp-encrypted' => [
            'pgp',
        ],
        'application/pgp-signature' => [
            'asc',
            'sig',
        ],
        'application/pics-rules' => [
            'prf',
        ],
        'application/pkcs10' => [
            'p10',
        ],
        'application/pkcs7-mime' => [
            'p7m',
            'p7c',
        ],
        'application/pkcs7-signature' => [
            'p7s',
        ],
        'application/pkcs8' => [
            'p8',
        ],
        'application/pkix-attr-cert' => [
            'ac',
        ],
        'application/pkix-cert' => [
            'cer',
        ],
        'application/pkix-crl' => [
            'crl',
        ],
        'application/pkix-pkipath' => [
            'pkipath',
        ],
        'application/pkixcmp' => [
            'pki',
        ],
        'application/pls+xml' => [
            'pls',
        ],
        'application/postscript' => [
            'ai',
            'eps',
            'ps',
        ],
        'application/pskc+xml' => [
            'pskcxml',
        ],
        'application/raml+yaml' => [
            'raml',
        ],
        'application/rdf+xml' => [
            'rdf',
        ],
        'application/reginfo+xml' => [
            'rif',
        ],
        'application/relax-ng-compact-syntax' => [
            'rnc',
        ],
        'application/resource-lists+xml' => [
            'rl',
        ],
        'application/resource-lists-diff+xml' => [
            'rld',
        ],
        'application/rls-services+xml' => [
            'rs',
        ],
        'application/rpki-ghostbusters' => [
            'gbr',
        ],
        'application/rpki-manifest' => [
            'mft',
        ],
        'application/rpki-roa' => [
            'roa',
        ],
        'application/rsd+xml' => [
            'rsd',
        ],
        'application/rss+xml' => [
            'rss',
        ],
        'application/rtf' => [
            'rtf',
        ],
        'application/sbml+xml' => [
            'sbml',
        ],
        'application/scvp-cv-request' => [
            'scq',
        ],
        'application/scvp-cv-response' => [
            'scs',
        ],
        'application/scvp-vp-request' => [
            'spq',
        ],
        'application/scvp-vp-response' => [
            'spp',
        ],
        'application/sdp' => [
            'sdp',
        ],
        'application/set-payment-initiation' => [
            'setpay',
        ],
        'application/set-registration-initiation' => [
            'setreg',
        ],
        'application/shf+xml' => [
            'shf',
        ],
        'application/smil+xml' => [
            'smi',
            'smil',
        ],
        'application/sparql-query' => [
            'rq',
        ],
        'application/sparql-results+xml' => [
            'srx',
        ],
        'application/srgs' => [
            'gram',
        ],
        'application/srgs+xml' => [
            'grxml',
        ],
        'application/sru+xml' => [
            'sru',
        ],
        'application/ssdl+xml' => [
            'ssdl',
        ],
        'application/ssml+xml' => [
            'ssml',
        ],
        'application/tei+xml' => [
            'tei',
            'teicorpus',
        ],
        'application/thraud+xml' => [
            'tfi',
        ],
        'application/timestamped-data' => [
            'tsd',
        ],
        'application/voicexml+xml' => [
            'vxml',
        ],
        'application/wasm' => [
            'wasm',
        ],
        'application/widget' => [
            'wgt',
        ],
        'application/winhlp' => [
            'hlp',
        ],
        'application/wsdl+xml' => [
            'wsdl',
        ],
        'application/wspolicy+xml' => [
            'wspolicy',
        ],
        'application/xaml+xml' => [
            'xaml',
        ],
        'application/xcap-diff+xml' => [
            'xdf',
        ],
        'application/xenc+xml' => [
            'xenc',
        ],
        'application/xhtml+xml' => [
            'xhtml',
            'xht',
        ],
        'application/xml' => [
            'xml',
            'xsl',
            'xsd',
            'rng',
        ],
        'application/xml-dtd' => [
            'dtd',
        ],
        'application/xop+xml' => [
            'xop',
        ],
        'application/xproc+xml' => [
            'xpl',
        ],
        'application/xslt+xml' => [
            'xslt',
        ],
        'application/xspf+xml' => [
            'xspf',
        ],
        'application/xv+xml' => [
            'mxml',
            'xhvml',
            'xvml',
            'xvm',
        ],
        'application/yang' => [
            'yang',
        ],
        'application/yin+xml' => [
            'yin',
        ],
        'application/zip' => [
            'zip',
        ],
        'audio/3gpp' => [
            '*3gpp',
        ],
        'audio/adpcm' => [
            'adp',
        ],
        'audio/basic' => [
            'au',
            'snd',
        ],
        'audio/midi' => [
            'mid',
            'midi',
            'kar',
            'rmi',
        ],
        'audio/mp3' => [
            '*mp3',
        ],
        'audio/mp4' => [
            'm4a',
            'mp4a',
        ],
        'audio/mpeg' => [
            'mpga',
            'mp2',
            'mp2a',
            'mp3',
            'm2a',
            'm3a',
        ],
        'audio/ogg' => [
            'oga',
            'ogg',
            'spx',
        ],
        'audio/s3m' => [
            's3m',
        ],
        'audio/silk' => [
            'sil',
        ],
        'audio/wav' => [
            'wav',
        ],
        'audio/wave' => [
            '*wav',
        ],
        'audio/webm' => [
            'weba',
        ],
        'audio/xm' => [
            'xm',
        ],
        'font/collection' => [
            'ttc',
        ],
        'font/otf' => [
            'otf',
        ],
        'font/ttf' => [
            'ttf',
        ],
        'font/woff' => [
            '*woff',
        ],
        'font/woff2' => [
            'woff2',
        ],
        'image/apng' => [
            'apng',
        ],
        'image/bmp' => [
            'bmp',
        ],
        'image/cgm' => [
            'cgm',
        ],
        'image/g3fax' => [
            'g3',
        ],
        'image/gif' => [
            'gif',
        ],
        'image/ief' => [
            'ief',
        ],
        'image/jp2' => [
            'jp2',
            'jpg2',
        ],
        'image/jpeg' => [
            'jpeg',
            'jpg',
            'jpe',
        ],
        'image/jpm' => [
            'jpm',
        ],
        'image/jpx' => [
            'jpx',
            'jpf',
        ],
        'image/ktx' => [
            'ktx',
        ],
        'image/png' => [
            'png',
        ],
        'image/sgi' => [
            'sgi',
        ],
        'image/svg+xml' => [
            'svg',
            'svgz',
        ],
        'image/tiff' => [
            'tiff',
            'tif',
        ],
        'image/webp' => [
            'webp',
        ],
        'message/disposition-notification' => [
            'disposition-notification',
        ],
        'message/global' => [
            'u8msg',
        ],
        'message/global-delivery-status' => [
            'u8dsn',
        ],
        'message/global-disposition-notification' => [
            'u8mdn',
        ],
        'message/global-headers' => [
            'u8hdr',
        ],
        'message/rfc822' => [
            'eml',
            'mime',
        ],
        'model/gltf+json' => [
            'gltf',
        ],
        'model/gltf-binary' => [
            'glb',
        ],
        'model/iges' => [
            'igs',
            'iges',
        ],
        'model/mesh' => [
            'msh',
            'mesh',
            'silo',
        ],
        'model/vrml' => [
            'wrl',
            'vrml',
        ],
        'model/x3d+binary' => [
            'x3db',
            'x3dbz',
        ],
        'model/x3d+vrml' => [
            'x3dv',
            'x3dvz',
        ],
        'model/x3d+xml' => [
            'x3d',
            'x3dz',
        ],
        'text/cache-manifest' => [
            'appcache',
            'manifest',
        ],
        'text/calendar' => [
            'ics',
            'ifb',
        ],
        'text/coffeescript' => [
            'coffee',
            'litcoffee',
        ],
        'text/css' => [
            'css',
        ],
        'text/csv' => [
            'csv',
        ],
        'text/html' => [
            'html',
            'htm',
            'shtml',
        ],
        'text/jade' => [
            'jade',
        ],
        'text/jsx' => [
            'jsx',
        ],
        'text/less' => [
            'less',
        ],
        'text/markdown' => [
            'markdown',
            'md',
        ],
        'text/mathml' => [
            'mml',
        ],
        'text/n3' => [
            'n3',
        ],
        'text/plain' => [
            'txt',
            'text',
            'conf',
            'def',
            'list',
            'log',
            'in',
            'ini',
        ],
        'text/richtext' => [
            'rtx',
        ],
        'text/rtf' => [
            '*rtf',
        ],
        'text/sgml' => [
            'sgml',
            'sgm',
        ],
        'text/shex' => [
            'shex',
        ],
        'text/slim' => [
            'slim',
            'slm',
        ],
        'text/stylus' => [
            'stylus',
            'styl',
        ],
        'text/tab-separated-values' => [
            'tsv',
        ],
        'text/troff' => [
            't',
            'tr',
            'roff',
            'man',
            'me',
            'ms',
        ],
        'text/turtle' => [
            'ttl',
        ],
        'text/uri-list' => [
            'uri',
            'uris',
            'urls',
        ],
        'text/vcard' => [
            'vcard',
        ],
        'text/vtt' => [
            'vtt',
        ],
        'text/xml' => [
            '*xml',
        ],
        'text/yaml' => [
            'yaml',
            'yml',
        ],
        'video/3gpp' => [
            '3gp',
            '3gpp',
        ],
        'video/3gpp2' => [
            '3g2',
        ],
        'video/h261' => [
            'h261',
        ],
        'video/h263' => [
            'h263',
        ],
        'video/h264' => [
            'h264',
        ],
        'video/jpeg' => [
            'jpgv',
        ],
        'video/jpm' => [
            '*jpm',
            'jpgm',
        ],
        'video/mj2' => [
            'mj2',
            'mjp2',
        ],
        'video/mp2t' => [
            'ts',
        ],
        'video/mp4' => [
            'mp4',
            'mp4v',
            'mpg4',
        ],
        'video/mpeg' => [
            'mpeg',
            'mpg',
            'mpe',
            'm1v',
            'm2v',
        ],
        'video/ogg' => [
            'ogv',
        ],
        'video/quicktime' => [
            'qt',
            'mov',
        ],
        'video/webm' => [
            'webm',
        ],
    ];

    public static function getContentType(string $suffix): string
    {
        return self::$Map[$suffix] ?? '';
    }

    public static function getSuffixes(string $contentType): array
    {
        return self::$suffixMap[$contentType] ?? [];
    }

}