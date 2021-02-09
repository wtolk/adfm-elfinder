<?php

namespace Wtolk\AdfmElfinder\Controllers;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

class ElfinderController
{
    public function elfinder()
    {
        require __DIR__.'/../Helpers/elfinder/autoload.php';

        $client = new S3Client([
            'credentials' => [
                'key'    => env('YANDEX_STORAGE_ACCESS_KEY_ID'),
                'secret' => env('YANDEX_STORAGE_SECRET_ACCESS_KEY'),
            ],
            'region' => 'ru-central1',
            'version' => 'latest',
            'endpoint' => 'http://storage.yandexcloud.net/',
            'url' => '/files',

        ]);

        $filesystem = new Filesystem(new AwsS3Adapter($client, env('YANDEX_STORAGE_BUCKET'), env('YANDEX_STORAGE_FOLDER')));


        function access($attr, $path, $data, $volume) {
            return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
                ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
                :  null;                                    // else elFinder decide it itself
        }

        $opts = array(
            'bind' => array(
                'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre ls.pre' => array('Plugin.Normalizer.cmdPreprocess'),
                'ls' => array('Plugin.Normalizer.cmdPostprocess'),
                'upload.presave' => array('Plugin.Normalizer.onUpLoadPreSave')
            ),
            // global configure (optional)
            'plugin' => array(
                'Normalizer' => array(
                    'enable'    => true,
                    'nfc'       => true,
                    'nfkc'      => true,
                    'lowercase' => false,
                    'convmap'   => array()
                )
            ),
            'roots' => array(
//            array(
//                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
//                'path'          => public_path(),                 // path to files (REQUIRED)
//                'URL'           => dirname($_SERVER['PHP_SELF']), // URL to files (REQUIRED)
//                'uploadDeny'    => array(''),                // All Mimetypes not allowed to upload
//                'uploadAllow'   => array(''), // Mimetype `image` and `text/plain` allowed to upload
//                'uploadOrder'   => array(),      // allowed Mimetype `image` and `text/plain` only
//                'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
//                'plugin' => array(
//                    'Normalizer' => array(
//                        'enable'    => true,
//                        'nfc'       => true,
//                        'nfkc'      => true,
//                        'lowercase' => false,
//                        'convmap'   => array()
//                    )
//                )
//            ),
                [
                    'driver' => 'Flysystem',
                    'filesystem' => $filesystem,
                    'URL' => 'http://storage.yandexcloud.net/'.env('YANDEX_STORAGE_BUCKET').'/'.env('YANDEX_STORAGE_FOLDER'),
                ],
            ));

        $connector = new \elFinderConnector(new \elFinder($opts));
        $connector->run();
    }
}


