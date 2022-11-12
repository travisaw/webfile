<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="default2.css">
<title>File Manager</title>
</head>
<body>
<h1>File Manager</h1>
<button onclick="refreshFiles()">Refresh Files</button><br><br>
<label id="lblStatus"></label>
<table id="tblFiles">
<tr><th>File</th><th>Size</th><th>Modified</th><th>Download</th><th>Delete</th></tr>
</table>
<script src="jquery-3.6.1.min.js"></script>
<script>

$(document).ready(function() {
    loadFiles();
});

function refreshFiles() {
    loadFiles();
    labelSuccess("Files Refreshed");
}

function loadFiles() {
    $.get("getfiles", function(data, status){
        populateFilesTable(data);
    });
}

function populateFilesTable(data) {
    $("#tblFiles").find("tr:not(:first)").remove();
    $.each(data, function(key, obj) {
        // console.log(obj);
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
            console.log(data);
        },
        error: function (data){
            labelError(data['responseText']['message']);
            console.log(data['responseText']);
            //console.log(data);
        },
        dataType: "json"
    });
}

function labelSuccess(labelText) {
    $('#lblStatus').html(labelText);
    $('#lblStatus').addClass("lblsuccess");
    setTimeout(function() {
        $('#lblStatus').html("");
    }, 5000);
}

function labelError(labelText) {
    $('#lblStatus').html(labelText);
    $('#lblStatus').addClass("lblfail");
    setTimeout(function() {
        $('#lblStatus').html("");
    }, 5000);
}

</script>
</body>
</html>
