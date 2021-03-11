// Get the needed HTML elements of the page
const search_input=document.getElementById("search-input");
const result_container=document.getElementById("result-container");
const button_clear=document.getElementById("button-clear");

// To search a show in the search bar : use of the JavaScript library Fuse
// Because show items have to be displayed in JavaScript, retreive shows from database via : get-shows.php
fetch('link-db-client/get-shows.php', {
	method: 'post',
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded'
	}
})
// Verify the received data or throw an error
.then((show_information) => {
  if (show_information.ok)  return show_information.json();
  else  window.location='error/error.html';
})
.then(show_information=> {

  // New Fuse object, search in show_information
  const fuse = new Fuse(show_information, options = {
    minMatchCharLength: 2,
    threshold:0.2,
    keys: ['show_name', 'show_artist']
  });

  // Add the auto-completed choices when user clicks or writes the text field of the search bar
  search_input.addEventListener("input", function() { addAutocompletedChoices(fuse); } );

  // Disable or enable the button by searching state
  search_input.addEventListener("input", disableButton );

  // Display choices according to search
  search_input.addEventListener("click", function() { addAutocompletedChoices(fuse); } );
})


/*
 *  Display choices according to search
 *  @param fuse : result of the search by Fuse.js
 */
function addAutocompletedChoices(fuse) {

  // Get the result of the research and close any already open lists of autocompleted values
  const result = fuse.search(search_input.value);

  result_container.innerHTML="";

  if(result.length>0 && search_input.value.length>1){

    // Loop on each result if there is a result and a value in the text field
    result.forEach(elem => {

      // Create a card for each matching element
      b = document.createElement("DIV");
      b.setAttribute("class","card mb-3 autocomplete-items");

      // Fill card
      b.innerHTML=`
        <div class='row no-gutters'>
          <div class="col-md-4">
            <img src="posters/`+elem.item.show_poster_file+`" class="card-img" alt="poster">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title">`+elem.item.show_name+`</h5>
              <p class="card-text mb-2">`+elem.item.show_artist+`</p>
              <a class='badge badge-primary' href="booking-date.php?show_id=`+elem.item.show_id+`">Réserver</a>
              <a class='badge badge-info' href="show-critics.php?show_id=`+elem.item.show_id+`">Voir les avis</a>
            </div>
          </div>
        </div>
      `;

      // Add card
      result_container.appendChild(b);
    })
  }
}

/*
 *  Close autocomplete list
 */
function closeAllLists() {
  result_container.innerHTML="";
  search_input.value="";
  button_clear.disabled=true;
}

/*
 *  Close autocomplete list
 */
function disableButton() {
  if (search_input.value.length==0) button_clear.disabled=true;
  else  button_clear.disabled=false;
}