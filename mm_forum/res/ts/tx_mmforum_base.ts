plugin.tx_mmforum = USER_INT
plugin.tx_mmforum {

		#
		# STORAGE PIDS
		#

		# Data storage PID
	storagePID			= {$plugin.tx_mmforum.storagePID}
		
		# User storage PID
	userPID 			= {$plugin.tx_mmforum.userPID}
		
		
		
		#
		# CONSTANT UIDS (CONTENT PAGES, USER GROUPS...)
		#

		# Page ID the board is placed on
	pid_forum       	= {$plugin.tx_mmforum.boardPID}

		# Page ID where the User Profile is displayed
	userProfilePID     	= {$plugin.tx_mmforum.boardPID}

		# Page ID for Private Messages
	pm_id         	    = {$plugin.tx_mmforum.pmPID}

		# User Group ID for the admins
	grp_admin      	    = {$plugin.tx_mmforum.adminGroup}
		
		# User group
	userGroup 			= {$plugin.tx_mmforum.userGroup}

		
		
		#
		# CACHING
		#

	caching = database
	caching {
		cacheTable = cachingframework_cache_hash
		tagsTable = cachingframework_cache_hash_tags
	}



		#
		# PM ENABLED
		#
		
	pm_enabled 			= {$plugin.tx_mmforum.pmEnabled}
		
		
		
		#
		# FILE PATHS
		#

		# Path for the avatars
	path_avatar         = uploads/tx_mmforum/

		# Path to the forum images
	path_img       	    = {$plugin.tx_mmforum.style_path}/img/default/

		# Path for the smilies
	path_smilie         = {$plugin.tx_mmforum.path_smilie}
		
		
		
		#
		# LANGUAGE AND DISPLAY SETTINGS
		#
		
		# Informal language
	informal			= {$plugin.tx_mmforum.informal}

		# Date format
	dateFormat          = {$plugin.tx_mmforum.dateFormat}
        
		# Database field used for the user name
	userNameField = {$plugin.tx_mmforum.userNameField}
	
		# This board's name
	boardName           = {$plugin.tx_mmforum.boardName}
		
		# The name of the administrating team or company
	teamName 				= {$plugin.tx_mmforum.team_name}
		
		# The name of the parent website
	siteName 				= {$plugin.tx_mmforum.site_name}
		
		# Disable rootline
	disableRootline = {$plugin.tx_mmforum.disableRootline}
		
		# Substitute the page title
	substitutePagetitle = {$plugin.tx_mmforum.substitutePagetitle}
	pagetitleLastForumPageTitleOnly = {$plugin.tx_mmforum.pagetitleLastForumPageTitleOnly}
	pagetitleWrap = {$plugin.tx_mmforum.pagetitleWrap}
	removeOriginalPagetitle = {$plugin.tx_mmforum.removeOriginalPagetitle}
		
		# RealUrl special links
	realUrl_specialLinks = {$plugin.tx_mmforum.realUrl_specialLinks}

		# Options for notification mails
	notifyingMail {
			# Notification mail sender
		sender				        = {$plugin.tx_mmforum.notifyMail_sender}
			# Notification mail sender address
		sender_address				= {$plugin.tx_mmforum.mailer_mail}
			# Link prefix override value
		topicLinkPrefix_override    =
	}



		#
		# IMAGE CONFIGURATIONS
		#

		# Image filenames
	images {
		www                 = www.gif
		icq                 = icq.gif
		yim                 = yim.gif
		aim                 = aim.gif
		msn                 = msn.gif
		skype               = skype.gif
		profile             = profile.gif
		quote               = quote.gif
		favorite            = favorit.gif
		favorite_on         = favorit_on.png
		favorite_off        = favorit_off.png
		info_mail_on        = info_mail_on.png
		info_mail_off       = info_mail_off.png
		reply               = reply.gif
		post-alert          = post-alert.gif
		pm                  = pm.gif
		delete              = delete_grey.gif
		edit                = edit_grey.gif
		solved              = solved.gif
		solved_on			= solved_on.png
		solved_off			= solved_off.png
		plus                = plus.gif
		minus               = minus.gif
		read                = read.gif
		read_new            = read_new.gif
		read_question_new   = read_question_new.gif
		read_question       = read_question.gif
		read_flame_new      = read_flame_new.gif
		read_flame          = read_flame.gif
		closed_icon_unread  = closed_icon_unread.gif
		closed_icon_read    = closed_icon_read.gif
		jump_to             = jump_to.gif
		5kstar              = 5kstar.gif
		new_topic           = new_topic.gif
		mail                = mail.gif
		imageReply          = pm-reply.gif
		imageDelete         = pm-delete.gif
		imageInbox          = pm-inbox.gif
		imageArchiv         = pm-archiv.gif
		imageOutbox         = pm-outbox.gif
		imageNewPm          = pm-newpm.gif
		start_search        = start_search.gif
		poll				= poll.gif

		forum				= forum.png
		forum_new			= forum_new.png
		closed_forum		= closed_forum.png
		closed_forum_new	= closed_forum_new.png

		topicicon			= topic.png
		topicicon_new		= topic_new.png
		topicicon_closed	= topic_closed.png
		topicicon_closed_new= topic_new_closed.png
		topicicon_hot		= topic_hot.png
		topicicon_hot_new	= topic_hot_new.png
		topicicon_unanswered= topic_unanswered.png
		topicicon_unanswered_new = topic_unanswered_new.png
		topicicon_shadow    = topic_shadow.png
		topicicon_pinned	= topic_pinned.png
		topicicon_pinned_new	= topic_pinned.png
		topicicon_pinned_hot = topic_pinned.png
		topicicon_pinned_hot_new = topic_pinned.png
		topicicon_pinned_closed = topic_pinned_closed.png
		topicicon_pinned_closed_new = topic_pinned_closed.png
		topicicon_pinned_unanswered_new = topic_pinned.png
		topicicon_pinned_unanswered = topic_pinned.png

		pmicon				= pm.png
		pmicon_new			= pm_new.png
	}
		
		
		
		#
		# BUTTON CONFIGURATIONS
		#
		
		# Image buttons
	buttons_image {

			# Normal size
		normal = IMAGE
		normal {
			file = GIFBUILDER
			file {
				XY = [10.w]+[30.w]+[50.w]+[26.w]+2,25

				10 = IMAGE
				10 {
					file = {$plugin.tx_mmforum.style_path}/img/default/buttons/button_left.png
					#file.width = 12
					#file.height = 25
					offset = 0,0
				}

				25 = IMAGE
				25 {
					file = {$plugin.tx_mmforum.style_path}/img/default/buttons/button_middle.png
					file.width = 400
					file.height = 25
					offset = [10.w],0
				}

				26 = IMAGE
				26 {
					file.import = {$plugin.tx_mmforum.style_path}/img/default/buttons/icons/
					file.import.field = button_iconname
					file.maxH = 25
					offset = [10.w]-3,0
					if.isTrue.field = button_iconname
				}

				30 = TEXT
				30 {
					text.field = button_label
					offset = [10.w]+[26.w],17
					fontSize = 10
					fontColor = #1555A0;
				}

				50 = IMAGE
				50 {
					file = {$plugin.tx_mmforum.style_path}/img/default/buttons/button_right.png
					#file.width = 12
					#file.height = 25
					offset = [10.w]+[26.w]+[30.w]+2,0
					#if.isFalse.field = button_iconname
				}
			}
			altText.field = button_label
			stdWrap.typolink {
				extTarget =
				parameter.field = button_link
				ATagParams.field = button_atagparams
			}
		}

			# Small size
		small < .normal
		small.file {
			XY = [10.w]+[30.w]+[50.w]+[26.w]+2,25
			10.file.height = 18
			50.file.height = 18
			25.file.height = 18
			#26.file.maxH = 18
			30 {
				fontSize = 8
				offset = [10.w]+[26.w],15
			}

			25.offset = [10.w],3
			#30.offset = [10.w]+[26.w],17
			10.offset = 0,3
			50.offset = [10.w]+[26.w]+[30.w]+2,3
		}
	}
        
		# Text buttons
	buttons_text {
        
			# Normal size
		normal = COA
		normal {
			1 = IMAGE
			1 {
				file = GIFBUILDER
				file {
					XY = 16,16

					10 = IMAGE
					10 {
						file.import = {$plugin.tx_mmforum.style_path}/img/default/buttons/icons/
						file.import.field = button_iconname
						file.maxW = 16
						file.maxH = 16
					}
					transparentBackground = 1
					transparentColor = #ffffff

					format = gif
				}

				#wrap = <div class="tx-mmforum-buttonicon"> | </div>
			}
			2 = TEXT
			2 {
				field = button_label
				#wrap = <div class="tx-mmforum-buttontext"> | </div>
				wrap = <span class="tx-mmforum-buttontext"> | </span>
			}
			stdWrap.wrap = <div class="tx-mmforum-textbutton"> | </div>
			stdWrap.typolink {
				extTarget =
				parameter.field = button_link
				ATagParams.field = button_atagparams
			}
		}

			# Small size
		small < .normal
		small.file {
			XY = [10.w]+[30.w]+[50.w]+[26.w]+2,25
			10.file.height = 18
			50.file.height = 18
			25.file.height = 18
			#26.file.maxH = 18
			30 {
				fontSize = 10
				offset = [10.w]+[26.w],15
			}

			25.offset = [10.w],3
			#30.offset = [10.w]+[26.w],17
			10.offset = 0,3
			50.offset = [10.w]+[26.w]+[30.w]+2,3
		}
	}
        
		# Button mode
		# Change to "buttons < plugin.tx_mmforum.buttons_image" to use image buttons.
	buttons < plugin.tx_mmforum.buttons_text



		#
		# ADVANCED DISPLAY CONFIGURATION
		#

		# Settings for output validation
	validatorSettings {
		quotes = double
		charset = auto
		stripTags = 0
		replace = specialchars
	}
		
		# Post parser configuration
	postparser {
		buttonPath          = {$plugin.tx_mmforum.postparser.insertButton_path}

		bb_code_linktarget	= _blank
		bb_code_linkclass	= link_10
		bb_code_linkclassinternal	< plugin.tx_mmforum.postparser.bb_code_linkclass
		bb_code_bullet_img	= EXT:mm_forum/res/img/postparser-bb_code_bullet.gif
		bb_code_path_smilie	= {$plugin.tx_mmforum.path_smilie}
		bb_code_parser		= 1
		bb_code_parser_differlinkclass = 0
		code_protection		= 1
		syntaxHighlighter	= 1
		smilie_generator	= 1
		zitat_div			= 1
		links				= 1
		sh_linestyle_bg		= #gggggg
		sh_linestyle_bg2	= #ghfghf
		tsrefUrl			= www.typo3.net

		quoteClass			= tx-mmforum-pi1-pt-quote
		codeClass			= tx-mmforum-pi1-pt-code
	}

		# Style path
	stylePath				= {$plugin.tx_mmforum.style_path}
}