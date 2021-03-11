// Get the needed HTML elements of the page
const map = document.querySelector(".map");
const next_step=document.getElementById("next-step");
const nb_seats_selected_span=document.getElementById("nb-seats-selected-span");
const map_container=document.getElementById("map-container");

// Define global variables
var show_line;
var nb_seats;

// Seats displayed in JavaScript, retreive performances from database via : get-performances.php
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

	// Ask confirmation to the user if he really want to leave the page when he leaves the page, except when he press a button to go to the next step
	window.onbeforeunload = function(e) { return "confirm"; };
	preventFromWrongConfirmation(next_step);

	// Create map
	createMap();	

	// Display selected seats on the map
	map.addEventListener("click", function(e) {
		updateSelectedSeats(e);
		nb_seats=getNumSeatsSelected();
	});

	// Retreive the performance informations from the database
	const performance_id=getUrlParameter("performance_id");
	const performance_array_assoc=getPerformanceInfos(performance_information,performance_id);

	// Detect if the performance is full : if yes, URL was corrupted, throw an error
	isPerfromanceFull(performance_array_assoc);

	// Display reserved seats on the map
	if(performance_array_assoc["performance_reserved_seats"]){
		const reserved_seats=performance_array_assoc["performance_reserved_seats"].split(",");
		setOccupiedSeats(reserved_seats);
	}

	// Display non reservables seats if the performace is under covid protocol
	const performance_covid_code=performance_array_assoc["performance_covid_code"];
	if (performance_covid_code==1)	setNonReservableSeats(getCovidNonReservablesSeats());

	// Go to next step (booking-confirmation.php) if there is a seat selected
	next_step.addEventListener('click',function (e) {

		if(getNumSeatsSelected()==0)	toastr["error"]("Vous n'avez pas selectionné de place.");
		else	window.location.href='booking-confirmation.php?performance_id='+performance_id+'&selected_seats='+getIdsSeatsSelected();
	})
})

/*
 *  Update selected seats on the map
 */
function updateSelectedSeats(e) {
	if(nb_seats==5 && (!e.target.classList.contains("selected"))){
		toastr["error"]("Vous ne poivez pas réserver plus de 5 places.");
		return;
	}
	if (e.target.classList.contains("seat") && !e.target.classList.contains("occupied"))	e.target.classList.toggle("selected");
}

/*
 *  Dynamic creation of the map
 */
function createMap() {

	for (let row = 0; row < 7; row++) {
		const div_row = document.createElement("div");
		div_row.className = 'row';
		map.appendChild(div_row);
	
		for (let id = 1; id <= 11; id++) {
			const div_seat = document.createElement("div");
			div_seat.className = 'seat free';
			div_seat.id=(11*row+id).toString();
			div_row.appendChild(div_seat);
		}
	}
}

/*
 *  Set initial occupied seats on the map
 *	@param ids_seats_occupied : list of occuped seat ids
 */
function setOccupiedSeats(ids_seats_occupied) {
	ids_seats_occupied.forEach(id_seat => {
		document.getElementById(id_seat).classList.toggle("occupied");
		document.getElementById(id_seat).classList.remove("free");
	});
}

/*
 *  Set initial non reservable seats on the map
 *	@param ids_non_reservable_seats : list of non reservable seat ids
 */
function setNonReservableSeats(ids_non_reservable_seats) {
	ids_non_reservable_seats.forEach(id_seat => {
		document.getElementById(id_seat).classList.toggle("non-reservable");
		document.getElementById(id_seat).classList.remove("free");
	});
}

/*
 *  Get the non reservable seats ids on the map in case of covid protocol
 */
function getCovidNonReservablesSeats() {
	var array1=[2,5,7,10];
	var array2=[];
	for (let a = 1; a < 7; a++) {
		array1.forEach(num => {
			array2.push(num+11*a);
		});
	}
	return array1.concat(array2);
}

/*
 *  Update numbers of seats selected
 */
function getNumSeatsSelected() {
	const selectedSeats=document.getElementsByClassName("selected");
	const selectedSeatsCount = selectedSeats.length;
	nb_seats_selected_span.innerHTML=selectedSeatsCount;
	return selectedSeatsCount;
}

/*
 *  Update ids of seats selected
 */
function getIdsSeatsSelected() {
	const selectedSeats=document.getElementsByClassName("selected");
	var requested_seats_id=[];
	for (let i = 0; i < selectedSeats.length; i++) {
		requested_seats_id.push(selectedSeats[i].id);
	}
	return requested_seats_id.toString();
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
 *  Retreive the performance informations from the database
 *  @param fetchResult : result of the fetch coming from the database
 *  @param performance_id : current performance id
 */
function getPerformanceInfos(fetchResult,performance_id) {

	var performance_array_assoc;

	fetchResult.forEach(row => {

		if (row["performance_id"]==performance_id) {
			performance_array_assoc=row;
		}
	});
	
	return performance_array_assoc;
}

/*
 *  Stop asking confirmation to quit
 *  @param list_btn : button to quit the page
 */
function preventFromWrongConfirmation(btn) {
    btn.addEventListener("click", function(event){
		window.onbeforeunload = null;
	},true);
}

/*
 *  Detect if the performance is full : if yes, URL was corrupted, throw an error
 *  @param performance_array_assoc : row in database of the current performance
 */
function isPerfromanceFull(performance_array_assoc) {
	const nb_seats_occupied=performance_array_assoc["performance_reserved_seats"].split(",").length;
	const cc=performance_array_assoc["performance_covid_code"];
	if ((nb_seats_occupied==77 && cc==0) || (nb_seats_occupied==49 && cc==1)) {
		window.location.href="error/error.html";
	}
}

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