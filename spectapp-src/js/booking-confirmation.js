// Get the needed HTML elements of the page
const promoBtn = document.getElementById("promoBtn");
const promoCodeInput = document.getElementById("promoCode");
const totalPriceContainer = document.getElementById("totalPriceContainer");
const totalPriceValue = document.getElementById("totalPriceValue");
const reservationPrice = document.getElementById("reservationPrice");
const addPromoCode = document.getElementById("addPromoCode");
const userInstruction = document.getElementById("userInstruction");
const colRigth = document.getElementById("colRigth");
const userForm = document.getElementById("userForm");

// Define global variable : price of reservation
const uniqueSeatPrice=5;
var resaPrice;

// To verify if the url has not been corrupted (and book an allready book seat), check if the selected seat is valid with the performance information of the database
fetch('link-db-client/get-performances.php', {
    method: 'post',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    }
})
// Verify the received data or throw an error
.then((performance_information) => {
    if (performance_information.ok)  return performance_information.json();
    else  window.location='error/error.html';
})
.then(performance_information=> {

    // Very uggly i am sorry
    fetch('link-db-client/get-promo-codes.php', {
        method: 'post',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    // Verify the received data or throw an error
    .then((promo_code_information) => {
        if (promo_code_information.ok)  return promo_code_information.json();
        else  window.location='error/error.html';
    })
    .then(promo_code_information=> {
        promoBtn.addEventListener('click',function(){submitPromo(promo_code_information)},false);
    })

    // Retreive the performance informations from the URL
    const performance_id=getUrlParameter("performance_id");
    const selected_seats=getUrlParameter('selected_seats');

    // Set and display initial price
    const nbSeats = selected_seats.split(",").length;
    resaPrice = uniqueSeatPrice*nbSeats;
    displayInitialPrice(resaPrice);

    // Check if the seats are valid or the user has corrupt his URL
    const performance_array_assoc=getPerformanceInfos(performance_information,performance_id);
    const all_performance_ids=getAllPerformanceIds(performance_information);
    
    // Verify URL has not been corrupted by user
    verifyUrlData(selected_seats,performance_array_assoc,all_performance_ids,performance_id);

    // Add a promo code
    promoCodeInput.addEventListener('input',isEnabledPromo);

    // For checkout
    userForm.addEventListener('submit',function() { onSubmit(performance_id,selected_seats); });

    // Ask confirmation to the user if he really want to leave the page when he leaves the page, except when he press a button to go to the next step
    window.onbeforeunload = function(e) { return "confirm"; };
})

/*
 *  Display initial price of the reservation
 *  @param price : total price at the begining
 */
function displayInitialPrice(price) {
    reservationPrice.innerHTML=price;
    totalPriceValue.innerHTML=price;
}

/*
 *  Get the information about the current performance
 *  @param fetchResult : fetch result coming from the database
 *  @param performance_id : id of the current performance
 */
function getPerformanceInfos(fetchResult,performance_id) {
	fetchResult.forEach(row => {
		if (row["performance_id"]==performance_id) {
			performance_array_assoc=row;
		}
	});
	return performance_array_assoc;
}

/*
 *  Get all the performance ids of the show table
 *  @param fetchResult : fetch result coming from the database
 */
function getAllPerformanceIds(fetchResult) {
    var all_performance_ids=[];
    fetchResult.forEach(row => {
        all_performance_ids.push(row["performance_id"]);
    });
    return all_performance_ids;
}

/*
 *  Verify the URL data hasn't been corrupted
 *  @param selected_seats : ids of the selected seats
 *  @param performance_array_assoc : info about the current performance coming from the database
 *  @param all_performance_ids : all performance ids of the database
 *  @param performance_id : id of the current performance
 */
function verifyUrlData(selected_seats,performance_array_assoc,all_performance_ids,performance_id) {

    if (!all_performance_ids.includes(performance_id))  window.location.href = 'error/error.html';

    const performance_reserved_seats_array=performance_array_assoc.performance_reserved_seats.split(",");
    const requested_seats_array=selected_seats.split(",");

    requested_seats_array.forEach(element => {
        if (performance_reserved_seats_array.includes(element)) window.location.href = 'error/error.html';
    });
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
 *  Create an unique id for reservations
 */
function makeUniqueId() {
	var characters='abcdefghijklmnopqrstuvwxyz';
	character=characters[Math.floor(Math.random() * Math.floor(26))];
	const start = Date.now().toString();
	return start.substr(6,8)+character;
}
 
/*
 *  Send reservation to the database
 *  @param data : data to send
 */
function sendReservationToDb(data) {

    fetch('link-db-client/reservation-sender.php', {
        method: 'post',
        body: data,
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(function (response) {
        if (!response.ok)   window.location.href="../error/error.html";
    })
}

/*
 *  Enable or disable the button to add a promo code
 */
function isEnabledPromo() {
    if (promoCodeInput.value.length>0)  promoBtn.disabled=false;
    else    promoBtn.disabled=true;
}

/*
 *  Add a promo code
 *  @param promo_code_information : fetch result of the promo-code table of the database
 */
function submitPromo(promo_code_information) {

    var codeIsValid=false;

    for (var i = 0; i < promo_code_information.length; i++) {

        var promoCodeName=promo_code_information[i].promo_code_name;
        var promoCodeValue=promo_code_information[i].promo_code_value;
        
        if (promoCodeInput.value==promoCodeName) {
            var newLi=document.createElement('li');
            newLi.setAttribute('class','list-group-item d-flex justify-content-between bg-light');
            newLi.setAttribute('id',promoCodeName);
            newLi.innerHTML=`
                <div>
                    <h6 class="text-success my-0">Code promotionnel</h6>
                    <small class="text-success">`+promoCodeName+`</small><br>
                    <a id='del-`+promoCodeName+`' class="delPromoCodeA badge badge-warning" onclick='delPromoCode(event)' >Retirer</a>
                </div>
                <span class="text-success">-`+promoCodeValue+`%</span>
            `;
            totalPriceContainer.parentNode.insertBefore(newLi,totalPriceContainer);
            var new_price = resaPrice-resaPrice*promoCodeValue/100;
            totalPriceValue.innerHTML=Math.round((new_price + Number.EPSILON) * 100) / 100
            addPromoCode.style.visibility='hidden';
            toastr["success"]("Votre code promotionnel a bien été ajouté.");

            codeIsValid=true;
        }
    }
    if (!codeIsValid) {
        promoCodeInput.value='';
        promoBtn.disabled=true;
        toastr["error"]("Code promotionnel incorrect.");
    }
}

/*
 *  Send reservation and diplay confirmation
 */
function onSubmit(performance_id,selected_seats) {
    const reservation_code = makeUniqueId();
    const client_email = document.getElementById('emailInput').value;

    // Send data to database
    const data="reservation_code="+reservation_code+"&client_email="+client_email+"&performance_id="+performance_id+"&reserved_seats="+selected_seats;            
    sendReservationToDb(data);

    addPromoCode.style.visibility='hidden';

    if (document.getElementsByClassName('delPromoCodeA')[0])    document.getElementsByClassName('delPromoCodeA')[0].remove();

    const firstname = document.getElementById('firstNameInput').value;
    const name = document.getElementById('lastNameInput').value;
    const cardType = document.querySelector('input[name="cardType"]:checked').value;
    const cardNumConf = "**** **** **** "+document.getElementById('ccNumberInput').value.substring(12);

    userInstruction.innerHTML='Votre réservation est validée !';

    colRigth.innerHTML=`
        <div class='jumbotron p-3'>
            <h4>Merci pour votre commande `+firstname+` !</h4>
            <h5 class="lead">Référence de votre commande à conserver : <span class="font-weight-bold">`+reservation_code+`</span></h5>
            <p class="mb-0"><small>Attention, ce code ainsi que votre email vous sera demandé si vous décidez d'annuler votre réservation.</small></p>
            <div class="mt-5 mb-5">
                <h5>Vos informations personnelles</h5>
                <p>`+firstname+` `+name+`</p>
                <p>`+client_email+`</p>
            </div>
            <div class="mt-5 mb-5">
                <h5>Votre paiement</h5>
                <p>Carte `+cardType+`  n° `+cardNumConf+`</p>
            </div>
            <a id='quitBtn' class="btn btn-primary" href="index.php" role="button">Retourner à l'accueil</a>
        </div>
    `;

    toastr["success"]("Vos sièges ont été réservés avec succès.");
    window.onbeforeunload = null;
}

/*
 *  Delete the current promo code
 *  @param event : click event when clicking on delete a promo code
 */
function delPromoCode(event) {
    document.getElementById(event.srcElement.id.substring(4)).remove();
    totalPriceValue.innerHTML=resaPrice;
    promoCodeInput.value='';
    promoBtn.disabled=true;
    addPromoCode.style.visibility='visible';
    toastr["warning"]("Votre code promotionnel a bien été retiré.");
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