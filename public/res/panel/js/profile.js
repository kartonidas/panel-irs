Profile =
{
    changePassword : function(obj) {
        if ($(obj).is(":checked")) 
            $(".profile-change-password").removeClass("d-none");
        else
            $(".profile-change-password").addClass("d-none");
    },
};
