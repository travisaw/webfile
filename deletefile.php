<?php

require_once 'config.php';

// File Path
$filePath = FILE_PATH;

$result = array();

header('Content-Type: application/json; charset=utf-8');

$deleteFile = $filePath . $_POST["file"];
if (file_exists($deleteFile)) {
    unlink($deleteFile);
    $result['message'] = "Deleted File {$_POST["file"]}";
}
else {
    $result['message'] = "{$_POST["file"]} not found.";
    http_response_code(404);
}

echo json_encode($result);


?>
