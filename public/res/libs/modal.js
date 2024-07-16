Modal = {
    onModalShowEvent : function(obj) {
        var id = obj.attr("data-id");
        var target = obj.attr("data-modal");
        var url = obj.attr("data-url");
        var callback = obj.attr("data-callback");

        App.preventFormSubmit(target);
        App.formSubmitButton(target, "init");

        Validator.clearAll($(target));
        $(target).find("DIV.alert-modal-error").addClass("d-none");

        if (id != undefined) {
            $(".modal-title .header-new", target).hide();
            $(".modal-title .header-edit", target).show();
        } else {
            $(".modal-title .header-new", target).show();
            $(".modal-title .header-edit", target).hide();
        }

        $("FORM", target).find("INPUT:not([type=checkbox]),SELECT,TEXTAREA").val("");
        $("FORM", target).find("INPUT:is([type=checkbox])").prop("checked", false);

        if (id != undefined) {
            if(url != undefined) {
                $.ajax({
                    url : url,
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
                    },
                    data : {id : id},
                    dataType : "json",
                    type: "get",
                    success: function(ret) {
                        if(ret.data != undefined)
                            Modal.setModalValues(target, ret.data);
                        else
                            Modal.setModalValues(target, {});
                        $(target).modal("toggle");
                        
                        Modal.runCallback(callback, obj);
                    }
                });
            } else {
                var values = $(obj).closest("tr").data("values");
                
                $(obj).closest("tr").find("INPUT[data-add-to-modal-values='true']").each(function() {
                    //var regex = new RegExp(type + "\\[" + id + "\\]\\[(.*)\\]", "gm");
                    //var inputName = regex.exec($(this).attr("name"));
                    //if (inputName && inputName[1] != undefined && inputName[1]) {
                    //    inputName = inputName[1];
                    //    values[inputName] = $(this).val();
                    //}
                });
                
                Modal.setModalValues(target, values);
                $(target).modal("toggle");
                Modal.runCallback(callback, obj);
            }
        } else {
            var defaultValues = $(obj).data('default-values');
            if(defaultValues != undefined)
            {
                for(i in defaultValues)
                {
                    var fieldObj = null;
                    if(Array.isArray(defaultValues[i]))
                        fieldObj = $("FORM", target).find("*[name='[" + i + "][]']");
                    else
                        fieldObj = $("FORM", target).find("*[name='[" + i + "]']");

                    if(!fieldObj) continue;

                    if(fieldObj.is("input") && obj.attr("type") == "checkbox")
                    {
                        fieldObj.prop("checked", false);
                        if(Array.isArray(defaultValues[i]))
                        {
                            for(j in defaultValues[i])
                                $("FORM", target).find("*[name='[" + v + "][]'][value='"+defaultValues[i][j]+"']").prop("checked", true);
                        }
                        else
                            fieldObj.prop("checked", true);
                    }
                    else
                        fieldObj.val(defaultValues[i]);
                }
            }

            var date = new Date();
            var currentDate = date.getFullYear() + "-" + App.zeroPad(date.getMonth()+1, 2) + "-" + App.zeroPad(date.getDate(), 2);
            var currentHour = date.getHours();
            var currentMin = date.getMinutes();

            $(target).find(".current-date").val(currentDate);
            $(target).find(".current-hour").val(currentHour);
            $(target).find(".current-min").val(currentMin);

            $(target).modal("toggle");
            Modal.runCallback(callback, obj);
        }

        App.reInitDatepicker();
    },

    onModalSubmitForm : function(form, onSuccess) {
        if(!App.valid(form)) {
            App.formSubmitButton(form, "start");
            
            var processData = true;
            var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
            
            var data = form.serialize();
            if (form.attr("data-file") != undefined && form.attr("data-file")) {
                data = new FormData(form[0]);
                processData = false;
                contentType = false;
            }
            
            $.ajax({
                url : form.attr("action"),
                headers: {
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
                },
                data : data,
                dataType : "json",
                type: "post",
                processData: processData,
                contentType: contentType,
                success: function(ret) {
                    onSuccess(ret);
                    App.formSubmitButton(form, "finish");
                },
                error: function(res) {
                    var errorsArray = [];
                    if (res.responseJSON.errors != undefined) {
                        var errors = res.responseJSON.errors;
                        for (const [key, value] of Object.entries(errors)) 
                            value.forEach((e) => errorsArray.push(e))
                    }
                    else if (res.responseJSON.message != undefined)
                        errorsArray.push(res.responseJSON.message);
                        
                    Modal.formErrors(form, errorsArray);
                    App.formSubmitButton(form, "finish");
                }
            });
        }
    },

    setModalValues : function(target, values) {
        for(v in values)
        {
            var obj = null;
            if(Array.isArray(values[v]))
            {
                obj = $("FORM", target).find("*[name='" + v + "[]']");
            }
            else
                obj = $("FORM", target).find("*[name='" + v + "']");

            if(!obj) continue;

            if(obj.is("input") && obj.attr("type") == "checkbox")
            {
                obj.prop("checked", false);
                if(Array.isArray(values[v]))
                {
                    for(j in values[v])
                        $(target).find("*[name='" + v + "[]'][value='"+values[v][j]+"']").prop("checked", values[v] ? true : false);
                }
                else
                    obj.prop("checked", values[v] ? true : false);
            }
            else
                obj.val(values[v]);
        }
    },
    
    formErrors : function(form, errors) {
        if(form.length && errors) {
            var ul = $("<UL>").addClass("list-unstyled mb-0")
            errors.forEach((e) => ul.append($("<LI>").text(e)));
            form.find("DIV.alert-modal-error").html(ul).removeClass("d-none");
        }
    },
    
    runCallback : function(callback, obj) {
        if(callback != undefined)
        {
            var tmp = callback.split(".");
            var cObject = tmp[0];
            var cFunction = tmp[1];

            if(typeof window[cObject][cFunction] == "function")
                window[cObject][cFunction](obj);
        }
    }
}
