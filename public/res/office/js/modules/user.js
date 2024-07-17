User = {
    init : function() {
        User.onModalShowEvent();
    },

    changePassword : function(obj) {
        if($(obj).is(":checked"))
            $("DIV.password").removeClass("d-none");
        else
            $("DIV.password").addClass("d-none");
    },
    
    changeCaseAccessType : function(obj) {
        $("#accessTypeWarning").removeClass("d-none");
        $("#accessTypeCaseSelectedButton").removeClass("d-none");
        if ($(obj).val() != "selected")
        {
            $("#accessTypeWarning").addClass("d-none");
            $("#accessTypeCaseSelectedButton").addClass("d-none");
        }
    },
    
    onModalShowEvent : function() {
        $("body").on("click", ".open-modal", function(e){
            e.preventDefault();
            Modal.onModalShowEvent($(this));
        });
    },
    
    saveAccessForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#accessTableContainer").ajaxTable("refresh");
            $("#accessModal").modal("hide");
            App.showToast("Dostęp został zapisany", "bg-success");
        });
    },
    
    removeAccess : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#accessTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Dostęp został usunięty", "bg-success");
            }
        });
        return false;
    },
    
    afterAccessModalOpen : function() {
        $("SELECT[name='customer_id']", $("#accessModal")).trigger("change");
        if ($("SELECT[name='type']", $("#accessModal")).val() == "all")
            $("#formCaseNumbersContainer").addClass("d-none");
        else
            $("#formCaseNumbersContainer").removeClass("d-none");
    },
    
    onAccessCustomerChange : function(obj) {
        $("#formCaseNumbersInputsCheckboxes").html("");
        
        var numbers = $("OPTION:SELECTED", $(obj)).attr("data-case-numbers");
        if (!$(obj).val()) {
            $("#formCaseNumbersInputsSelectCustomerDanger").removeClass("d-none");
        } else {
            var selectedCaseNumbers = $("INPUT[name='case_numbers']").val();
            if (selectedCaseNumbers != undefined && selectedCaseNumbers)
                selectedCaseNumbers = selectedCaseNumbers.split(",");
            
            if (numbers != undefined && numbers)
            {
                $("#formCaseNumbersInputsNoCaseNumbersDanger").addClass("d-none");
                var row = $("<DIV>").addClass("row");
                
                numbers = numbers.split(",");
                numbers.forEach((v) => {
                    var id = $(obj).val() + "-" + v;
                    
                    var label = $("<LABEL>").addClass("form-check-label").attr("for", id).text(v);
                    var checkbox = $("<INPUT>").attr("type", "checkbox").val(v).addClass("form-check-input").attr("id", id).attr("name", "selected_case_numbers[]");
                    var div = $("<DIV>").addClass("form-check d-inline-block m-2");
                    
                    if (selectedCaseNumbers.indexOf(v) !== -1)
                        checkbox.prop("checked", true);
                    
                    div.append(checkbox).append(label);
                    
                    var col = $("<DIV>").addClass("col-2").append(div);
                    row.append(col);
                    
                });
                    $("#formCaseNumbersInputsCheckboxes").append(row);
            }
            else
            {
                $("#formCaseNumbersInputsNoCaseNumbersDanger").removeClass("d-none");
            }
            $("#formCaseNumbersInputsSelectCustomerDanger").addClass("d-none");
        }
    },
    
    onAccessTypeChange : function(obj) {
        if ($(obj).val() == "all")
            $("#formCaseNumbersContainer").addClass("d-none");
        else
            $("#formCaseNumbersContainer").removeClass("d-none");
    }
}

$(document).ready(function(){
    User.init();
});
