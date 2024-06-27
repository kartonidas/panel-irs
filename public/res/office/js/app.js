App =
{
    loginActivityInterval : null,
    init : function()
    {
        if ($(".datepicker").length) {
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd"
            });
        }
        
        if ($(".editor").length) {
            $(".editor").each(function() {
                CKEDITOR.replace($(this)[0], {
                    height: '270px',
                    toolbar: 'Full'
                });
            });
        }
    },
    
    getGusData : function(obj, key) {
        if (key == undefined || !key)
            key = "data-gus";
        
        var $this = $(obj);
        var field = $this.data("field-name");
        if (field != undefined && $("input[name='" + field + "']").length) {
            var input = $("input[name='" + field + "']");
            var nip = input.val();
            Validator.clear($this);
            if (nip != "" && Validator.nip(input, nip)) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/getDataFromGus",
                    dataType: "json",
                    type: "post",
                    data: {"nip": nip},
                    success: function(ret) {
                        if(ret.success != undefined && ret.success && ret.data != undefined) {
                            for (var i in ret.data)
                                $("input[" + key + "='" + i + "']").val(ret.data[i]);
                            
                            if ($("INPUT[" + key + "='street_house_apartment']").length) {
                                var address = ret.data.street;
                                
                                if (ret.data.street_no && ret.data.apartment_no)
                                    address += " " + ret.data.street_no + "/" + ret.data.apartment_no;
                                else if (ret.data.street_no)
                                    address += " " + ret.data.street_no;
                                else if (ret.data.apartment_no)
                                    address += " " + ret.data.apartment_no;
                                    
                                $("INPUT[" + key + "='street_house_apartment']").val(address);
                            }
                        } else
                            Validator.setError($this, "Nie udało się pobrać danych z bazy GUS");
                    }
                });
            }
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
            if((!$(this).is(":visible") && !$(this).attr("data-force-validate")) || $(this).attr("disabled"))
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

    formErrors : function(form, errors) {
        if(form.length && errors) {
            for(e in errors) {
                var fieldName = e;
                if(e.indexOf(".") !== -1) {
                    fieldNameSplitted = e.split(".");
                    fieldName = fieldNameSplitted[0] + "[" + fieldNameSplitted[1] + "]";
                }

                if($("*[name='" + fieldName + "']").length && $("*[name='" + fieldName + "']").attr("data-validate") != undefined)
                    Validator.setError($("*[name='" + fieldName + "']"), errors[e]);
            }

            if(errors["_message"] != undefined)
                form.find("DIV.alert-modal-error").text(errors["_message"]).removeClass("d-none");
        }
    },

    formApply : function(obj) {
        var form = $(obj).closest("form");
        if(form.length) {
            var hiddenApply = $("<input>").attr("type", "hidden").attr("name", "_apply").val(1);
            form.append(hiddenApply);
        }
        return true;
    },
    
    changePageSize : function(obj) {
        var url = $("OPTION:SELECTED", $(obj)).attr("data-url");
        window.location.href = url;
    },
    
    calculcateTax(obj, type, parent) {
        var container = (parent != undefined && parent.length) ? parent : obj.closest(".prices");

        var net = container.find("INPUT.net-amount").val().trim().replace(",", ".");
        var gross = container.find("INPUT.gross-amount").val().trim().replace(",", ".");
        var rate = container.find(".vat-rate").val().trim().replace(",", ".");

        if(net == "" || isNaN(net) || net < 0) net = 0;
        if(gross == "" || isNaN(gross) || gross < 0) gross = 0;
        if(rate == undefined || isNaN(rate)) rate = 0;

        net = parseFloat(net);
        gross = parseFloat(gross);
        rate = parseFloat(rate);

        if(type == "gross")
        {
            var netValue = gross;
            if(rate > 0)
                netValue = (gross * 100) / (rate + 100);

            container.find("INPUT.net-amount").val(netValue.toFixed(2));
        }
        else
        {
            var grossValue = net;
            if(rate > 0)
                grossValue = net * ((100 + rate) / 100);
                
            container.find("INPUT.gross-amount").val(grossValue.toFixed(2));
        }
    },
    
    select2Init : function() {
        if ($(".form-select-2").length) {
            $(".form-select-2").each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                    theme: "bootstrap"
                });
            });
        }
    },
    
    showToast : function(msg, toastClass) {
        $("#toast-message").removeClass("bg-success");
        $("#toast-message").removeClass("bg-danger");
         
        if (toastClass == undefined)
            toastClass = "bg-success";
        
        $("#toast-message").addClass(toastClass);
        $("#toast-message .toast-body").text(msg);
        var toast = new bootstrap.Toast($("#toast-message")[0]);
        toast.show();
    },
    
    setLoginActivityInterval : function(url) {
        App.loginActivityInterval = setInterval(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : url,
                type: "post",
                dataType: "json",
                success: function(ret) {
                    if (!ret.status) {
                        if (/\/?kancelaria(\/(.*))?/.test(window.location.pathname))
                            window.location.reload();
                        clearInterval(App.loginActivityInterval);
                    }
                }
            });
        }, 30000);
    }
}

$(document).ready(function(){
    App.init();
    App.validForm();
    App.select2Init();
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
