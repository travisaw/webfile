
// When page loads, load list of files.
$(document).ready(function() {
    loadFiles();
});

// Use when refreshing files. Loads files plus other actions to clean up form.
function refreshFiles() {
    loadFiles();
    labelSuccess("Files Refreshed");
    $('#fileToUpload').val('');
    updateFileNameLbl();
}

// Function to make AJAX call to get list of files.
function loadFiles() {
    $.get("getfiles", function(data, status){
        populateFilesTable(data);
    });
}

// Function to take data returned from loadFiles() and populate the file table.
function populateFilesTable(data) {
    $("#tblFiles").find("tr:not(:first)").remove();
    $.each(data, function(key, obj) {
        var modifiedDate =  new Date(obj['modified']);
        var row = "<tr>";
        row += "<td>" + obj['name'] + "</td>";
        row += "<td>" + obj['size'] + "</td>";
        row += "<td>" + modifiedDate.toLocaleString() + "</td>";
        row += "<td><a class=\"uploadbutton\" href=\"" + obj['path'] + "\" download>Download</a></td>";
        row += "<td><button onclick=\"deleteFile('" + obj['name'] + "')\">Delete</button></td>";
        row += "</tr>";
        $('#tblFiles').append(row);
    });
}

// Function to make AJAX call to delete file.
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

// Updates lblStatus with message provided and applies CSS class.
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

// Updates fileName label based on selection in file selector HTML input.
function updateFileNameLbl() {
    var filename = $('#fileToUpload')[0].files[0];
    if (typeof filename !== 'undefined') {
        $("#lblFileName").text(filename.name);
    }
    else {
        $("#lblFileName").empty();
        $("#fileprogress")[0].max = 1;
        $("#fileprogress")[0].value = 0;
        $("#fileprogress").hide("slow");
    }
}

// Function called when uploading a file.
function uploadFile() {
    if (document.getElementById('fileToUpload').files[0]) {
        var file = document.getElementById('fileToUpload').files[0];
        var reader = new FileReader();
        $("#fileprogress").show();
        // console.log(file);
        // reader.readAsText(file, 'UTF-8');
        reader.readAsArrayBuffer(file);  // Read all files as binary instead of reading as text.
        // reader.readAsBinaryString(file);  // Read all files as binary instead of reading as text.
        // reader.onload = postFile;
        //reader.onloadstart = ...
        //reader.onprogress = //... <-- Allows you to update a progress bar.
        //reader.onabort = ...
        //reader.onerror = ...
        reader.onloadend = postFile;
    }
}

// Function used to POST upload data. Called from uploadFile().
function postFile(event) {
    // console.log(event);
    var file = document.getElementById('fileToUpload').files[0];
    var enc = new TextDecoder("utf-8");
    // var fileData = ArrayBufferToString(event.target.result);
    // var fileData = toBinString(Uint8Array.from(event.target.result))
    // var fileData = event.target.result.slice(event.target.result.start, event.target.result.end);
    var fileData = event.target.result;
    var fileName = file.name;
    var fileSize = file.size;
    var fileMod = file.lastModified;
    var fileType = file.type;
    var postData = { data: fileData, name: fileName, size: fileSize, modified: fileMod, type: fileType };
    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    // var percentComplete = evt.loaded / evt.total;
                    // percentComplete = parseInt(percentComplete * 100);
                    // console.log(percentComplete);
                    $("#fileprogress")[0].max = evt.total;
                    $("#fileprogress")[0].value = evt.loaded;
                }
            }, false);
            return xhr;
        },
        type: "POST",
        url: "uploadfile",
        data: postData,
        // processData: false,
        success: function (data){
            labelSuccess(data['message']);
            $('#fileToUpload').val('');
            loadFiles();
            updateFileNameLbl();
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

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
const toBinString = (bytes) => bytes.reduce((str, byte) => str + byte.toString(2).padStart(8, '0'), '');
