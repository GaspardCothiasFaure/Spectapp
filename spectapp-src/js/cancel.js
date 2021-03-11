// Get the needed HTML elements of the page
const cancelation_confirmation_btn=document.getElementById("cancelation-confirmation-btn");
const cancelation_confirmation_container=document.getElementById("cancelation-confirmation-container");

// When the cancelation has been done, hide the cancelation button
hideCancellationBtn();

// Ask confirmation to the user if he really want to leave the page when he leaves the page, except when he press a button to go to the next step
preventFromWrongConfirmation(cancelation_confirmation_btn);

/*
 *  Hide the cancelation button if the cancelation has been done
 */
function hideCancellationBtn() {
    if(getUrlParameter("cancelation")=='true'){
        cancelation_confirmation_container.remove();
        toastr["success"]("Votre réservation a bien été supprimée.");
        window.onbeforeunload = null;
    }
    else    window.onbeforeunload = function(e) { return "confirm"; };
}

/*
 *  Retreive the GET parameter in URL
 *  @param name : name of the GET parameter
 */
function getUrlParameter(name){
    if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
    return decodeURIComponent(name[1]);
}

/*
 *  Stop asking confirmation to quit after cancellation confirmation
 *  @param list_btn : button to quit the page
 */
function preventFromWrongConfirmation(btn) {
    btn.addEventListener("click", function(event){
        window.onbeforeunload = null;
    },true);
}

// Define toaster options
toastr.options = {
    "closeButton": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}