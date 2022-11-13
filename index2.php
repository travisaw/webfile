<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="index2.css">
<title>File Manager</title>
</head>
<body>
<div class="divmain">
<h1>File Manager</h1>
<p>Select file to upload:</p>
<table class="uploadtable">
<tr>
<td><input type="file" id="fileToUpload" onchange="updateFileNameLbl()" hidden>
<label class="button" for="fileToUpload">Select File</label></td>
<td><label id="lblFileName"></label></td>
</tr>
<tr><td><button onclick="uploadFile()">Upload File</button></td><td></td></tr>
<tr><td><button onclick="refreshFiles()">Refresh Files</button></td><td></td></tr>
</table>
<label id="lblStatus"></label>
<table id="tblFiles">
<tr><th>File</th><th>Size</th><th>Modified</th><th>Download</th><th>Delete</th></tr>
</table>
<br><br><br>
<p>Having trouble? Try <a href="index">version 1</a> of this tool.</p>
</div>
<script src="jquery-3.6.1.min.js"></script>
<script>

$(document).ready(function() {
    loadFiles();
});

function refreshFiles() {
    loadFiles();
    labelSuccess("Files Refreshed");
    $('#fileToUpload').val('');
    updateFileNameLbl();
}

function loadFiles() {
    $.get("getfiles", function(data, status){
        populateFilesTable(data);
    });
}

function populateFilesTable(data) {
    $("#tblFiles").find("tr:not(:first)").remove();
    $.each(data, function(key, obj) {
        var modifiedDate =  new Date(obj['modified']);
        var row = "<tr>";
        row += "<td>" + obj['name'] + "</td>";
        row += "<td>" + obj['size'] + "</td>";
        row += "<td>" + modifiedDate.toLocaleString() + "</td>";
        row += "<td><a href=\"" + obj['path'] + "\" download>Download</a></td>";
        row += "<td><button onclick=\"deleteFile('" + obj['name'] + "')\">Delete</button></td>";
        row += "</tr>";
        $('#tblFiles').append(row);
    });
}

function deleteFile(filename) {
    var data = { 'file' : filename };
    $.ajax({
        type: "POST",
        url: "deletefile",
        data: data,
        success: function (data){
            labelSuccess(data['message']);
            loadFiles();
        },
        error: function (data){
            if (data['responseJSON']) {
                labelError(data['responseJSON']['message']);
            }
            else {
                labelError("Unknown error encountered");
            }
        },
        dataType: "json"
    });
}

function labelSuccess(labelText) {
    $('#lblStatus').removeClass('lblfail').addClass('lblsuccess');
    $('#lblStatus').html(labelText);
    setTimeout(function() {
        $('#lblStatus').html("");
    }, 5000);
}

function labelError(labelText) {
    $('#lblStatus').removeClass('lblsuccess').addClass('lblfail');
    $('#lblStatus').html(labelText);
    setTimeout(function() {
        $('#lblStatus').html("");
    }, 5000);
}

function uploadFile() {
    if (document.getElementById('fileToUpload').files[0]) {
        var file = document.getElementById('fileToUpload').files[0];
        var reader = new FileReader();
        reader.readAsText(file, 'UTF-8');
        reader.onload = postFile;
        //reader.onloadstart = ...
        // reader.onprogress = postProgress; //... <-- Allows you to update a progress bar.
        //reader.onabort = ...
        //reader.onerror = ...
        //reader.onloadend = ...
    }
    else {
        labelError("Select file to upload!");
    }
}

function postFile(event) {
    var file = document.getElementById('fileToUpload').files[0];
    var fileData = event.target.result;
    var fileName = file.name;
    var fileSize = file.size;
    var fileMod = file.lastModified;
    var fileType = file.type;
    var postData = { data: fileData, name: fileName, size: fileSize, modified: fileMod, type: fileType };
    $.ajax({
        type: "POST",
        url: "uploadfile",
        data: postData,
        success: function (data){
            labelSuccess(data['message']);
            $('#fileToUpload').val('');
            loadFiles();
        },
        error: function (data){
            if (data['responseJSON']) {
                labelError(data['responseJSON']['message']);
            }
            else {
                labelError("Unknown error encountered");
            }
        },
        dataType: "json"
    });
}

function postProgress(event) {
    console.log(event);
}

function updateFileNameLbl() {
    var filename = $('#fileToUpload')[0].files[0];
    if (typeof filename !== 'undefined') {
        $("#lblFileName").text(filename.name);
    }
    else {
        $("#lblFileName").empty();
    }
}

</script>
</body>
</html>
