<?php

error_reporting(0); // Set E_ALL for debuging

// load composer autoload before load elFinder autoload If you need composer
require '../vendor/autoload.php';
//require '../vendor/nao-pon/flysystem-google-drive/src/GoogleDriveAdapter.php';
// elFinder autoload
require './autoload.php';
// https://www.dropbox.com/developers/apps
// ===============================================
elFinder::$netDrivers['dropbox2'] = 'Dropbox2';
define('ELFINDER_DROPBOX_APPKEY', 'suj08stcviy2pqc');
define('ELFINDER_DROPBOX_APPSECRET', '96t0zgyb54eh139');
elFinder::$netDrivers['googledrive'] = 'GoogleDrive';
define('ELFINDER_GOOGLEDRIVE_CLIENTID',     '933711622570-lkksj61v1tvf4upucptcbvsd3dln1512.apps.googleusercontent.com');
define('ELFINDER_GOOGLEDRIVE_CLIENTSECRET', 'BqW5OeYafXYpmI1vjHktjkfG');
/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string    $attr    attribute name (read|write|locked|hidden)
 * @param  string    $path    absolute file path
 * @param  string    $data    value of volume option `accessControlData`
 * @param  object    $volume  elFinder volume driver object
 * @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
 * @param  string    $relpath file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}


// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options

$opts = array(
	 'debug' => true,
	'roots' => array(
		// Items volume
		array(
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => '../files/',                 // path to files (REQUIRED)
			'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			'uploadAllow'   => array('all'),// Mimetype `image` and `text/plain` allowed to upload
			'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
		),
		
		array(
			'driver' => 'Dropbox2',
			'path' => '/',
			'access_token' => 'RFS31sZeVSAAAAAAAAAACPXbsEefFC3-kmaRKlPopXAyW34bPedEY9i3raJC5gp0',
		),
		
		/*array(
			'driver' => 'GoogleDrive',
			'client_id' => '933711622570-lkksj61v1tvf4upucptcbvsd3dln1512.apps.googleusercontent.com',
            'client_secret' => 'BqW5OeYafXYpmI1vjHktjkfG',
			'path' => '/',
			'refresh_token' => '1/_tzXzZ5figGCGFlud2PQyfYa6uMZFt9hBf9z64a0NXg'
			)*/
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();