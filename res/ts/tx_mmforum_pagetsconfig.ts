mod.web_txmmforumM1 {

	foo = bar

	sections {

		10 = MMFORUM_SECTION_ITEM
		10.id   = useradmin
		10.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.useradmin
		10.handler = userManagement

		20 = MMFORUM_SECTION_ITEM
		20.id   = boardadmin
		20.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.boardadmin
		20.handler = EXT:mm_forum/mod1/class.tx_mmforum_forumadmin.php:tx_mmforum_forumAdmin->main

		30 = MMFORUM_SECTION_ITEM
		30.id   = template
		30.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.template
		30.handler = EXT:mm_forum/mod1/class.tx_mmforum_templates.php:tx_mmforum_templates->main

		40 = MMFORUM_SECTION_ITEM
		40.id   = tools
		40.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.tools
		40.handler = Tools

		50 = MMFORUM_SECTION_ITEM
		50.id   = import
		50.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.import
		50.handler = EXT:mm_forum/mod1/class.tx_mmforum_import.php:tx_mmforum_import->main

		60 = MMFORUM_SECTION_ITEM
		60.id   = userFields
		60.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.userFields
		60.handler = EXT:mm_forum/mod1/class.tx_mmforum_userfields.php:tx_mmforum_userFields->main

		70 = MMFORUM_SECTION_ITEM
		70.id   = install
		70.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.install
		70.handler = EXT:mm_forum/mod1/class.tx_mmforum_install.php:tx_mmforum_install->main

		80 = MMFORUM_SECTION_ITEM
		80.id   = statistics
		80.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.statistics
		80.handler = EXT:mm_forum/mod1/class.tx_mmforum_statistics.php:tx_mmforum_statistics->main

		90 = MMFORUM_SECTION_ITEM
		90.id   = ranks
		90.name = LLL:EXT:mm_forum/mod1/locallang.xml:menu.ranks
		90.handler = EXT:mm_forum/mod1/class.tx_mmforum_ranksbe.php:tx_mmforum_ranksBE->main

	}

	defaultConfigFiles {
		default = EXT:mm_forum/ext_typoscript_constants.txt
	}

	essentialConfiguration {
		userPID = 1
		storagePID = 1
		userGroup = 1
		adminGroup = 1
	}

	submodules {
		installation {
			categories {
				general = MMFORUM_CONF_CATEGORY
				general {
					icon = EXT:mm_forum/mod1/img/install-general.png
					items {
						storagePID = MMFORUM_CONF_ITEM
						storagePID {
							type = group
							type.table = pages
						}

						userPID = MMFORUM_CONF_ITEM
						userPID {
							type = group
							type.table = pages
						}

						userGroup = MMFORUM_CONF_ITEM
						userGroup {
							type = select
							type.table = fe_groups
							type.limit = 1
						}

						adminGroup = MMFORUM_CONF_ITEM
						adminGroup {
							type = select
							type.table = fe_groups
							type.limit = 1
						}

						informal = MMFORUM_CONF_ITEM
						informal.type = checkbox

						realUrl_specialLinks = MMFORUM_CONF_ITEM
						realUrl_specialLinks.type = checkbox

						disableRootline = MMFORUM_CONF_ITEM
						disableRootline.type = checkbox

						dateFormat = MMFORUM_CONF_ITEM
						dateFormat.type = string

						userNameField = MMFORUM_CONF_ITEM
						userNameField.type = select
						userNameField.type.handler = getFeUserFields
					}
				}

				user = MMFORUM_CONF_CATEGORY
				user {
					icon = EXT:mm_forum/mod1/img/install-user.png
					items {
						useCaptcha = MMFORUM_CONF_ITEM
						useCaptcha.type = checkbox

						requiredFields = MMFORUM_CONF_ITEM
						requiredFields {
							type = special
							type.handler = getUserRequiredField
							type.big = 1
						}
					}
				}

				forum = MMFORUM_CONF_CATEGORY
				forum {
					icon = EXT:mm_forum/mod1/img/install-forum.png
					items {
						boardPID = MMFORUM_CONF_ITEM
						boardPID {
							type = group
							type.table = pages
						}

						moderatedBoard = MMFORUM_CONF_ITEM
						moderatedBoard.type = checkbox

						threadsPerPage = MMFORUM_CONF_ITEM
						threadsPerPage {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.topics
						}

						postsPerPage = MMFORUM_CONF_ITEM
						postsPerPage {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.posts
						}

						displayRealName = MMFORUM_CONF_ITEM
						displayRealName.type = checkbox

						topic_hotPosts = MMFORUM_CONF_ITEM
						topic_hotPosts {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.posts
						}

						spamblock_interval = MMFORUM_CONF_ITEM
						spamblock_interval {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.seconds
						}

						signatureLimit = MMFORUM_CONF_ITEM
						signatureLimit {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.lines
						}

						signatureBBCodes = MMFORUM_CONF_ITEM
						signatureBBCodes.type = checkbox

						enableRanks = MMFORUM_CONF_ITEM
						enableRanks.type = checkbox

						enableShadows = MMFORUM_CONF_ITEM
						enableShadows.type = checkbox

						prefixes = MMFORUM_CONF_ITEM
						prefixes.type = string

						polls_enable = MMFORUM_CONF_ITEM
						polls_enable.type = checkbox

						polls_restrict = MMFORUM_CONF_ITEM
						polls_restrict {
							type = select
							type.table = fe_groups
							type.big = 1
						}

						rssPID = MMFORUM_CONF_ITEM
						rssPID {
							type = group
							type.table = pages
						}

						topicIconMode = MMFORUM_CONF_ITEM
						topicIconMode {
							type = select
							type.options.classic = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.field.topicIconMode.options.classic
							type.options.modern = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.field.topicIconMode.options.modern
						}

						attachments = MMFORUM_CONF_ITEM
						attachments.type = div

						attachment_enable = MMFORUM_CONF_ITEM
						attachment_enable.type = checkbox

						attachment_allow = MMFORUM_CONF_ITEM
						attachment_allow.type = string

						attachment_deny = MMFORUM_CONF_ITEM
						attachment_deny.type = string

						attachment_filesize = MMFORUM_CONF_ITEM
						attachment_filesize {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.bytes
						}

						attachment_preview = MMFORUM_CONF_ITEM
						attachment_preview.type = checkbox

						attachment_count = MMFORUM_CONF_ITEM
						attachment_count.type = int
					}
				}

				pm = MMFORUM_CONF_CATEGORY
				pm {
					icon = EXT:mm_forum/mod1/img/install-pm.png
					items {
						pmEnabled = MMFORUM_CONF_ITEM
						pmEnabled.type = checkbox

						pmPID = MMFORUM_CONF_ITEM
						pmPID {
							type = group
							type.table = pages
						}

						pmBlocktime = MMFORUM_CONF_ITEM
						pmBlocktime {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.seconds
						}
					}
				}

				search = MMFORUM_CONF_CATEGORY
				search {
					icon = EXT:mm_forum/mod1/img/install-search.png
					items {
						sword_minLength = MMFORUM_CONF_ITEM
						sword_minLength {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.chars
						}

						resultsPerPage = MMFORUM_CONF_ITEM
						resultsPerPage {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.results
						}

						indexCount = MMFORUM_CONF_ITEM
						indexCount {
							type = int
							type.unit = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.unit.topics
						}

						indexingPassword = MMFORUM_CONF_ITEM
						indexingPassword.type = md5
					}
				}

				filepaths = MMFORUM_CONF_CATEGORY
				filepaths {
					icon = EXT:mm_forum/mod1/img/install-filepaths.png
					items {
						path_img = MMFORUM_CONF_ITEM
						path_img.type = string

						path_smilie = MMFORUM_CONF_ITEM
						path_smilie.type = string

						path_template = MMFORUM_CONF_ITEM
						path_template.type = string

						path_altTemplate = MMFORUM_CONF_ITEM
						path_altTemplate.type = string
					}
				}

				contact = MMFORUM_CONF_CATEGORY
				contact {
					icon = EXT:mm_forum/mod1/img/install-contact.png
					items {
						boardName = MMFORUM_CONF_ITEM
						boardName.type = string

						site_name = MMFORUM_CONF_ITEM
						site_name.type = string

						support_mail = MMFORUM_CONF_ITEM
						support_mail.type = string

						mailer_mail = MMFORUM_CONF_ITEM
						mailer_mail.type = string

						notifyMail_sender = MMFORUM_CONF_ITEM
						notifyMail_sender.type = string

						team_name = MMFORUM_CONF_ITEM
						team_name.type = string
					}
				}

				cron = MMFORUM_CONF_CATEGORY
				cron {
					icon = EXT:mm_forum/mod1/img/install-cron.png
					items {
						cron_verbose = MMFORUM_CONF_ITEM
						cron_verbose {
							type = select
							type.options.all = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.field.cron_verbose.options.all
							type.options.errors = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.field.cron_verbose.options.errors
							type.options.quiet = LLL:EXT:mm_forum/mod1/locallang_install.xml:install.field.cron_verbose.options.quiet
						}

						cron_htmlemail = MMFORUM_CONF_ITEM
						cron_htmlemail.type = checkbox

						cron_notifyPublish_group = MMFORUM_CONF_ITEM
						cron_notifyPublish_group {
							type = select
							type.table = fe_groups
							type.limit = 1
						}

						cron_lang = MMFORUM_CONF_ITEM
						cron_lang.type = string

						cron_sitetitle = MMFORUM_CONF_ITEM
						cron_sitetitle.type = string

						cron_postqueue_link = MMFORUM_CONF_ITEM
						cron_postqueue_link.type = string

						cron_notifyPublishSender = MMFORUM_CONF_ITEM
						cron_notifyPublishSender.type = string

						cron_pathTmpl = MMFORUM_CONF_ITEM
						cron_pathTmpl.type = string
					}
				}
			}
		}
	}

}