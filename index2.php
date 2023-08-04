<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="index2.min.css?2">
<title>File Manager</title>
</head>
<body>
<div class="divmain">
<h1>File Manager</h1>
<p>Select file to upload:</p>
<table class="uploadtable">
<tr>
<td><input type="file" id="fileToUpload" onchange="updateFileNameLbl()" hidden>
<label class="uploadbutton" for="fileToUpload">Select File</label></td>
<td><label id="lblFileName"></label></td>
</tr>
<tr><td><button class="uploadbutton" onclick="uploadFile()">Upload File</button></td>
<td><progress id="fileprogress" value="0" max="100" hidden>0%</progress></td></tr>
<tr><td><button class="uploadbutton" onclick="refreshFiles()">Refresh Files</button></td><td></td></tr>
</table>
<p class="statuslabel"><label id="lblStatus"></label></p>
<p class="statuslabel">Files:</p>
<table id="tblFiles">
<tr><th>File</th><th>Size</th><th>Modified</th><th>Download</th><th>Delete</th></tr>
</table>
<br><br><br>
<p>Trouble with this page? Try <a href="index1">version 1</a>.</p>
</div>
<script src="jquery-3.7.0.min.js"></script>
<script src="index2.min.js?3"></script>
<?php if (LOG_VISIT > 0) {
echo "
<script src='https://www.travisty.org/assets/js/sitehit.min.js?v=2'></script>
<script>
$(document).ready(function() {
	window.onload = tHit(1, 11);
    $(document).on('click', 'a', function(e){
        var txt = $(e.target).attr('href');
        tLink(11, 'LinkClick', txt);
	});
});
</script>";
}
?>
</body>
</html>
