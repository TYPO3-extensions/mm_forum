/**
 * @author mhelmich
 */

var Userfields_TypeText = Class.create();
Userfields_TypeText.prototype = {
	
	length: 32,
	limLength: 32,
	validate: 'none',
	
	defaultLength: 32,
	
		/**
		 * 
		 */
	initialize: function() {
		$('uf-text-length').setValue(this.defaultLength);
	},
	
	unlimitedSwitch: function() {
		if($('uf-text-length-unlim').checked == true)
			this.setLengthUnlimited();
		else this.setLengthLimited();
	},
	
	setLengthUnlimited: function() {
		this.limLength = this.length;
		this.length = -1;
		
		$('uf-text-length-unlim').checked = true;
		$('uf-text-length').disabled = true;
	},
	
	setLengthLimited: function() {
		if(this.length == -1) this.length = this.limLength;
		
		if(this.length < 1) this.length = 1;
		$('uf-text-length').value = this.length;
		
		$('uf-text-length-unlim').checked = false;
		$('uf-text-length').disabled = false;
	},
	
	setValidate: function(v) {
		if($('uf-text-vld-'+v) != null) {
			$('uf-text-vld-'+v).checked = true;
			this.validate = v;
		}
	},
	
	setLength: function(l) {
		
		if(l == '') {
			this.setLength(-1);
		} else if(l > 0 || l == -1) {
			
			if(l == -1) this.setLengthUnlimited();
			else {
				this.length = l;
				this.setLengthLimited();
			}
			
		}
	}
	
}
