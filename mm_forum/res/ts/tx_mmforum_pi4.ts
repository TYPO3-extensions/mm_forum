#
# tx_mmforum_pi4 // Search
##############################
plugin.tx_mmforum_pi4 < plugin.tx_mmforum
plugin.tx_mmforum_pi4 {

	# Plugin configuration
		includeLibs				= EXT:mm_forum/pi4/class.tx_mmforum_pi4.php
		userFunc				= tx_mmforum_pi4->main
        
	# Template File
		template            = {$plugin.tx_mmforum.style_path}/search/tmpl_search.html

	# Board UIDs that are not indexed. DEPRECATED
		noIndex_boardUIDs	= 
		
	# Minimum search word length
		sword_minLength		= {$plugin.tx_mmforum.sword_minLength}
		
	# Search results to be displayed on one page
		resultsPerPage		= {$plugin.tx_mmforum.resultsPerPage}
    	
    # Wrap for search word matches in results
    	matchWrap           = <strong> | </strong>
        
    # Number of Threads to Index
        indexCount          = {$plugin.tx_mmforum.indexCount}
            
    # Indexing password
        indexingPassword    = {$plugin.tx_mmforum.indexingPassword}
		
	# Display search duration
        displaySearchDuration	= 0
        
	# Crop the link to the result (stdWrap)
		postPath { 
			crop=50|...
		}
}