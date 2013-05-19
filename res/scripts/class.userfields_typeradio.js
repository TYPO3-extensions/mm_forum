/**
 * @author mhelmich
 */

var Userfields_TypeRadio = Class.create();
Userfields_TypeRadio.prototype = {
	
	valueId: 0,
	values: [],
	delPath: '',
	
	initialize: function() {
		this.values[0] = $('uf-radio-value-0');
	},
	
	addValue: function(text) {
		if(this.valueId == 0 && this.getValue(0)=='' && text != null)
			this.setValue(0,text);
		else {
			
			if (this.getValue(this.valueId) == '') {
				this.getInput(this.valueId).style.border = '1px solid red';
			} else {
				this.valueId++;
				
				var newDiv = document.createElement('div');
				var newInput = document.createElement('input');
				
				newInput.type = 'text';
				newInput.size = 32;
				newInput.setAttribute('onchange','this.style.border=\'\';');
				newInput.name = 'tx_mmforum_userfields[radio][value]['+this.valueId+']';
				
				if(text != null)
					newInput.value = text;
				
				/* Create delete button */
				var newInput_delete = document.createElement('img');
				newInput_delete.src = this.delPath;
				newInput_delete.style.verticalAlign = 'middle';
				newInput_delete.setAttribute('onclick', 'typeRadio.removeValue(' + this.valueId + ')');
				
				newDiv.appendChild(newInput);
				newDiv.appendChild(newInput_delete);
				
				$('uf-radio-values').appendChild(newDiv);
				
				this.values[this.valueId] = newDiv;
			}
		}
	},
	
	removeValue: function(id) {
		this.values[id].remove();
	},
	
	getInput: function(id) {
		return this.values[id].childNodes[0];
	},
	
	getValue: function(id){
		return this.values[id].childNodes[0].getValue();
	},
	
	setValue: function(id,value) {
		this.values[id].childNodes[0].setValue(value);
	}
	
}
