Case = {
    changeDeath : function(obj) {
        $("INPUT#dateOfDeath").prop("disabled", $(obj).val() != 1);
        
        $("#dateOfDeathRequiredMark").addClass("d-none");
        if ($(obj).val() == 1)
            $("#dateOfDeathRequiredMark").removeClass("d-none");
    },
};