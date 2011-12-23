<?php
/* Shared functions through the CleverTrail website */
/* Copyright CleverTrail.com and Dylan Reid 2011 */

include_once("globals.php");

//common header stuff: style sheets and a javascript warning
function drawCommonHeader($addTrailMap, $bTrailArticle) {
	global $wgMainServer, $wgMobileServer;
?>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" language="javascript"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script> 
<script type="text/javascript" src="<?php echo $wgMobileServer ?>/javascript/globals.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/main.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/toolbar.js" language="javascript"></script>

<link type='text/css' href='<?php echo $wgMobileServer ?>/css/main.css' rel='stylesheet' media='screen' />
<link type='text/css' href='<?php echo $wgMainServer ?>/css/toolbar.css' rel='stylesheet' media='screen' />
<link type='text/css' href='<?php echo $wgMobileServer ?>/css/toolbar.css' rel='stylesheet' media='screen' />

<?php if ($bTrailArticle) { ?>
<script type="text/javascript" src="http://<?php echo $wgMainServer ?>/javascript/trailarticle.js" language="javascript"></script>
<?php } ?>

<?php if ($addTrailMap) { ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.5&sensor=true"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/infobox.js" language="javascript"></script>
<script type="text/javascript" src="<?php echo $wgMainServer ?>/javascript/trailmap.js" language="javascript"></script>
<link type='text/css' href='<?php echo $wgMainServer ?>/css/trailmap.css' rel='stylesheet' media='screen' />
<link type='text/css' href='<?php echo $wgMobileServer ?>/css/trailmap.css' rel='stylesheet' media='screen' />
<?php } ?>
		
<!--[if lt IE 5.5000]><link rel="stylesheet" href="<?php echo $wgMobileServer?>/css/IE50Fixes.css" media="screen" /><![endif]-->
<!--[if IE 5.5000]><link rel="stylesheet" href="<?php echo $wgMobileServer?>/css/IE55Fixes.css" media="screen" /><![endif]-->
<!--[if IE 6]><link rel="stylesheet" href="<?php echo $wgMobileServer?>/css/IE60Fixes.css" media="screen" /><![endif]-->
<?php
}

function drawCommonDivs() {
?>
	<div id="divCleverTrailAlert"></div>
<?php
}

function drawToolBar($userName, $personalUrls, $searchTerm, $numTrailsFinished) {
	global $wgMobileServer, $wgScriptOfWiki;
?>
	<div id="divToolbar">
		<input type="hidden" id="txtScriptPath" name="txtScriptPath" value="<?php echo $wgScriptOfWiki; ?>">
		
		<div id="divCleverTrailLogo">
			<a href="<?php echo $wgMobileServer; ?>">
			<img id="imgCleverTrailLogo" src="http://clevertrail.com/images/logo-40px-white-on-grey.png" height="40px"></a>
		</div>
		<div id="divTrailSearch">		
			<form action="<?php echo $wgScriptOfWiki ?>" id="searchform">
				<input style="height: 0px" type='hidden' name="title" value="Special:Search"/>	

				<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>
				<input id="txtTrailSearchInput" autocomplete="off" title="Search By Trail Name Or Category" 
				type="text" name="search" value="<?php
				echo ( $searchTerm != '' ) ? $searchTerm : 'search for a trail name or category';
				?>"/>
				</td><td>
				<input type='submit' name="go" id="btnTrailSearchInput" value="" title="Search By Trail Name">			
				</td></tr></table>
				
			</form>			
		</div>
		<div id="divUserActions">						
			<?php
			//if the user name is set, let's create a fancy drop down menu
			if ($userName != '') { ?>	
				<?php echo drawHikerTitle($numTrailsFinished, 24, false, false, false); ?>&nbsp;
				<a id="aUserActionsUserName"><?php echo $userName ?></a>
				<img id="imgUserActionsDropDown" src="http://clevertrail.com/images/icons/ico-dropdown25.png">
				
				<br>
				<ul id="ulUserActionsDropDown">
				<?php foreach($personalUrls as $key => $item) {
					 ?>
						<li id="<?php echo"pt-$key" ?>" onclick="location.href=('<?php echo htmlspecialchars($item['href']) ?>')">
						<?php 
							if ($key == "userpage")
								echo "my page";
							else
								echo htmlspecialchars($item['text']) ;
						?></li>
				<?php }	?>
				</ul>
			
			<?php }
				else { ?>
					<ul id="ulUserActionsAcross">
					<?php foreach($personalUrls as $key => $item) { 
						//skip the user page for an ip address - we want to encourage registration
						if ($key == "anonuserpage" || $key == "anontalk") continue;
					?>
							<li id="<?php echo"pt-$key" ?>">
							<a href="<?php echo htmlspecialchars($item['href']) ?>">
							<?php echo htmlspecialchars($item['text']) ?></a></li>
					<?php }	?>
					</ul>
				<?php 
				} ?>
		</div>
		
		
	</div>
	
	<div id="divJumpToNavigation">
		Jump to: [<a href="#navigation">navigation</a>]
	</div>	

<?php
}

function drawNavigation() { 
	global $wgMobileServer, $wgPathOfWiki;
?>
	<div id="navigation">
		<h2>Navigation:</h2>
		<ul>
			<li id="n-home"><a href="<?php echo $wgMobileServer ?>" title="Map of all the trails">
			Map Of Trails</a></li>				
			<li id="n-recentchanges"><a href="<?php echo $wgPathOfWiki ?>/Special:RecentChanges" title="The list of recent changes">
			Recent Changes</a></li>
			<li id="n-randompage"><a href="<?php echo $wgPathOfWiki ?>/Special:Random" title="Load a random trail">
			Random Trail</a></li>
			<li id="n-specialpages"><a href="<?php echo $wgPathOfWiki ?>/Special:SpecialPages" title="Special pages">
			Special Pages</a></li>					
			<li id="n-help"><a href="<?php echo $wgPathOfWiki ?>/CleverTrail:Help" title="Help with CleverTrail">
			Help</a></li>			
		</ul>
		<br>
		<div id="divJumpToTop">
			Jump to: [<a href="#top">top</a>]
		</div>	
		<br>
	</div>	

<?php
}


function drawAdditionalFooterLinks() {
	global $wgPathOfWiki;
?>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:About" title="About CleverTrail">about</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Contact" title="Ways To Contact CleverTrail">contact</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Privacy" title="Privacy Policy">privacy</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Terms_Of_Use" title="Terms Of Use">terms of use</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Help" title="Help With CleverTrail">help</a></li>
	<li><a href="<?php echo $wgPathOfWiki; ?>/CleverTrail:Contribute" title="Help Out By Donating! :)">contribute</a></li>
<?php			
}