<?php

require_once 'config.php';

// File Path
$filePath = FILE_PATH;

$result = array();

$deleteFile = $filePath . $_POST["file"] . "ss";
if (file_exists($deleteFile)) {
    //unlink($deleteFile);
    $result['message'] = "Deleted File {$_POST["file"]}";
    // Return file list as json
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);
}
else {
    $result['message'] = "{$_POST["file"]} not found.";
    header('HTTP/1.0 404 File Not Found; Content-Type: application/json; charset=utf-8');
    echo json_encode($result);
    // http_response_code(404);
}



?>
