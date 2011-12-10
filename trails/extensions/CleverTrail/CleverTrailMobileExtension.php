<?php
if( !defined( 'MEDIAWIKI' ) ){
        die( "This is not a valid entry point.\n" );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'CleverTrailMobile',
	'version' => '0.1',
	'author' => '[http://www.twitter.com/dylankreid Dylan Reid]',
	'description' => 'Extension for the CleverTrail website',
);

//The following overrides the action=edit action from article pages 
//usually a user would not come here but there were still ways to access the action=edit option
//even as simple as putting it into the URL, so this hook is necessary
$wgHooks['AlternateEdit'][] = 'cleverTrailMobileAlternateEdit';
function cleverTrailMobileAlternateEdit( $editpage ) {
	global $wgServer, $wgScript, $wgTitle, $wgUser;
	
	//allow adminstrators to edit as normal or if not the main namespace
	$ctCurrentNamespace = $wgTitle->getNamespace();
	if ($wgUser->isAllowed('editinterface') || $ctCurrentNamespace != NS_MAIN) {
		return true;
	}
	else {
		$redirect = "$wgServer$wgScript/$wgTitle";
		header("Location: $redirect");
	}
	return false;
}

//for now, don't allow any editing on the mobile site
$wgHooks['DoEditSectionLink'][]  = 'cleverTrailMobileDoEditSectionLink';  
function cleverTrailMobileDoEditSectionLink($skin, $title, $section, $tooltip, $result, $lang ) {
	
	$arStandardSections = array(1 => "Overview", 2 => "Directions To Trailhead", 3 => "Trail Description", 4 => "Conditions And Hazards",
								5 => "Fees, Permits, And Restrictions", 6 => "Amenities", 7 => "Miscellaneous", 8 => "Photo Gallery", 9 => "Map");
								
	$arStandardSections[] = "Comments And Review"; //normally wouldn't be here if it was for editing...
	
	$posOfSection = array_search($tooltip, $arStandardSections);
	
	if (!$posOfSection) {
		$result = "";		
	}
	else {
		//create the link for special page's section editor
		$result = "<span class=\"editsection\">[<a href=\"#top\" title=\"top\">top";
		$result .=  "</a>]</span>";
	}
	
	return true;
}

?>