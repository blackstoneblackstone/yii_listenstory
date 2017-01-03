/**
 * Created by lihb on 12/21/16.
 */

var currentStoryId = "";
$(function () {
    $('.tool').tooltip();
    $('#imgUp').fileupload({
        dataType: 'json',
        url: "/uploadSroryImg",
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#imgBar').css(
                'width',
                progress + '%'
            );
            $('#imgBar').text(progress + '%');
            if (progress >= 100) {
                $('#imgBar').text("上传成功");
                setTimeout(function () {
                    $("#imgUpModal").modal('hide');
                    window.location.reload();
                }, 2000);
            }
        },
        done: function (e, data) {
        },

        add: function (e, data) {
            $('#imgUploadBtn').click(function () {
                $("#imgProgress").show();
                console.log(data);
                data.submit();
                return false;
            });
        },
        change: function (e, data) {
            $.each(data.files, function (index, file) {
                $("#imgUpText").text("大小:" + file.size / 1000 + "kb");
                return file.name;
            });
        },
        replaceFileInput: false
    });

    $('#audioUp').fileupload({
        dataType: 'json',
        url: "/uploadSroryAudio",
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#audioBar').css(
                'width',
                progress + '%'
            );
            $('#audioBar').text(progress + '%');
            if (progress >= 100) {
                $('#audioBar').text("上传成功");
                setTimeout(function () {
                    $("#audioUpModal").modal('hide');
                    window.location.reload();
                }, 2000);
            }
        },
        done: function (e, data) {

        },
        add: function (e, data) {
            $('#audioUploadBtn').click(function () {
                $("#audioProgress").show();
                data.submit();
                return false;
            });
        },
        replaceFileInput: false
    });
});

function imgUpBtn(id) {
    $("#imgUpModal").modal('show');
    $(".storyid").attr("value", id);
}
function audioUpBtn(id) {
    $("#audioUpModal").modal('show');
    $(".storyid").attr("value", id);
}

function addSave() {
    var name = $("#addName").val();
    var desc = $("#addDesc").val();
    $.ajax({
        url: "addStory",
        data: {
            name: name,
            description: desc
        },
        type: 'get',
        success: function (data) {
            if (data == 0) {
                window.location.reload();
            } else {
                alert("失败");
            }
        }
    })
}

function delStory(id) {
    $.ajax({
        url: 'delStory?id=' + id,
        type: 'get',
        success: function (data) {
            if (data == 0) {
                alert("删除成功");
                window.location.reload();
            } else {
                alert("删除失败");
            }
        }
    })
}

function editStory(id, name, desc) {
    $("#editName").val(name);
    $("#editDesc").val(desc);
    currentStoryId = id;
    $("#editStory").modal("show");
}

function eidtSave() {
    $.ajax({
        url: "editStory",
        type: 'get',
        data: {
            id: currentStoryId,
            name: $("#editName").val(),
            desc: $("#editDesc").val()
        },
        success: function (data) {
            if (data == 0) {
                window.location.reload();
            } else {
                alert('编辑失败');
            }
        }
    })

}