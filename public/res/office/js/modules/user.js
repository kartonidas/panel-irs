User = {
    init : function() {
    },

    changePassword : function(obj) {
        if($(obj).is(":checked"))
            $("DIV.password").removeClass("d-none");
        else
            $("DIV.password").addClass("d-none");
    }
}

$(document).ready(function(){
    User.init();
});
