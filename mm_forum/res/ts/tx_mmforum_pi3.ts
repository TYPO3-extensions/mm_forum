#
# tx_mmforum_pi3 // Private Messaging
##################################
plugin.tx_mmforum_pi3 < plugin.tx_mmforum
plugin.tx_mmforum_pi3 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi3/class.tx_mmforum_pi3.php
		userFunc				= tx_mmforum_pi3->main
        
	# Minimum amount of time between two sent PMs in seconds
		block_time  			= {$plugin.tx_mmforum.pmBlocktime}

	# Fields that are searched in
		userSearchFields		= {$plugin.tx_mmforum.pmUserSearchFields}

	# How the results of the user search are ordered	
		userSearchOrderBy		= {$plugin.tx_mmforum.pmUserSearchOrderBy}

	# The email address of the PHP mailer
		mailerEmail				= {$plugin.tx_mmforum.mailer_mail}
		
	# Wrap for unread messages in list display
		unreadWrap				= <span class="tx-mmforum-pi3-pminbox-unread"> | </span>

	# Templates
		template {
			error_message		= {$plugin.tx_mmforum.style_path}/pm/error.html
			navi_top			= {$plugin.tx_mmforum.style_path}/pm/navi_top.html
			main				= {$plugin.tx_mmforum.style_path}/pm/main.html
			message_read		= {$plugin.tx_mmforum.style_path}/pm/message_read.html
			message_write		= {$plugin.tx_mmforum.style_path}/pm/message_write.html
			user_list			= {$plugin.tx_mmforum.style_path}/pm/user_list.html
			mail_message		= {$plugin.tx_mmforum.style_path}/pm/mail_message.html
		}

}