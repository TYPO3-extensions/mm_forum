/**
 * @author mhelmich
 */

var Userfields_TypeController = Class.create();
Userfields_TypeController.prototype = {
	
	currentType: 'text',
	types: ['text','radio','custom','select','checkbox'],
	
		/**
		 * 
		 */
	initialize: function() {
		this.hideAllTypes();
	},
	
	setType: function(type) {
		this.currentType = type;
		
		this.hideAllTypes();
		this.getSettingsDiv(type).show();
		
		$('userfield-type-'+type).checked = true;
	},
	
	hideAllTypes: function() {
		for(var i=0; i < this.types.length; i ++) {
			this.getSettingsDiv(this.types[i]).hide();
		}
	},
	
	getSettingsDiv: function(type) {
		return $('userfield-typediv-'+type);
	}
	
}
