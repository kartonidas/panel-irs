Dictionary = {
    changeDictionaryType : function(obj) {
        $("DIV.row-by-type").addClass("d-none");
        $("DIV.row-by-type#by-type-" + $(obj).val()).removeClass("d-none");
    },
};