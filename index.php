<?php

function openDirAndGetContentNames($dir) {
	
	$dir_content = [];
	
	if ( ! is_dir($dir) ) {
		throw new Exception($dir . " não é um diretório");
	}

	$handle = @opendir($dir);
	
	if ( $handle === false ) {
		throw new Exception("Não foi possivel abrir " . $dir);
	}
	
	while ( ($entry = readdir($handle)) !== false ) {

		if ( in_array($entry, [".", ".."]) === false ) {
			
			if ( is_dir($dir."/".$entry) ) {
				$sub_dir_content = array_map(function($each_sub_dir_content) use ($entry) {return $entry . "/" . $each_sub_dir_content;}, openDirAndGetContentNames($dir."/".$entry));
				$dir_content = array_merge($dir_content, $sub_dir_content);
			}
			else {
				$dir_content[] = $entry;
			}
		}
	}

	closedir($handle);
	
	return $dir_content;
}

$config = json_decode(
	file_get_contents(__DIR__."/config.json"),
	true
);

try {

	$path_origin = $config["origin"];

	$dir_files = array_diff(openDirAndGetContentNames($config["origin"]), $config["excludes"]);

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
}
catch (Exception $e) {
	print_r($e->getMessage());
}
