Customer = {
    init : function() {
    },
    
    changePassword : function(obj) {
        if($(obj).is(":checked"))
            $("DIV.user-change-password").removeClass("d-none");
        else
            $("DIV.user-change-password").addClass("d-none");
    },
    
    changeSftpPassword : function(obj) {
        var input = $("<INPUT>").attr("type", "hidden").attr("name", "set_password").val(1);
        $(obj).closest("DIV").find("INPUT[name='password']").val("");
        $(obj).closest("DIV").find("INPUT[name='password']").removeClass("d-none");
        $(obj).closest("DIV").append(input);
        $(obj).remove();
    },
    
    sftpTestConfiguration : function(obj, url) {
        var data = $(obj).closest("FORM").serialize();
        $(obj).prop("disabled", true);

        $("DIV#configuration-valid").addClass("d-none");
        $("DIV#configuration-invalid").addClass("d-none");
        $("SPAN#configuration-progress").removeClass("d-none");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            data: data,
            dataType: "json",
            type: "post",
            success: function(ret) {
                if(ret.status != undefined && ret.status)
                    $("DIV#configuration-valid").removeClass("d-none");
                else
                {
                    $("DIV#configuration-invalid").removeClass("d-none");
                    $("SPAN#configuration-invalid-error").text(ret.error);
                }
                $("SPAN#configuration-progress").addClass("d-none");
                $(obj).prop("disabled", false);
            },
            error: function() {
                $("SPAN#configuration-progress").addClass("d-none");
                $(obj).prop("disabled", false);
            }
        });
    },
}

$(document).ready(function(){
    Customer.init();
})