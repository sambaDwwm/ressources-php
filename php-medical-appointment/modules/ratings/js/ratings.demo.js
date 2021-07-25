// Ajax Star Rating Script - http://coursesweb.net
var sratings = Array();		  // store the items with rating
var ar_elm = Array();	   	  // store the items that will be send to rtgAjax()
var srated = '';		      // store the rated value that will be send to rtgAjax()
var i_elm = 0;				  // Index for elements aded in ar_elm
var itemrated_rtg = '';       // store the rating of rated item
var rating_elm = '';
var rating_totalrate = '';
var rating_nrrates = '';

// gets all DIVs, then add in $ar_elm the DIVs with class="ratings_stars", and ID which begins with "rt_", and sends to rtgAjax()
var getRtgsElm = function () {
  obj_div = document.getElementsByTagName('div');
  for(var i=0; i<obj_div.length; i++) {
    // if contains class and id
    if(obj_div[i].className && obj_div[i].id) {
	  var val_id = obj_div[i].id;
      // if class="ratings_stars" and id begins with "rt_"
      if(obj_div[i].className=='ratings_stars' && val_id.indexOf("rt_")==0) {
	    sratings[val_id] = obj_div[i];
	    ar_elm[i_elm] = val_id;
	    i_elm++;
	  }
    }
  }
  // Daca sunt elemente cu notari, le trimite toate la rtgAjax()
  if(ar_elm.length>0) rtgAjax(ar_elm, srated);      // if items in $ar_elm pass them to rtgAjax()
};

// add the ratting data to element in page
function addRtgData(elm, totalrate, nrrates, renot) {
  var avgrating = (nrrates>0) ? totalrate/nrrates : 0;      // sets average rating and length of area with stars

  // convert in string, if has more that 3 characters, convert it in number with decimals
  avgrating = avgrating+'';
  if(avgrating.length>3) {
    avgrating *= 1; avgrating = avgrating.toFixed(1);
  }
  var star_n = 22*avgrating;

  // HTML code for rating, add 10 SPAN tags, each one for a half of star, only if renot=0
  var d_rtg = '';
  if(renot==0) {
    for(var i=0; i<5; i++) {
      d_rtg += '<span id="d_'+i+'">&nbsp;</span>';
    }
    d_rtg = '<div class="d_rtg">'+d_rtg+'</div>';
	rating_elm = elm;
	rating_totalrate = totalrate;
	rating_nrrates = nrrates;
  }

  // Create and add HTML with stars, and rating data
  var htmlrtg = '<div class="stars">';
	  htmlrtg += '<div class="star_n" style="width:'+star_n+'px;">&nbsp;</div>';
	  htmlrtg += d_rtg+'('+avgrating+') '+nrrates+' '+RatingsVoc._MSG["votes"];
	  htmlrtg += '</div>';
	  
  if(sratings[elm]) sratings[elm].innerHTML = htmlrtg;
}

/*** Ajax ***/
// create the XMLHttpRequest object, according to browser
function get_XmlHttp() {
  var xmlHttp = null;           // will stere and return the XMLHttpRequest
  if(window.XMLHttpRequest) xmlHttp = new XMLHttpRequest();     // Forefox, Opera, Safari, ...
  else if(window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");     // IE
  return xmlHttp;
}

// sends data to PHP and receives the response
function rtgAjax(elm, ratev) {
  var cerere_http = get_XmlHttp();		// get XMLHttpRequest object

  // define data to be send via POST to PHP (Array with name=value pairs)
  var datasend = Array();
  for(var i=0; i<elm.length; i++) datasend[i] = 'elm[]='+elm[i];
  // joins the array items into a string, separated by '&'
  datasend = datasend.join('&')+'&rate='+ratev;
  cerere_http.open("POST", 'modules/ratings/lib/ratings.php', true);			// crate the request
  cerere_http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");    // header for POST
  cerere_http.send(datasend);		//  make the ajax request, passing the data
  // checks and receives the response
  cerere_http.onreadystatechange = function() {
    if (cerere_http.readyState == 4) {
      // receives a JSON with one or more item:['totalrate', 'nrrates', renot]
      eval("var jsonitems = "+ cerere_http.responseText);
      // if jsonitems is defined variable
      if (jsonitems) {
        // parse the jsonitems object
        for(var rtgitem in jsonitems) {
          var renot = jsonitems[rtgitem][2];		// determine if the user can rate or not
			// calls function that shows rating
			addRtgData(rtgitem, jsonitems[rtgitem][0], jsonitems[rtgitem][1], renot);	
        }
      }
      // if renot is undefined or 2 (set to 1 item rated per day), after vote, removes the element for rate from each elm (removing childNode 'd_rtg')
      if(ratev != '' && (renot == undefined || renot == 2)) {
        if(renot == undefined) document.getElementById(elm[0]).innerHTML = itemrated_rtg;
      }
	}
  }
}

setTimeout("getRtgsElm()", 88);		// calls getRtgsElm() at 88 milliseconds after page loads