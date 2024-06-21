Permission = {
    init : function() {
    },

    checkGroup : function(obj, type) {
        var checked = $(obj).is(":checked");

        $("input[type='checkbox'][value$=':" + type + "']").each(function(){
            $(this).prop("checked", checked);
        });
    },
    
    changeRole : function(obj) {
        $("TBODY.permissions").addClass("d-none");
        $(".role-admin").addClass("d-none");
        
        $("#col-role").addClass("col-md-6");
        $("#col-role").removeClass("col-md-3");
        if ($(obj).val() == "admin")
        {
            $("TBODY.permissions-admin").removeClass("d-none");
            $(".role-admin").removeClass("d-none");
            
            $("#col-role").removeClass("col-md-6");
            $("#col-role").addClass("col-md-3");
        }
            
        if ($(obj).val() == "employee")
            $("TBODY.permissions-employee").removeClass("d-none");
            
        if ($(obj).val() == "admin" || $(obj).val() == "employee")
            $("DIV.col-permissions").removeClass("d-none");
        else
            $("DIV.col-permissions").addClass("d-none");
            
        if ($(obj).val() == "admin")
            Permission.changeAdminPermissionType($("SELECT[name='admin_permission_type']")[0]);
    },
    
    changeAdminPermissionType : function(obj) {
        console.log($(obj).val());
        if ($(obj).val() == "full")
            $("DIV.col-permissions").addClass("d-none");
        else
            $("DIV.col-permissions").removeClass("d-none");
    }
};
