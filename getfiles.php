<?php
require_once 'config.php';

// File Path
$filePath = FILE_PATH;
// Scan directory
$fileList = array_diff(scandir($filePath), array('.', '..'));
$fileListShow = array();

// Filename Processing
foreach($fileList as $filename) {
    $fileSize = filesize($filePath . $filename);
    // $fileMod = date("F d Y H:i:s", filemtime($filePath . $filename));
    $fileModified = date(DATE_ISO8601, filemtime($filePath . $filename));
    $fileCreated = date(DATE_ISO8601, filectime($filePath . $filename));
    $fileEntry = array('name' => $filename,
                       'path' => $filePath . $filename,
                       'size' => humanFileSize($fileSize),
                       'created' => $fileCreated,
                       'modified' => $fileModified
                   );
    array_push($fileListShow, $fileEntry);
}

// Return file list as json
header('Content-Type: application/json; charset=utf-8');
echo json_encode($fileListShow);

// Get human readable file size
function humanFileSize($size,$unit="") {
    if( (!$unit && $size >= 1<<30) || $unit == "GB")
    return number_format($size/(1<<30),2)." GB";
    if( (!$unit && $size >= 1<<20) || $unit == "MB")
    return number_format($size/(1<<20),2)." MB";
    if( (!$unit && $size >= 1<<10) || $unit == "KB")
    return number_format($size/(1<<10),2)." KB";
    return number_format($size)." bytes";
}

?>
