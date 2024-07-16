Case = {
    init : function() {
        Case.onModalShowEvent();
    },
    changeDeath : function(obj) {
        $("INPUT#dateOfDeath").prop("disabled", $(obj).val() != 1);
        
        $("#dateOfDeathRequiredMark").addClass("d-none");
        if ($(obj).val() == 1)
            $("#dateOfDeathRequiredMark").removeClass("d-none");
    },
    saveClaimForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#claimsTableContainer").ajaxTable("refresh");
            $("#claimModal").modal("hide");
            App.showToast("Roszczenie zostało zapisane", "bg-success");
        });
    },
    removeClaim : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#claimsTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Roszczenie zostało usunięte", "bg-success");
            }
        });
        return false;
    },
    saveHistoryForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#historyTableContainer").ajaxTable("refresh");
            $("#historyModal").modal("hide");
            App.showToast("Czynność została zapisana", "bg-success");
        });
    },
    removeHistory : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#historyTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Czynność została usunięta", "bg-success");
            }
        });
        return false;
    },
    saveScheduleForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#scheduleTableContainer").ajaxTable("refresh");
            $("#scheduleModal").modal("hide");
            App.showToast("Harmonogram został zapisany", "bg-success");
        });
    },
    removeSchedule : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#scheduleTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Harmonogram został usunięty", "bg-success");
            }
        });
        return false;
    },
    saveCourtForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#courtTableContainer").ajaxTable("refresh");
            $("#courtModal").modal("hide");
            App.showToast("Postępowanie sądowe zostało zapisane", "bg-success");
        });
    },
    removeCourt : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#courtTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Postępowanie sądowe zostało usunięte", "bg-success");
            }
        });
        return false;
    },
    saveEnforcementForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#enforcementTableContainer").ajaxTable("refresh");
            $("#enforcementModal").modal("hide");
            App.showToast("Postępowanie egzekucyjne zostało zapisane", "bg-success");
        });
    },
    removeEnforcement : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#enforcementTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Postępowanie egzekucyjne zostało usunięte", "bg-success");
            }
        });
        return false;
    },
    savePaymentForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#paymentTableContainer").ajaxTable("refresh");
            $("#paymentModal").modal("hide");
            App.showToast("Wpłata została zapisana", "bg-success");
        });
    },
    removePayment : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#paymentTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Wpłata została usunięta", "bg-success");
            }
        });
        return false;
    },
    saveDocumentForm : function(obj) {
        var form = $(obj);
        Modal.onModalSubmitForm(form, function() {
            $("#documentTableContainer").ajaxTable("refresh");
            $("#documentModal").modal("hide");
            App.showToast("Dokument został zapisany", "bg-success");
        });
    },
    removeDocument : function(obj) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(obj).attr("action"),
            dataType: "json",
            type: "post",
            success: function() {
                $("#documentTableContainer").ajaxTable("refresh");
                $(obj).closest(".modal").modal("toggle");
                App.showToast("Dokument został usunięty", "bg-success");
            }
        });
        return false;
    },
    onModalShowEvent : function() {
        $("body").on("click", ".open-modal", function(e){
            e.preventDefault();
            Modal.onModalShowEvent($(this));
        });
    },
    afterCourtModalOpen : function() {
        $("#courtModal SELECT.form-select-2").trigger({
            type : "change",
            block : true,
        });
    },
    changeCourt : function(obj, event) {
        if (event.block == undefined || !event.block) {
            var info = $("OPTION:SELECTED", $(obj)).attr("data-info");
            if (info != undefined && info) {
                info = JSON.parse(info);
                
                var form = $(obj).closest("FORM");
                $("INPUT[name='court_street']", form).val(info.street);
                $("INPUT[name='court_zip']", form).val(info.zip);
                $("INPUT[name='court_city']", form).val(info.city);
            }
        }
    },
    editDocument : function() {
        $("#documentReplaceFileContainer", $("#documentModal")).removeClass("d-none");
        $("#documentUploadContainer", $("#documentModal")).addClass("d-none");
        $("INPUT[name='replace_file']", $("#documentModal")).val(0);
    },
    newDocument : function() {
        $("#documentReplaceFileContainer", $("#documentModal")).addClass("d-none");
        $("#documentUploadContainer", $("#documentModal")).removeClass("d-none");
        $("INPUT[name='replace_file']", $("#documentModal")).val(0);
    },
    changeDocumentFile : function() {
        $("#documentReplaceFileContainer", $("#documentModal")).addClass("d-none");
        $("#documentUploadContainer", $("#documentModal")).removeClass("d-none");
        $("INPUT[name='replace_file']", $("#documentModal")).val(1);
    }
};

$(document).ready(function() {
    Case.init();
});