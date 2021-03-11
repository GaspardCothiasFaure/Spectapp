// Get the needed HTML elements of the page
const delDateInputLink = document.getElementById("delDateInputLink");
const addDateInputLink = document.getElementById("addDateInputLink");
const datePickerContainer = document.getElementById("datePickerContainer");
const imgOverview = document.getElementById("imgOverview");
const overviewButton = document.getElementById("overviewButton");
const posterFileInput = document.getElementById("posterFileInput")

var ct=1;

// Function to updat file modal when a file is inputed
document.querySelector('.custom-file-input').addEventListener('change',onFileChange);

/*
 *  Add a date picker to add an other performance
 */
function addDatePicker() {
    ct++;
    const newDatePicker=document.createElement("div");
    newDatePicker.setAttribute("class","performance-choices card bg-light m-1 text-center");
    newDatePicker.innerHTML="<div class='card-body p-0'><input type='datetime-local' name='showDates[]' required><div class='custom-control custom-radio'><input id='ncp_"+ct+"' name='covidProtocol["+(ct-1)+"]' type='radio' class='custom-control-input' value='0' checked required><label class='custom-control-label' for='ncp_"+ct+"'>Pas de protocole COVID</label></div><div class='custom-control custom-radio'><input id='cp_"+ct+"' name='covidProtocol["+(ct-1)+"]' type='radio' class='custom-control-input' value='1' required><label class='custom-control-label' for='cp_"+ct+"'>Protocole COVID</label></div></div>"
    newDatePicker.id="datePicker_"+ct;
    datePickerContainer.appendChild(newDatePicker);
    if (ct==500)    addDateInputLink.style.visibility='hidden';
    delDateInputLink.style.visibility='visible';
}

/*
 *  Delete a date picker in case we add too much
 */
function delDatePicker() {
    document.getElementById("datePicker_"+ct).remove();
    ct--;
    addDateInputLink.style.visibility='visible';
    if (ct==1)  delDateInputLink.style.visibility='hidden';
}


/*
 *  load poster file in the modal in case of overview
 *  @param event : loading a file as poster event
 */
function loadPoster(event) {
    if (!event.target.files[0]) overviewButton.disabled=true;
    else{
        overviewButton.disabled=false;
        imgOverview.src = URL.createObjectURL(event.target.files[0]);
    }
}

/*
 *  Function to updat file modal when a file is inputed
 *  @param e : change file event
 */
function onFileChange(e) {
    if (posterFileInput.files[0]) {
        var fileName = posterFileInput.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    }
    else{
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = "Choisir un fichier";
    } 
}

//Bootstrap JQuery function to display modal
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})

/*
 *  Retreive the GET parameter in URL
 *  @param name : name of the GET parameter
 */
function getUrlParameter(name){
	if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) return decodeURIComponent(name[1]);
}

// Display toaster according to GET parameter
if (getUrlParameter('newshow')=="t")    toastr["success"]("Le spectacle a bien été ajouté.");
if (getUrlParameter('newshow')=="i")    toastr["error"]("Erreur lors de l'ajout. Vous ne pouvez pas ajouter deux performances à la même heure.");
if (getUrlParameter('error')=="f")  toastr["error"]("Erreur lors de l'ajout. Il y a eu une erreur lors du téléchargement de l'affiche. Veuillez réessayer.");

// Define toaster options
toastr.options = {
    "closeButton": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}