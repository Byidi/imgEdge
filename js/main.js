function upload(){
    $("#uploadSubmit").click(function(e){
        e.preventDefault();
        if($("#uploadFile").val() != ""){
            $("#uploadSubmit").attr("disabled","disabled").val("Upload en cours ...");
            $("#backContent").css("visibility","visible");
            var formData =  new FormData($('#uploadForm')[0]);
            $.ajax({
                type: "POST",
                url: "./ajax/uploadImg.php",
                enctype: 'multipart/form-data',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                success: function(data){
                    $("#uploadSubmit").parent().remove();
                    $("#result").css("visibility","visible");
                    $("#backContent").css("visibility","hidden");
                    getShadow(data, $("#range").val());
                    $("#range").change(function(){
                        getShadow(data, $("#range").val());
                    });
                }
            });
        }
    });
}

function getShadow(file, range){
    $("#backContent").css("visibility","visible");
    $.ajax({
        type: "POST",
        url: "./ajax/getShadow.php",
        dataType: 'text',
        data: {
            'file': file,
            'range': range
        },
        success: function(data){
            $("#backContent").css("visibility","hidden");
            showImg(data);
            return data;
        }
    });
}

function showImg(file){
    file = (file[0] == file[1] && file[1]== ".")? file.slice(1):file;

    var d = new Date();
    var img = $('<img src="'+file+'?'+d+'" alt="shadow">');
    $("#canva").empty().append(img);
}

$(document).ready(function() {
    upload();
});
