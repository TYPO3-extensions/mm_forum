/**
 * @author mhelmich
 */

var Userfields_GeneralSettings = Class.create();
Userfields_GeneralSettings.prototype = {
	
	langNum: 0,
	languageFields: [],
	delPath: "",
	required: false,
	isPrivate: false,
	isUnique: false,
	
		/**
		 * 
		 */
	initialize: function() {
		this.languageFields[0] = $('uf-label-0');
	},
	
		/**
		 * Adds a language field.
		 * @param {Object} content
		 * @param {Object} key
		 */
	addLanguage: function(content, key) {
		
			/* Check if the default language is being set */
		if(key == 'default') {
			this.getLangContentField(0).value = content;
		}
		
			/* Check if the previous fields have been filled in */
		else if (this.langNum == 0 || (this.getLangContent(this.langNum).length > 0 && this.getLangKey(this.langNum).length > 0)) {
		
			var labelDiv = $('userfield-labels');
			this.langNum++;
			
			var newDiv = document.createElement('div');
			newDiv.id = 'langlabel-' + this.langNum;
			
				/* Create label content field */
			var newInput_content = document.createElement('input');
			newInput_content.setAttribute('name', 'tx_mmforum_userfields[label][' + this.langNum + '][content]');
			newInput_content.setAttribute('onchange', 'this.style.border=\'\';');
			newInput_content.size = 32;
			if (content != null) {
				newInput_content.value = content;
			}
			
				/* Create language key field */
			var newInput_lang = document.createElement('input');
			newInput_lang.setAttribute('name', 'tx_mmforum_userfields[label][' + this.langNum + '][lang]');
			newInput_lang.setAttribute('onchange', 'this.style.border=\'\';');
			newInput_lang.size = 4;
			if (key != null) {
				newInput_lang.value = key;
			}
			
				/* Create delete button */
			var newInput_delete = document.createElement('img');
			newInput_delete.src = this.delPath;
			newInput_delete.style.verticalAlign = 'middle';
			newInput_delete.setAttribute('onclick', 'userfields.removeLanguage(' + this.langNum + ')');
			
				/* Add input fields to surrounding div */
			newDiv.appendChild(newInput_content);
			newDiv.appendChild(newInput_lang);
			newDiv.appendChild(newInput_delete);
			
			labelDiv.appendChild(newDiv);
			
			this.languageFields[this.langNum] = newDiv;
			
		} else {
			if(this.getLangContent(this.langNum).length == 0) {
				this.getLangContentField(this.langNum).style.border = '1px solid red';
			}
			if(this.getLangKey(this.langNum).length == 0) {
				this.getLangKeyField(this.langNum).style.border = '1px solid red';
			}
		}
		
	},
	
		/**
		 * 
		 * @param {Object} id
		 */
	removeLanguage: function(id) {
		Element.remove(this.languageFields[id]);
	},
	
		/**
		 * 
		 * @param {Object} id
		 */
	getLangContentField: function(id) {
		return this.languageFields[id].childNodes[0];
	},
	
		/**
		 * 
		 * @param {Object} id
		 */
	getLangKeyField: function(id) {
		return this.languageFields[id].childNodes[1];
	},
	
		/**
		 * 
		 * @param {Object} id
		 */
	getLangContent: function(id) {
		return this.getLangContentField(id).getValue();
	},
	
		/**
		 * 
		 * @param {Object} id
		 */
	getLangKey: function(id) {
		return this.getLangKeyField(id).getValue();
	},
	
	setRequired: function(r) {
		this.required = r;
		
		if(r == true)
			$('uf-required').checked = true;
		else $('uf-required').checked = false;
	},
	
	setPrivate: function(p) {
		this.isPrivate = p;
		
		if(p == true)
			$('uf-private').checked = true;
		else $('uf-private').checked = false;
	},
	
	setUnique: function(u) {
		this.isUnique = u;
		
		if(u == true)
			$('uf-unique').checked = true;
		else $('uf-unique').checked = false;
	}
	
}

