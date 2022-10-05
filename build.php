<?php
$srcRoot = "./src";
$buildRoot = "./build";

// Create Phar with sources
$phar = new Phar($buildRoot . "/phpstorm_debug_validator.phar",
                 FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, "phpstorm_debug_validator.phar");
$phar["index.php"] = file_get_contents($srcRoot . "/index.php");
$phar["common.php"] = file_get_contents($srcRoot . "/common.php");
$phar->setStub($phar->createDefaultStub("index.php"));

// Copy helpers to build directory
copy($srcRoot . "/helpers/phpstorm_debug.php", $buildRoot . "/phpstorm_debug.php");
copy($srcRoot . "/helpers/phpstorm_index.php", $buildRoot . "/phpstorm_index.php");

// Zip phpstorm_debug_validator.phar and helpers
$zip = new ZipArchive();
$zip_path= $buildRoot . "/phpstorm_debug_validator.zip";
if ($zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
  die ("An error occurred creating your ZIP file.");
}
$zip->addFile($buildRoot . "/phpstorm_debug.php","phpstorm_debug.php");
$zip->addFile($buildRoot . "/phpstorm_index.php","phpstorm_index.php");
$zip->addFile($buildRoot . "/phpstorm_debug_validator.phar","phpstorm_debug_validator.phar");

// Close and save archive
$zip->close();

// Delete temp files
unlink($buildRoot . "/phpstorm_debug.php");
unlink($buildRoot . "/phpstorm_index.php");
unlink($buildRoot . "/phpstorm_debug_validator.phar");
