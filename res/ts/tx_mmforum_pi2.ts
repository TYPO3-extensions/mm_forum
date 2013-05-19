#
# tx_mmforum_pi2 // User Registration
##################################
plugin.tx_mmforum_pi2 < plugin.tx_mmforum
plugin.tx_mmforum_pi2 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi2/class.tx_mmforum_pi2.php
		userFunc				= tx_mmforum_pi2->main

	# Template file
		templateFile			= {$plugin.tx_mmforum.style_path}/fe_user_reg/fe_user_registrierung.html
		
	# Image border
        img_border 				= {$plugin.tx_mmforum.img_border}
		
	# The email address of the support team
		supportMail 			= {$plugin.tx_mmforum.support_mail}
		
    # Email sender
    	Emailsender 			= {$plugin.tx_mmforum.support_mail} 
        
    # Allowed characters for user name
        username_allowed        = a-z0-9äüö_.ß
        
    # Minimal length for user names
        username_minLength      = 3
    
    # Maximal length for user names
        username_maxLength      = 30
	
	# Button configuration
		buttons {
			small.file {
				XY = 75,25
				50.offset = 75-[50.w],3
			}
			small.stdWrap.wrap = <div class="tx-mmforum-pi2-textbutton"> | </div>
			normal.stdWrap.wrap = <div class="tx-mmforum-pi2-textbutton"> | </div>
		}
		
	# Use captcha
		useCaptcha				= {$plugin.tx_mmforum.useCaptcha}
		
	# Validation settings
		validation {
			www = /^(https?:\/\/)?([a-z0-9-]+\.)+([a-z0-9]+)(\/[!~*\'\(\)a-zA-Z0-9;\/\\\?:\@&=\+\$,%#\._-]*)*$/
		}

	# Wrap for fields with invalid input
		errorwrap = <div class="error"> | </div>

	# Settings for required fields
		required {
			fields = {$plugin.tx_mmforum.requiredFields}
			fieldWrap = <strong>|&nbsp;(*)</strong>
		}
}