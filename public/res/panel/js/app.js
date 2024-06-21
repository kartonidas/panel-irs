App =
{
    init : function()
    {
        if ($(".datepicker").length) {
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });
        }
    },
    
    validForm : function() {
        $("FORM.validate").submit(function(e) {
            var $this = $(this);
            var hasError = App.valid($this);
            if(hasError)
                e.preventDefault();
        });
    },

    valid : function($this) {
        var hasError = false;
        $this.find("*[data-validate]").each(function() {
            if(!$(this).is(":visible") && !$(this).attr("data-force-validate"))
                return true;

            if($(this).is("input[type='radio'],input[type='checkbox']")) {
                var inputName = $(this).attr("name");
                var val = $("input[name='" + inputName + "']:checked").val();
                if(val == undefined) val = "";
            }
            else {
                var val = $(this).val();
                if(val == undefined) val = "";
                val = val.trim();
           }

            var type = $(this).data("validate");
            if(type == undefined || !type)
                return true;

            type = type.split("|");
            for(i in type) {
                var _type = type[i];
                var _param = null;
                if(_type.indexOf(":") !== -1) {
                    var tmp = _type.split(":");
                    _type = tmp[0];
                    _param = tmp[1];
                }
                if(Validator[_type] != undefined) {
                    if(!Validator[_type]($(this), val, _param)) {
                        hasError = true;
                        break;
                    }
                }
            }
        });
        return hasError;
    },

    formApply : function(obj) {
        var form = $(obj).closest("form");
        if(form.length) {
            var hiddenApply = $("<input>").attr("type", "hidden").attr("name", "_apply").val(1);
            form.append(hiddenApply);
        }
        return true;
    },
}

$(document).ready(function(){
    App.init();
    App.validForm();
});
