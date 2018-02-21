function init(){
}

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
                success: function(msg){
                    console.log("file uploaded : "+msg);
                    var shadow = getShadow(msg);
                    $("#uploadSubmit").removeAttr('disabled').val("Envoyer");
                    $("#result").css("visibility","visible");
                    return shadow;
                }
            });
        }
    });
}

function getShadow(file){
    $("#backContent").css("visibility","visible");
    console.log("search shadow");
    $.ajax({
        type: "POST",
        url: "./ajax/getShadow.php",
        dataType: 'text',
        data: {
        'file': file
        },
        success: function(msg){
            console.log("shadowed : "+msg);
            showImg(file);
            console.log(msg);
            $("#backContent").css("visibility","hidden");
            return msg;
        }
    });
}

function showImg(file){
    console.log(file);
    var file = file.split("/");
    var filePath = "";
    for(var i=1; i<file.length-1; i++){
        filePath += file[i]+"/";
    }
    file = file[file.length-1];
    var split = file.split(".");
    var fileExt = split[1];
    var fileName = split[0];


    var d = new Date();
    $("#originalFile").attr("src","./"+filePath+fileName+"."+fileExt+"?"+d.getTime());
    $("#vectorFile").attr("src","./"+filePath+fileName+".svg?"+d.getTime());

}

$(document).ready(function() {
    init();
    upload();
});
