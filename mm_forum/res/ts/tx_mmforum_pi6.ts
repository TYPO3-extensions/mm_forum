#
# tx_mmforum_pi6 // Portal Information
##############################

plugin.tx_mmforum_pi6 < plugin.tx_mmforum
plugin.tx_mmforum_pi6 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi6/class.tx_mmforum_pi6.php
		userFunc				= tx_mmforum_pi6->main

	# Template file
		templateFile        = {$plugin.tx_mmforum.style_path}/portalinfo/mm_forum_pi6.tmpl

	# The time that has to pass before a user is not counted as online anymore
		onlineTime          = 600

	# Determines, whether the counts a user made in the current session is to be counted
		showPostCount       = 1
	# Debug var
		debug               = 0
		
	# The wrap for important information in the text
		importantInformation_wrap = <span style="font-weight:bold;color:#8e8d8d;"> | </span>
		
	# User list configuration options
	userList {
		# Wrap for group titles
			groupTitle_wrap = <span style="font-weight:bold;"> | </span>
	}
}
