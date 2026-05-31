<pre>
<?php

use Classes\Dir;
use Classes\File;
use Classes\DirSync;

spl_autoload_register(function($file_name) {
	require str_replace("\\", "/", $file_name) . ".php";
});

$config = json_decode(
	file_get_contents(__DIR__."/config.json"),
	true
);

try {

	$dir_origin = new Dir($config["origin"]);
		$dir_origin->openDirAndSetContent();

	$dir_target = new Dir($config["target"]);

	$dir_sync = new DirSync($dir_origin, $dir_target);
		$dir_sync->syncDirectories();

	die();
	
}
catch (Exception $e) {
	print_r($e->getMessage());
}

echo "*************************************\n";
echo "*************  SUCESSO  *************\n";
echo "*************************************\n";

print_r($dir_origin);

// FIM AQUI
die();

$dir_target = new Dir($config["target"]);

die();

$path_origin = $config["origin"];

$dir_files = array_diff(openDirAndSetContent($config["origin"]), $config["excludes"]);

foreach ($dir_files as $each_file) {
	
	$target_full_path = $config["target"];
	
	$target_path_parts = explode("/", $each_file);
	for ($i = 0; $i < count($target_path_parts) - 1; $i++) {
		
		$target_full_path .= "/" . $target_path_parts[$i];
		if ( !is_dir($target_full_path) ) {
			@mkdir( $target_full_path );
		}	
	}

	$origin_file = fopen($config["origin"] . "/" . $each_file, "r");
	$target_file = fopen($config["target"] . "/" . $each_file, "w");

	while( feof($origin_file) === false ) {
		fwrite($target_file, fread($origin_file, 1024));
	}

	fclose($origin_file);
	fclose($target_file);
}