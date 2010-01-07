	/**
	 * The tx_mmforum_Editor class is a helper class providing functionality
	 * for the post creation editor. It is meant to replace the old mm_forum.js
	 * file which included a lot of redundancies.
	 *
	 * @author    Martin Helmich <m.helmich@mittwald.de>
	 * @copyright Mittwald CM Service GmbH & Co. KG
	 * @version   0.1.8-090409
	 */
var tx_mmforum_Editor = Class.create();
tx_mmforum_Editor.prototype = {

		/* The ID of the editor form element */
	editorFieldId: "tx_mmforum_editor",

		/* Initializes the object. Actually does nothing. */
	initialize: function() {
		
	},

		/**
		 * Inserts a smily at the current cursor position of the
		 * message form field.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090409
		 * @param   smilie The smily to be inserted.
		 * @return  void
		 **/
	insertSmilie: function(smilie) {
		var editor = this.getEditorElement();

			/* Internet Explorer */
		if(typeof document.selection != 'undefined') {
			if (editor.createTextRange && editor.caretPos) {
				var caretPos = editor.caretPos;
				caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? smilie + ' ' : smilie;
			} else editor.value  += smilie;

			editor.focus();

			/* Gecko */
		} else if(typeof editor.selectionStart != 'undefined') {
			start	= editor.selectionStart;
			end		= editor.selectionEnd;

			editor.value = editor.value.substr(0, start) + smilie + editor.value.substr(end);

			editor.focus();
			editor.selectionStart = end + smilie.length;
			editor.selectionEnd = editor.selectionStart;

			/* Other */
		} else {
			editor.value += smilie;
			editor.focus();
		}
	},

		/**
		 * Applies a set of bbcode tags to the selected part of the message text.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090409
		 * @param   open  The opening bbcode tag
		 * @param   close The closing bbcode tag
		 * @return  void
		 */
	applyBBCode: function(open, close) {
		var editor = this.getEditorElement();

			/* Internet Explorer */
		if(typeof document.selection != 'undefined') {

			var range = document.selection.createRange();
			var insText = range.text;

			if (insText == '') {
				editor.value += open + close;
				editor.focus();
			} else {
				range.text = open + insText + close;
				/* Anpassen der Cursorposition */
				range = document.selection.createRange();

				range.moveStart('character', open.length + insText.length + close.length);
				range.select();
			}
		}

			/* Gecko */
		else if(typeof editor.selectionStart != 'undefined') {
			start	= editor.selectionStart;
			end		= editor.selectionEnd;

			var insText = open + editor.value.substring(start, end) + close;
			editor.value = editor.value.substr(0, start) + insText + editor.value.substr(end);

			editor.focus();
			editor.selectionStart = end + open.length;
			editor.selectionEnd = editor.selectionStart;

			/* Other */
		} else {
			editor.value += open + close;
			editor.focus();
		}

	},

		/**
		 * Gets the editor form element.
		 *
		 * @author  Martin Helmich <m.helmich@mittwald.de>
		 * @version 0.1.8-090409
		 * @return  The editor form field
		 */
	getEditorElement: function() {
		return $(this.editorFieldId);
	}

}