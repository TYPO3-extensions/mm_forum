/**
 * @author mhelmich
 */

var Validator = Class.create();
Validator.prototype = {
	
		/**
		 * 
		 */
	initialize: function() {

	},
	
	parseNumber: function(e) {
		e.setValue(e.getValue().replace(/[^0-9]/g, ""));
	}
	
}

var validator = new Validator();
