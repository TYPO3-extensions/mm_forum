#
# tx_mmforum_pi5 // Private Data
##############################
plugin.tx_mmforum_pi5 < plugin.tx_mmforum
plugin.tx_mmforum_pi5 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi5/class.tx_mmforum_pi5.php
		userFunc				= tx_mmforum_pi5->main
		
	# Template file
		template			= {$plugin.tx_mmforum.style_path}/change_user/main.html
		
	# List of fields the user is allowed to edit
		userFields			= name,zip,city,www,tx_mmforum_occ,tx_mmforum_interests,email,tx_mmforum_yim,tx_mmforum_msn,tx_mmforum_aim,tx_mmforum_icq,tx_mmforum_skype,tx_mmforum_user_sig,tx_mmforum_pmnotifymode

	# Configuration for the avatar image. See TSRef.
		avatar = IMAGE
		avatar {
			file.maxW = 80
			file.maxH = 80
			wrap =
			imageLinkWrap = 0
			imageLinkWrap {
				enable = 1
				bodyTag = <body bgColor="#dddddd">
				wrap = <a href="javascript:close();"> | </a>
				width = 1000m
				height = 800
				JSwindow = 1
				JSwindow.newWindow = 1
				JSwindow.expand = 17,20
			}
		}
		
	# Icons
		icons {
			settings = IMAGE
			settings.file = {$plugin.tx_mmforum.style_path}/img/default/settings_icon.png
			
			settings2 = IMAGE
			settings2.file = {$plugin.tx_mmforum.style_path}/img/default/wrench.png
			
			avatar = IMAGE
			avatar.file = {$plugin.tx_mmforum.style_path}/img/default/avatar_icon.png
			
			password = IMAGE
			password.file = {$plugin.tx_mmforum.style_path}/img/default/password_icon.png
		}
		
	# Minimum password length
		minPasswordLength = 6
	
	# Button configuration
		buttons {
			small.file {
				XY = 75,25
				50.offset = 75-[50.w],3
			}
		}
		
	# Validation settings
		validation {
			www = /^(https?:\/\/)?([a-z0-9-]+\.)+([a-z0-9]+)(\/[!~*\'\(\)a-zA-Z0-9;\/\\\?:\@&=\+\$,%#\._-]*)*$/
		}

	# Settings for required fields
		required {
			fields = {$plugin.tx_mmforum.requiredFields}
			fieldWrap = <strong>|</strong>
		}

		date = %A, %e. %B %Y
}