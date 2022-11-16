<?php

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

// Get data type and data payload from BASE64 string
list($dataType, $fileData) = explode(';', $_POST['data']);

// base64-encoded data
list(, $encodedData) = explode(',', $fileData);

// decode base64-encoded image data
$decodedData = base64_decode($encodedData);

$filePath = FILE_PATH;
$result = array();
$uploadOk = 1;
$uploadFile = $filePath . basename($_POST['name']);
$prettyUploadFile = htmlspecialchars(basename($_POST['name']));

// Check if file already exists
if (file_exists($uploadFile)) {
    $result['message'] = "File $prettyUploadFile already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_POST["size"] > MAX_UPLOAD_SIZE) {
    $result['message'] = "File is too large.";
    $uploadOk = 0;
}

// Check if $upload is set to 0 by an error
if ($uploadOk == 0) {
    http_response_code(400);
}
// If everything is ok, try to upload file
else {
    $fp = fopen($uploadFile,'w');
    // Check if able to open file. If not likely folder permissions
    if ( !$fp ) {
        $result['message'] = "Unable to write file $prettyUploadFile.";
        http_response_code(400);
    }
    else {
        // fwrite($fp, $_POST['data']);
        fwrite($fp, $decodedData);
        fclose($fp);
        $result['message'] = "The file $prettyUploadFile has been uploaded.";
    }
}

echo json_encode($result);

?>
