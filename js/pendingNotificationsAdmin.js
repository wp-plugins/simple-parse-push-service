jQuery(document).ready( function($) {
	/*
	 * Variables
	 * ====================== */
    var $checkSelectAll    = $('#selectall');
    var $checkboxesByClass = $('.simpar_ckb');
    var $checkboxes        = $("input[type='checkbox']");
    var $btnsSubmit        = $("input[type='submit']");
    var $btnDeleteAsk      = $('#simpar_delete_ask');
    var $btnDeleteConfirm  = $('#simpar_delete_confirm');
    var $btnDeleteDeny 	   = $('#simpar_delete_deny');
    var $btnWarningFSubmit = $('#warning_form_submit');


	/*
	 * Initializations
	 * ====================== */
    $btnsSubmit.attr("disabled", true);
    $btnDeleteAsk.attr("disabled", true);


	/*
	 * Functionality
	 * ====================== */
    $checkSelectAll.click(function(event) {  
        if(this.checked) { 
            $checkboxesByClass.each(function() {
                this.checked = true;  
            });
        }else{
            $checkboxesByClass.each(function() {
                this.checked = false; 
            });         
        }
    });

    $checkboxes.on('click', function() {
    	var makeDisabled = !$checkboxes.is(":checked");

	    $btnsSubmit.attr("disabled", makeDisabled);
	    $btnDeleteAsk.attr("disabled", makeDisabled);
	    if (makeDisabled) {
	    	$btnDeleteConfirm.hide();
	    }
	});

    $btnDeleteAsk.on('click', function(event) {
    	event.preventDefault();
    	$btnDeleteConfirm.show();
    });

    $btnDeleteDeny.on('click', function(event) {
    	event.preventDefault();
    	$btnDeleteConfirm.hide();
    });

    $btnWarningFSubmit.on('click', function(event) {
        event.preventDefault();
        $('#simpar_warning_form').submit();
    });

});