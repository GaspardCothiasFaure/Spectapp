// Get the needed HTML elements of the page
var calendar = document.getElementById('calendar');

// Retreive information of performances from the database
// Because performances have to be displayed in JavaScript, retreive shows from database via : get-performances.php
fetch('link-db-client/get-performances.php', {
	method: 'post',
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded'
	}
})
// Verify the received data or throw an error
.then((show_performances_info) => {
    if (show_performances_info.ok)  return show_performances_info.json();
    else  window.location='error/error.html';
})
.then(show_performances_info=> {

    // Get the id of the show from URL
    const show_id=getUrlParameter("show_id");
    
    // Retreive all perfromance information of the show
    const performances_info=getShowInfo(show_performances_info,show_id);

    // Creation of the calendar of performance dates
    createCalendar(performances_info);

    // Ask confirmation to the user if he really want to leave the page when he leaves the page, except when he press a button to go to the next step
    window.onbeforeunload = function(e) { return "confirm"; };
    preventFromWrongConfirmation(calendar);
})

/*
 *  Create calendar with perfomance dates displayed
 *  @param performances_info : row of the current database in the database
 */
function createCalendar(performances_info) {

    var jsonEvents = [];
    var setColor;

    //Creation of a json for the performance
    performances_info.forEach(performance => {
        if (isFullPerformance(performance)) {
            jsonEvents.push({
                start: performance.performance_date,
                color:'red'
            });
        }
        else{
            if (performance.performance_covid_code==0)  setColor='green';
            else    setColor='yellow';
            jsonEvents.push({
                start: performance.performance_date,
                url: "booking-seat.php?performance_id="+performance.performance_id,
                color:setColor
            });
        }
    });

    // Append options to calendar
    var cal = new FullCalendar.Calendar(calendar, {
        initialDate: jsonEvents[0].start,
        events:jsonEvents,
        locale:'fr',
        fixedWeekCount: false
    });
    
    // Display calendar
    cal.render();
}

/*
 *  Retreive the GET parameter in URL
 *  @param name : name of the GET parameter
 */
function getUrlParameter(name){
	if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) return decodeURIComponent(name[1]);
}

/*
 *  Retreive the performance informations from the database
 *  @param fetchResult : fetch result coming from the database
 *  @param show_id : id of the current show
 */
function getShowInfo(fetchResult,show_id) {
	var show_array_assoc=[];

	fetchResult.forEach(row => {
		if (row["show_id"]==show_id) {
			show_array_assoc.push(row);
		}
    });
    
	return show_array_assoc;
}

/*
 *  Return if a performance is full
 *  @param performance_info : row of the current performance in the database
 */
function isFullPerformance(performance_info) {
    const reserved_seats=performance_info.performance_reserved_seats;
    const split_reserved_seats= reserved_seats.split(",");
    if((split_reserved_seats.length==77 && performance_info.performance_covid_code==0) || (split_reserved_seats.length==49 && performance_info.performance_covid_code==1)) return true;
    else    return false;
}

/*
 *  Stop asking confirmation to quit
 *  @param list_btn : button to quit the page
 */
function preventFromWrongConfirmation(elem) {
    elem.addEventListener("click", function(event){
        window.onbeforeunload = null;
    },true);
}