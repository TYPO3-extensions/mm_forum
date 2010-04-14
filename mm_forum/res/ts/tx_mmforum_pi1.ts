#
# tx_mmforum_pi1 // Main Forum Class
#################################
plugin.tx_mmforum_pi1 < plugin.tx_mmforum
plugin.tx_mmforum_pi1 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi1/class.tx_mmforum_pi1.php
		userFunc				= tx_mmforum_pi1->main

    # RSS feed PID
        rssPID              = {$plugin.tx_mmforum.rssPID}

	# Moderated board
		moderated			= {$plugin.tx_mmforum.moderatedBoard}
    
    # Avatar image dimensions
	    avatar_height       = 100
	    avatar_width        = 100

	# Number of posts to make a topic as a hot topic 
		hotposts       	    = {$plugin.tx_mmforum.topic_hotPosts}

	# Number of posts to make a user have a special icon. DEPRECATED.
		user_hotposts		= {$plugin.tx_mmforum.user_hotPosts}

	# Number of posts which are displayed on a topic page
		post_limit	        = {$plugin.tx_mmforum.postsPerPage}

	# mail debugger
		maillog		        = 1

	# Mail address for the Debugger
		maillog_mail	    = 	

	# Sets the numbers of topics which are displayed in topic list
		topic_count         = {$plugin.tx_mmforum.threadsPerPage}
	
	# Prefixes for threads
		prefixes			= {$plugin.tx_mmforum.prefixes}
	
	# Prefixes not listed in list_prefix view
		noListPrefixes		= Test
	
	# Spamblock interval, time between postings
		spamblock_interval	= {$plugin.tx_mmforum.spamblock_interval}
	
	# Maximum lines in signature
		signatureLimit		= {$plugin.tx_mmforum.signatureLimit}
        
    # Enable BB codes in signature
        signatureBBCodes    = {$plugin.tx_mmforum.signatureBBCodes}
	
	# Post alert options
		postalerts {
			# Colors for different states
				statusColors {
					open		= #FFDDDD
					work		= #FFFFDD
					done		= #DDFFDD
				}
		}
        
    # Enable shadow topics
        enableShadows       = {$plugin.tx_mmforum.enableShadows}
        
    # Polls
        polls {
            # Enable polls
                enable      = {$plugin.tx_mmforum.polls_enable}
                
            # Minimum number of answers
                minAnswers  = 2
            
            # stdWrap for poll expire date label
                expired_stdWrap {
                    wrap    = <div class="tx-mmforum-poll-expireson"> | </div>
                }
            
            # Defines colors for poll result bars
                pollBar_colorMap {
                    1 = #ff0000
                    2 = #00ff00
                    3 = #0000ff
                    4 = #ff00ff
                    default = #ff0000
                }
                
            # Restricts poll creation to following groups
                restrictToGroups = {$plugin.tx_mmforum.polls_restrict}
                
            # Poll icon
            	poll_icon = IMAGE
            	poll_icon {
            		file = {$plugin.tx_mmforum.style_path}/img/default/poll.png
            		
            		stdWrap.wrap = <div class="tx-mmforum-poll-icon"> | </div>
            	}
        }
        
    # File attachments
        attachments {
            # Enable file attachments
                enable          = {$plugin.tx_mmforum.attachment_enable}
                
            # Maximum file size for attachments (in bytes)
                maxFileSize     = {$plugin.tx_mmforum.attachment_filesize}
                
            # Allowed file extensions
                allow           = {$plugin.tx_mmforum.attachment_allow}
                
            # Denyed file extensions
                deny            = {$plugin.tx_mmforum.attachment_deny}
                
            # Directory for attachments
                attachmentDir   = uploads/tx_mmforum/
            
            # stdWrap for maximum file size label
                maxFileSize_stdWrap.wrap = <br /><br /> |
            
            # Enables preview images for image attachments
                imagePreview = {$plugin.tx_mmforum.attachment_preview}
            
            # Configuration for preview images
                imagePreviewObj = IMAGE
                imagePreviewObj {
                    file.width = 120
                    file.maxH = 120
                    
                    wrap = <div style="float:right;"> | </div>
                }
            
            # stdWrap for attachment section in post listing view
                attachment_stdWrap.wrap = <hr /> |
            
            # stdWrap for "file attachments" label
                attachmentLabel_stdWrap {
                    wrap = <div class="tx-mmforum-pi1-attachmentlabel"> | </div>
                }
            
            # stdWrap for attachment link
                attachmentLink_stdWrap {
                    wrap = <div class="tx-mmforum-pi1-attachment"> | </div>
                }
                
            # stdWrap for attachment label in edit mode
                attachmentEditLabel_stdWrap {
                    wrap = <div style="font-weight:bold;"> | </div>
                }
                
            # Maximum attachment count
            	maxCount = {$plugin.tx_mmforum.attachment_count}a
        }
        
    # Fields to be displayed in user list
        userlist_fields = username,email,tx_mmforum_posts
        
    # Number of users to be displayed in user list
        userlist_limit  = 30
        
        userlist_item = CASE
        userlist_item {
            key.field   = fieldname
            
            username = TEXT
            username {
                field = username
                typolink.parameter < plugin.tx_mmforum.userProfilePID
                typolink.additionalParams.field = uid
                typolink.additionalParams.wrap = &tx_mmforum_pi1[action]=forum_view_profil&tx_mmforum_pi1[user_id]=|
            }
            usergroup = TEXT
            usergroup {
                field = usergroup
                postUserFunc = tx_mmforum_tools->getUserGroupList
            }
            email = TEXT
            email {
                field = email
                typolink.parameter.field = email
            }
            crdate = TEXT
            crdate {
                field = crdate
                strftime = {$plugin.tx_mmforum.dateFormat}
                wrap = <div style="white-space:nowrap;"> | </div>
            }
            www = TEXT
            www {
                field = www
                typolink.parameter.field = www
            }
            tx_mmforum_icq = IMAGE
            tx_mmforum_icq {
                file = {$plugin.tx_mmforum.path_img}icq.gif
                
                stdWrap.if.isTrue.field = tx_mmforum_icq
                stdWrap.typolink {
                    parameter.field = tx_mmforum_icq
                    parameter.wrap = http://www.icq.com/people/&uin= |
                }
            }
            tx_mmforum_yim = IMAGE
            tx_mmforum_yim {
                file = {$plugin.tx_mmforum.path_img}yim.gif
                
                stdWrap.if.isTrue.field = tx_mmforum_yim
                stdWrap.typolink {
                    parameter.field = tx_mmforum_yim
                    parameter.wrap = http://edit.yahoo.com/config/send_webmesg?.target= | &.src=pg
                }
            }
            tx_mmforum_aim = IMAGE
            tx_mmforum_aim {
                file = {$plugin.tx_mmforum.path_img}aim.gif
                
                stdWrap.if.isTrue.field = tx_mmforum_aim
                stdWrap.typolink {
                    parameter.field = tx_mmforum_aim
                    parameter.wrap = aim:goim?screenname= | &message=Hello+Are+you+there?
                }
            }
            __pm = IMAGE
            __pm {
                file = {$plugin.tx_mmforum.path_img}pm.gif
                params = style="border:0px;"
                
                stdWrap.typolink {
                    parameter = {$plugin.tx_mmforum.pmPID}
                    additionalParams.field = uid
                    additionalParams.wrap = &tx_mmforum_pi3[action]=message_write&tx_mmforum_pi3[messid]=new&tx_mmforum_pi3[userid]=|&tx_mmforum_pi3[folder]=inbox
                }
            }
            
			tx_mmforum_avatar = IMAGE
			tx_mmforum_avatar {
				file {
					maxW = 80
					maxH = 100
					width >
					import = uploads/tx_mmforum/
					import {
						listNum = 0
						override.field = tx_mmforum_avatar
					}
				}
				altText = Avatar
				stdWrap {
					if.isTrue.field = tx_mmforum_avatar
					typolink {
						parameter = {$plugin.tx_mmforum.boardPID}
						additionalParams.field = uid
						additionalParams.wrap = &tx_mmforum_pi1[action]=forum_view_profil&tx_mmforum_pi1[user_id]=|
						ATagParams.cObject = TEXT
						ATagParams.cObject {
							field = username
						}
					}
				}
			}
            
            default = TEXT
            default.field = fieldvalue
        }
        
    # User ranks
        ranks {
            enable = {$plugin.tx_mmforum.enableRanks}
            
            title_stdWrap {
            }
            
            icon_stdWrap {
            }
            
            rank_stdWrap {
                wrap = <div> | </div>
            }
            
            all_stdWrap {
            }
        }

	# Topic listing display options
		list_topics {                     
			# Wrap for prefixes
		 		prefix_wrap = <span class="tx-mmforum-pi1-listtopic-prefix">[ | ]</span>&nbsp;
		 	# Wrap for page navigation
			 	pagenav_wrap = <div class="tx-mmforum-pi1-listtopic-pages"> | </div>
		 	# Wrap for page navigation in the unread topic listing
		 		listunread_pagenav_wrap = <div class="tx-mmforum-pi1-listtopic-pages" style="display:inline; padding:0px;">(&nbsp; | &nbsp;)</div>
		 	
		 	# stdWrap for last post date info
		 		lastPostDate_stdWrap {
					postUserFunc = tx_mmforum_pi1->formatLastPostDate
					postUserFunc.defaultDateFormat = {$plugin.tx_mmforum.dateFormat}
		 			wrap = |<br />
		 		}
                
            # Display topic title in last post information
                lastPostTopicTitle = 1
                
            # stdWrap for topic title in topic lists
                topicTitle_stdWrap.crop = 50 | ... | 1
                
            # Wrap for topic title
                lastPostTopicTitle_outerStdWrap {
                    wrap = <div class="tx-mmforum-pi1-listtopic-lastpost-title"><strong>|</strong></div>
                }
                
                lastPostTopicTitle_innerStdWrap {
                }
                
            # Wrap for user name
                lastPostUserName_stdWrap {
                    wrap = <div class="tx-mmforum-pi1-listtopic-lastpost-user"><strong>|</strong></div>
                }
		}
		
	# Post listing display options
		list_posts {
            # a new option since 0.1.7 that adds the users' signature to the main posttext
            # since 0.1.7 there is now a separate marker to add the user signature anywhere in the template
				appendSignatureToPostText = 1

			# New option since 1.9.0: Determine ordering of posts in post listing view.
			# Allowed values are ASC and DESC
				postOrdering = ASC

            # stdWraps for user information fields
                userinfo {
                    username_stdWrap.wrap = <div style="font-weight: bold;"> | </div>
                    realname_stdWrap {
                        if.isTrue = {$plugin.tx_mmforum.displayRealName}
                        wrap = <div style="font-size: 10px;"> | </div>
                    }
                    crdate_stdWrap.date = d.m.Y
                    creator_stdWrap.wrap = <div style="font-size: 10px; font-weight: bold; margin: 4px 0px;"> | </div>
                    avatar_cObj = IMAGE
                    avatar_cObj {
                        file.maxW = 100
                        file.maxH = 100
                    }
                }
                
                userbuttons {
                	10 = MMFORUM_BUTTON
                	10 {
                		label = www
                		special = www
                		link {
                			field = www
                			wrap = |
                		}
                	}
                	20 = MMFORUM_BUTTON
                	20 {
                		label = icq
                		link.field = tx_mmforum_icq
                		link.wrap = http://www.icq.com/scripts/search.dll?to=|
                		if.isTrue.field = tx_mmforum_icq
                	}
                	30 = MMFORUM_BUTTON
                	30 {
                		label = aim
                		link.field = tx_mmforum_aim
                		link.wrap = aim:goim?screenname=|&message=Hello+Are+you+there?
                		if.isTrue.field = tx_mmforum_aim
                	}
                	40 = MMFORUM_BUTTON
                	40 {
                		label = yim
                		link.field = tx_mmforum_yim
                		link.wrap = http://edit.yahoo.com/config/send_webmesg?.target=|
                		if.isTrue.field = tx_mmforum_yim
                	}
                	50 = MMFORUM_BUTTON
                	50 {
                		label = skype
                		link.field = tx_mmforum_skype
                		link.wrap = skype:|?call
                		if.isTrue.field = tx_mmforum_skype
                	}
                }
                
            # stdWrap for user signature
                signature_stdWrap {
                    wrap = <br /><hr /><div class="tx-mmforum-signature"> | </div>
                }
                
            # Prefix object
            	prefix = TEXT
            	prefix {
            		field = topic_is
            		if.isTrue.field = topic_is
            		noTrimWrap = |<span class="tx-mmforum-pi1-listposts-prefix">[|]</span> |
            	}
            	
            # Closed object
            	closed = TEXT
            	closed {
            		if.isTrue.field = closed_flag
            		data = LLL:EXT:mm_forum/pi1/locallang.xml:topic-closed
            		noTrimWrap = | <span class="tx-mmforum-pi1-listposts-closed">[|]</span> |
            	}
            	
            	optImgWrap {
            	}
            	optLinkWrap.wrap = <div style="font-size:smaller">|</div>
            	optItemWrap.wrap = <table cellspacing="0" cellpadding="3" border="0"><tr><td style="text-align:center;">|</td></tr></table>
        
			# Wraps for user information fields
            # DEPRECATED. Just for backwards compatibility.
				userinfo_topicauthor_wrap = <span style="font-size:10px; font-weight: bold;">[ | ]</span><br />
				userinfo_content_wrap = <span style="font-size:10px;"> | </span><br />
				userinfo_admin_wrap = <span class="tx-mmforum-pi1-administrator">[ | ]</span>
				userinfo_moderator_wrap = <span class="tx-mmforum-pi1-moderator">[ | ]</span>
                userinfo_realName = {$plugin.tx_mmforum.displayRealName}
                userinfo_realName_wrap = <span style="font-size:10px;"> | </span><br />
			# Wrap for highlighted search words
				highlight_wrap = <span class="tx-mmforum-pi1-highlight"> | </span>

			# stdWrap for text "Edited x times. Last on xxxx.xx.xx"
				postEdited_stdWrap {
					#you can use fields ###COUNT###, ###DATE### and ###TIME### fe. dataWrap = count: {field:###COUNT###}
					wrap = <br /><br />|
				}
		}
		
	# General display options
		display {
			# Separator between rootline elements
				rootline.separator = &nbsp;&raquo;&nbsp;
			# Separator between pagetitle elements
				pageTitle.separator = &nbsp;-&nbsp;
			# CSS class of button-like links
				linkButton.cssClass = tx-mmforum-button
			# Even list item class
				listItem.evenClass = tx-mmforum-list-even
			# Odd list item class
				listItem.oddClass = tx-mmforum-list-odd
		}
		
	# Post creation function
		postForm {
			smiliesAsDiv = 0
			smiliesAsDiv {
				allWrap = <div class="tx-mmforum-pi1-smilies"> | </div>
				itemWrap = <div class="tx-mmforum-pi1-smilie"> | </div>
			}
		}

		listLatest {
			limit = 10
			linkToLatestPost = 0
		}
		
	# User profile
		user_profile {
			crdate_stdWrap.date = d. m. Y
		}

	# Template files
		template { 
	        main                = {$plugin.tx_mmforum.style_path}/forum/main.html
	        header              = {$plugin.tx_mmforum.style_path}/forum/header.html
			list_topic			= {$plugin.tx_mmforum.style_path}/forum/list_topic.html
			list_post			= {$plugin.tx_mmforum.style_path}/forum/list_post.html
			login_error			= {$plugin.tx_mmforum.style_path}/forum/login_error.html
			error				= {$plugin.tx_mmforum.style_path}/forum/error.html
			new_topic			= {$plugin.tx_mmforum.style_path}/forum/new_topic.html
			new_post			= {$plugin.tx_mmforum.style_path}/forum/new_post.html
			send_email			= {$plugin.tx_mmforum.style_path}/forum/send_email.html
			userdetail			= {$plugin.tx_mmforum.style_path}/forum/userdetail.html
			admin_gui			= {$plugin.tx_mmforum.style_path}/forum/admin_gui.html
			post_del			= {$plugin.tx_mmforum.style_path}/forum/post_del.html
			change_userdata		= {$plugin.tx_mmforum.style_path}/forum/change_userdata.html
			new_post_mail		= {$plugin.tx_mmforum.style_path}/forum/mail_newpost.html
			havealook			= {$plugin.tx_mmforum.style_path}/forum/havealook.html
			favorites			= {$plugin.tx_mmforum.style_path}/forum/favorites.html
			search				= {$plugin.tx_mmforum.style_path}/forum/search.html
			userconf			= {$plugin.tx_mmforum.style_path}/forum/userconf.html
			post_alert			= {$plugin.tx_mmforum.style_path}/forum/post_alert.html
            polls               = {$plugin.tx_mmforum.style_path}/forum/polls.html
            latest              = {$plugin.tx_mmforum.style_path}/forum/latest.html
            userlist            = {$plugin.tx_mmforum.style_path}/forum/userlist.html
            postqueue			= {$plugin.tx_mmforum.style_path}/forum/postqueue.html
            rss					= {$plugin.tx_mmforum.style_path}/forum/rss.xml
	        
	        footer              = {$plugin.tx_mmforum.style_path}/forum/footer.html
		}

    # Topic icons
        topicIcon = IMAGE
        topicIcon {
            file = GIFBUILDER
            file {
                XY = 32,32
                
                2 = BOX
                2 {
                    dimensions = 1,1,30,30
                    color = #f0f0f0;
                    if.isFalse.field = unread
                }
                
                1 = BOX
                1 {
                    dimensions = 0,0,32,32
                    color = #1555A0;
                }
                
                10 = IMAGE
                10 {
                    file = {$plugin.tx_mmforum.style_path}/img/default/mini_unanswered.gif
                    offset = 3,3
                    if.isTrue.field = unanswered
                }
                
                20 = IMAGE
                20 {
                    file = {$plugin.tx_mmforum.style_path}/img/default/mini_closed.gif
                    offset = 17,3
                    if.isTrue.field = closed
                }
                
                30 = IMAGE
                30 {
                    file = {$plugin.tx_mmforum.style_path}/img/default/mini_solved.gif
                    offset = 3,17
                    if.isTrue.field = solved
                }
                
                40 = IMAGE
                40 {
                    file = {$plugin.tx_mmforum.style_path}/img/default/mini_hot.gif
                    offset = 17,17
                    if.isTrue.field = hot
                }
            }
        }
            
	# Topic icon mode
		topicIconMode = {$plugin.tx_mmforum.topicIconMode}

	# Ratings settings
		enableRating {
			topics			= 1
			posts			= 0
			users			= 0
		}

	# Frontend administration settings
		feAdmin {
			enable = 0
			templates {
				list = {$plugin.tx_mmforum.style_path}/feadmin/list.html
				edit = {$plugin.tx_mmforum.style_path}/feadmin/edit.html
				acl  = {$plugin.tx_mmforum.style_path}/feadmin/acl.html
			}

			imagePath = {$plugin.tx_mmforum.style_path}/img/default/feadmin

			format {
				errorMessage.wrap = <div class="error"> | </div>
			}

			acl {
				category {
					create = all
					edit = all
					remove = all
					acl = all
					order = all
				}
				forum {
					create = all
					edit = all
					remove = all
					acl = all
					order = all
				}
			}

			list.buttons {
				button = IMAGE
				button {
					file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-edit.png
					file.maxW = 24
					stdWrap.typolink {
						parameter.data = TSFE:id
						additionalParams.field = uid
						additionalParams.wrap = &tx_mmforum_pi1[editForum]=|
					}
				}

				edit < .button
				edit.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-edit.png
				edit.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[editForum]=|
				edit.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-edit
				edit.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-edit

				remove < .button
				remove.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-delete.png
				remove.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[removeForum]=|
				remove.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-remove
				remove.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-remove

				access < .button
				access.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-access.png
				access.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[setACLs]=|
				access.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-acl
				access.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-acl

				newsub < .button
				newsub.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-newforum.png
				newsub.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[newForum]=1&tx_mmforum_pi1[forum][parent]=|
				newsub.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-newchild
				newsub.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-newchild

				up < .button
				up.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-up.png
				up.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[moveUp]=|
				up.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-moveUp
				up.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-listmoveUpedit

				down < .button
				down.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-down.png
				down.stdWrap.typolink.additionalParams.wrap = &tx_mmforum_pi1[moveDown]=|
				down.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-moveDown
				down.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-moveDown

				newctg < .button
				newctg.file = {$plugin.tx_mmforum.style_path}/img/default/feadmin/feadmin-newcategory.png
				newctg.stdWrap.typolink.additionalParams >
				newctg.stdWrap.typolink.additionalParams = &tx_mmforum_pi1[newForum]=1
				newctg.stdWrap.typolink.title.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-newcategory
				newctg.altText.data = LLL:EXT:mm_forum/pi1/locallang.xml:feadmin-list-newcategory
			}

			validation {
				name {
					maxLength = 255
					minLength = 3
				}

				description {
					maxLength = 255
					minLength = 3
				}
			}
		}

            
}
