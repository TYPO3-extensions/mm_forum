/**
 * Initializes the user search class.
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @return  void
 */
function UserSearch() {
	this.http = createRequestObject();
	this.userSearchVisible = false;
	
	this.selectedElement = -1;
}

userSearch = new UserSearch();

/**
 * Refreshes the user search window.
 * This function is intended to be called from the onkeydown event of the
 * search input field (i.e. every time something is done with the input field).
 * This function also detects if the enter key was hit and automatically inserts
 * the selected value into the input field if this is the case.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @param   Element obj   The calling object. This should be the input field in which
 *                        the search string is being entered.
 * @param   Event   event The event handler of the onkeydown event.
 * @return  void
 */
userSearch.refreshUserSearch = function(obj, event) {
	if(event.keyCode == 13 || event.keyCode == 10) {
		if(this.resultItems[this.selectedElement] != null)
			this.insertUserName(this.resultItems[this.selectedElement]);
		else this.insertUserName(obj.value);
	} else if(event.keyCode == 38) {
		this.scrollUp();
	} else if(event.keyCode == 40) {
		this.scrollDown();
	} else {
		this.getUserList(obj.value);
	}
}

/**
 * Sets a list item from the autocompletion list to hover mode.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @param   string id The id of the element that is to be selected.
 * @return  void
 */
userSearch.setHover = function(id) {
	if(this.selectedElement > -1)
		document.getElementById('userItem_'+this.selectedElement).className = 'tx-mmforum-pi3-quicksearch_item';
	this.selectedElement = id;
	document.getElementById('userItem_'+this.selectedElement).className = 'tx-mmforum-pi3-quicksearch_itemHover';
}

/**
 * Scrolls up in the autocompletion list. This is fired by hitting the UP key.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @return  void
 */
userSearch.scrollUp = function() {
	if(this.selectedElement == -1) return;
	else {
		document.getElementById('userItem_'+this.selectedElement).className = 'tx-mmforum-pi3-quicksearch_item';
		this.selectedElement --;
		
		if(this.selectedElement >= 0)
			document.getElementById('userItem_'+this.selectedElement).className = 'tx-mmforum-pi3-quicksearch_itemHover';
	}
}

/**
 * Scrolls down in the autocompletion list. This is fired by hitting the DOWN key.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @return  void
 */
userSearch.scrollDown = function() {
	if(this.selectedElement != -1)
		document.getElementById('userItem_'+this.selectedElement).className = 'tx-mmforum-pi3-quicksearch_item';
	this.selectedElement ++;
	
	var e = document.getElementById('userItem_'+this.selectedElement);
	
	if(e == null) {
		this.selectedElement --;
		e = document.getElementById('userItem_'+this.selectedElement);
	}
	
	e.className = 'tx-mmforum-pi3-quicksearch_itemHover';
}

/**
 * Asynchronically loads a list of usernames beginning with a certain string
 * from the server.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @param   String value The search string
 * @return  void
 */
userSearch.getUserList = function(value) {
	
		// Return if no search string was submitted
	if(value.length == 0) return;
	
		// Create HTTP Request object
	this.http = createRequestObject();
	
		// Define the event when an answer was recieved
	this.http.onreadystatechange = function() {
		if(userSearch.http.readyState == 4) {
					// Get the autocomplete div
				var e = document.getElementById('tx_mmforum_pi3_quicksearch');
				
					// If the search did not return anything, hide the autocomplete div
				if(userSearch.http.responseText == '') {
					e.style.display = 'none';
					userSearch.userSearchVisible = false;
				} else {
						// Parse JSON result
					var results = eval(userSearch.http.responseText);
					
					userSearch.resultItems = results;
					
					if(userSearch.selectedElement >= 0)
						var selectedFound = false;
					else var selectedFound = true;
					
						// Clear all old result items
					while(e.hasChildNodes())
						e.removeChild(e.firstChild);
						
						// Iterate through result items
					for(var i=0; i < results.length; i ++) {
							// Create new list element
						var item = document.createElement('li');
							// Create regular expression to match search string
						var expr = eval('/^('+value+')/');
						
							// Set new element's id
						item.setAttribute('id','userItem_'+i);
							// Set events
						item.setAttribute('onclick','userSearch.insertUserName("'+results[i]+'")');
						item.setAttribute('onmouseover','userSearch.setHover('+i+')');
							// If element is selected, change CSS class
						if(userSearch.selectedIndex == i) {
							item.className = 'tx-mmforum-pi3-quicksearch_itemHover';
							selectedFound = true;
						}
						else item.className = 'tx-mmforum-pi3-quicksearch_item';
						item.innerHTML = results[i].replace(expr,'<u>$1</u>');
						
						if((selectedFound == false) && (i+1 == results.length)) {
							userSearch.selectedElement = i;
							item.className = 'tx-mmforum-pi3-quicksearch_itemHover';
							selectedFound = true;
						}
							// Append new element to list
						e.appendChild(item);
					}
					
						// Unhide autocompletion div
					e.style.display = 'block';
					userSearch.userSearchVisible = true;
				}
		}
	}
	
		// Compose request parameters
	var requestParams = 'userSearch='+value;
	
		// Send request
	this.http.open('POST', this.ajaxURL, true);
	this.http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	this.http.send(requestParams);
		
	this.userNameMatch = value;
}

/**
 * Inserts a user name into the user name input field.
 * This function is triggered when a value is selected from the autocompletion
 * list and the user either clicks on it or hits enter.
 * 
 * @author  Martin Helmich <m.helmich@mittwald.de>
 * @version 2008-06-22
 * @param   String name The user name that is to be set as value for the input field.
 * @return  void
 */
userSearch.insertUserName = function(name) {
	document.getElementById('username').value = name;
	document.getElementById('tx_mmforum_pi3_quicksearch').style.display = 'none';
	this.userSearchVisible = false;
}

userSearch.hideUserSearch = function() {
	document.getElementById('tx_mmforum_pi3_quicksearch').style.display = 'none';
	this.userSearchVisible = false;	
}