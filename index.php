<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="default.css">
<title>File Manager</title>
</head>
<body>
<h1>File Manager</h1>
<a href="">Refresh Page</a>
<p>
<?php
    // date_default_timezone_set("Asia/Singapore"); // Timezone override
    // File Path
    $filePath = "./files/";
    // Scan directory
    $fileList = array_diff(scandir($filePath), array('.', '..'));
    $fileListShow = array();

    // Filename Processing
    foreach($fileList as $filename) {
        $fileSize = filesize($filePath . $filename);
        $fileMod = date("F d Y H:i:s", filemtime($filePath . $filename));
        // $fileMod = date(DATE_ISO8601, filemtime($filePath . $filename));
        $fileEntry = array('name' => $filename, 'size' => humanFileSize($fileSize), 'modified' => $fileMod);
        array_push($fileListShow, $fileEntry);
    }

    // Action is Upload file
    if(isset($_POST['upload'])) {

        $uploadOk = 1;
        $uploadFile = $filePath . basename($_FILES["fileToUpload"]["name"]);

        // Check if file already exists
        if (file_exists($uploadFile)) {
          echo "File already exists.<br>";
          $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 150000000) {
          echo "File is too large.<br>";
          $uploadOk = 0;
        }

        // Check if $upload is set to 0 by an error
        if ($uploadOk == 0) {
            echo "File was not uploaded.<br>";
        }
        // If everything is ok, try to upload file
        else {
            $uploaded = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadFile);
            if ($uploaded) {
                echo "The file ". htmlspecialchars(basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.<br>";
                header("Refresh:0");
            }
            else {
                echo "There was an error uploading the file. Error #" . $_FILES["fileToUpload"]["error"] . "<br>";
            }
        }
    }

    // Delete file
    if(isset($_POST['delete'])) {

        $deleteFile = $filePath . $_POST["delete"];
        if (file_exists($deleteFile)) {
            unlink($deleteFile);
            echo "Deleted File {$_POST["delete"]}";
            header("Refresh:0");
        }
        else {
            echo "File not found.";
        }
    }

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
</p>

<form action="index.php" method="post" enctype="multipart/form-data">
Select file to upload:<br>
<input type="file" name="fileToUpload" id="fileToUpload"><br>
<input type="submit" value="Upload File" name="upload">
<br><br>
<p>
<?php if (empty($fileList)) { echo "No Files Found In Source Directory!"; } ?>
</p>
<p>
Times shown in <b><?php echo date_default_timezone_get(); ?></b> time.
</p>
<table>
<tr>
    <th>File</th>
    <th>Size</th>
    <th>Modified</th>
    <th>Download</th>
    <th>Delete</th>
</tr>
<?php foreach ($fileListShow as $file) { ?>
<tr>
    <td><?php echo $file['name']; ?></td>
    <td><?php echo $file['size']; ?></td>
    <td><?php echo $file['modified']; ?></td>
    <td><a href="<?php echo "$filePath/{$file['name']}"; ?>" download="<?php echo $file['name']; ?>" class="button">Download</a></td>
    <td><button type="submit" value="<?php echo $file['name']; ?>" name="delete">Delete</button></td>
</tr>
<?php }?>
</table>
</form>
</body>
</html>
