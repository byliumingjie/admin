$(function () {

    $("#tableExcel tr").each(function () {

        var _this = $(this);
        // 删除
        _this.find(".delpayConfig").click(function () {
            var id = $(this).attr('data-value');

            if (confirm("你确定要把配置,编号为" + id + "删除么?")) {
                $.ajax({
                    type: 'POST',
                    url: 'delConfig',
                    data: 'id=' + id,
                    dataType: 'json',
                    success: function (result) {
                        alert(result.msg);
                        if (result.errcode == 0) {
                            window.location.reload();
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.status);
                        alert(XMLHttpRequest.readyState);
                        alert(errorThrown);
                        alert(textStatus); // paser error;
                    }
                });
            }

        });

        // 编辑
        _this.find(".editpayConcif").click(function () {
            var id = $(this).attr('data-value');

            var form = $("#editpayconfigForm").serializeArray();
            for (var i = 0; i < form.length; i++) {
                var name = form[i].name;

                if (name == null || name == "") {
                    continue;
                }
                value = $(this).parent().parent().find('[data-name=' + name + ']').text();
                $("#editpayconfigForm [name=" + name + "]").val(value);
            }

            $("#EditConfigModal").modal({backdrop: "static"}).modal('show');
        });
    });

    $("#editPayBtn").click(function () {
        var Formdata = $("#editpayconfigForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: 'editConfig',
            data: Formdata,
            dataType: 'json',
            success: function (result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.reload();
                    //window.location.href = window.location.href;
                }
            }
        });

    });
    //
    $(".loadConfig").click(function () {
        var id = $(this).attr('data-value');
        $.ajax({
            type: 'POST',
            url: 'loadConfig',
            data: "id=" + id,
            dataType: 'json',
            success: function (result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.reload();
                    //window.location.href = window.location.href;
                }
            }
        });

    })
    $("#addPayCofnigBtn").click(function () {
        var Formdata = $("#addPayConfigForm").serializeArray();
        $.ajax({
            type: 'POST',
            url: 'addConfig',
            data: Formdata,
            dataType: 'json',
            success: function (result) {
                alert(result.msg);
                if (result.errcode == 0) {
                    window.location.reload();
                    //window.location.href = window.location.href;
                }
            }
        });

    });

});