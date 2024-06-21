Modal = {
    getModalFormData : function(form, fieldPrefix) {
        var index = $("input[name='" + fieldPrefix + "[id]']", form).val();
        if(!index)
            index = "NEW_" + Math.floor(Date.now() / 1000);

        var inputFields = new Array(
            "input[name^='" + fieldPrefix + "[']",
            "select[name^='" + fieldPrefix + "[']",
            "textarea[name^='" + fieldPrefix + "[']"
        );

        var data = {};
        var regExp = /\[([^)]+)(\[\])?\]/;
        form.find(inputFields.join(",")).each(function() {
            var fieldName = $(this).attr("name")
            isArray = false;
            if(fieldName.substr(-2) == "[]") {
                isArray = true;
                fieldName = fieldName.slice(0, -2);
            }

            var matches = regExp.exec(fieldName);

            if(isArray) {
                if(data[matches[1]] == undefined)
                    data[matches[1]] = {};

                if($(this).attr("type") != undefined && $(this).attr("type") == "checkbox") {
                    if($(this).is(":checked"))
                        data[matches[1]][$(this).val()] = 1;
                }
                else
                    data[matches[1]][Object.keys(data[matches[1]]).length] = $(this).val();
            } else {
                if($(this).attr("type") != undefined && $(this).attr("type") == "checkbox")
                    data[matches[1]] = $(this).is(":checked") ? 1 : 0;
                else
                    data[matches[1]] = $(this).val();
            }
        });
        data.id = index;
        return data;
    },

    onModalShowEvent : function(obj) {
        var id = obj.attr("data-edit");
        var type = obj.attr("data-type");
        var target = obj.attr("data-modal");
        var object = obj.attr("data-object");
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

        $(target).find("*[name^='" + type + "[']:not([type=checkbox])").val("");
        $(target).find("*[name^='" + type + "[']:is([type=checkbox])").prop("checked", false);

        if (id != undefined) {
            var dataType = object + "-" + type;

            if(object != undefined) {
                $.ajax({
                    url : "/ajax/getDataById",
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
                    },
                    data : {id : id, type : dataType},
                    dataType : "json",
                    type: "post",
                    success: function(ret) {
                        if(ret.data != undefined)
                            Modal.setModalValues(target, type, ret.data);
                        else
                            Modal.setModalValues(target, type, {});
                        $(target).modal("toggle");
                    }
                })
            } else {
                var values = $(obj).closest("tr").data("values");
                
                $(obj).closest("tr").find("INPUT[data-add-to-modal-values='true']").each(function() {
                    var regex = new RegExp(type + "\\[" + id + "\\]\\[(.*)\\]", "gm");
                    var inputName = regex.exec($(this).attr("name"));
                    if (inputName && inputName[1] != undefined && inputName[1]) {
                        inputName = inputName[1];
                        values[inputName] = $(this).val();
                    }
                });
                
                console.log(values);
                Modal.setModalValues(target, type, values);
                $(target).modal("toggle");
            }
        } else {
            var defaultValues = $(obj).data('default-values');
            if(defaultValues != undefined)
            {
                for(i in defaultValues)
                {
                    var fieldObj = null;
                    if(Array.isArray(defaultValues[i]))
                        fieldObj = $(target).find("*[name='" + type + "[" + i + "][]']");
                    else
                        fieldObj = $(target).find("*[name='" + type + "[" + i + "]']");

                    if(!fieldObj) continue;

                    if(fieldObj.is("input") && obj.attr("type") == "checkbox")
                    {
                        fieldObj.prop("checked", false);
                        if(Array.isArray(defaultValues[i]))
                        {
                            for(j in defaultValues[i])
                                $(target).find("*[name='" + type + "[" + v + "][]'][value='"+defaultValues[i][j]+"']").prop("checked", true);
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
        }

        if(callback != undefined)
        {
            var tmp = callback.split(".");
            var cObject = tmp[0];
            var cFunction = tmp[1];

            if(typeof window[cObject][cFunction] == "function")
                window[cObject][cFunction](obj);
        }

        App.reInitDatepicker();
    },

    onModalSubmitForm : function(form, type, onSuccess) {
        if(!App.valid(form)) {
            var formData = {};
            formData[type] = Modal.getModalFormData(form, type);

            App.formSubmitButton(form, "start");
            $.ajax({
                url : form.attr("action"),
                headers: {
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
                },
                data : formData,
                dataType : "json",
                type: "post",
                success: function(ret) {
                    if(ret.success != undefined && ret.success) {
                        onSuccess(ret);
                    } else {
                        if(ret.error != undefined)
                            App.formErrors(form, ret.error);
                    }
                    App.formSubmitButton(form, "finish");
                }
            })
        }
    },

    setModalValues : function(target, type, values) {
        for(v in values)
        {
            var obj = null;
            if(Array.isArray(values[v]))
            {
                obj = $(target).find("*[name='" + type + "[" + v + "][]']");
            }
            else
                obj = $(target).find("*[name='" + type + "[" + v + "]']");

            if(!obj) continue;

            if(obj.is("input") && obj.attr("type") == "checkbox")
            {
                obj.prop("checked", false);
                if(Array.isArray(values[v]))
                {
                    for(j in values[v])
                        $(target).find("*[name='" + type + "[" + v + "][]'][value='"+values[v][j]+"']").prop("checked", values[v] ? true : false);
                }
                else
                    obj.prop("checked", values[v] ? true : false);
            }
            else
                obj.val(values[v]);
        }
    }
}
