<?php

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );
	
include_once("../include/globals.php");
global $wgMainDirectory;

include_once("../include/Skin_shared.php");
include_once("$wgMainDirectory/include/User_shared.php");	


/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinCleverTrailMobileSkin extends SkinTemplate {
	var $skinname = 'clevertrailmobileskin', $stylename = 'clevertrailmobileskin',
	$template = 'CleverTrailMobileTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
	}
}



/**
 * @todo document
 * @ingroup Skins
 */
class CleverTrailMobileTemplate extends BaseTemplate {

	/**
	 * @var Skin
	 */
	var $skin;

	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgRequest, $wgServer, $wgUser, $wgScript, $wgOut;
		
		$this->skin = $this->data['skin'];

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
		
		//get some useful information
		$server = $_SERVER['HTTP_HOST'];
		$trailTitle = $wgOut->getTitle();
		$namespace = $trailTitle->getNamespace();
		$userName = '';
		if ($wgUser->isLoggedIn())
			$userName = $wgUser->getName();
		
		//add common header info
		//add trail maps to categories and user pages
		$addTrailMap = ($namespace == NS_CATEGORY || $namespace == NS_USER);
		drawCommonHeader($addTrailMap, ($namespace == NS_MAIN));		
		
		//check if this trail has been marked complete by the user
		$bTrailFinished = false;
		if ($trailTitle && $namespace == NS_MAIN && $userName != "") {						
			$trailTitleSafe = mysql_real_escape_string($trailTitle);
			$userNameSafe = mysql_real_escape_string($userName);
			
			$query = "select * from trails_finished where sUser = '$userNameSafe' AND sTrail = '$trailTitleSafe'";			
			$result = ExecQuery($query);
			$num_rows = mysql_num_rows($result);

			if ($num_rows > 0) {
				$bTrailFinished = true;
			}			
		}
		
		//if a user is logged in, find out how many trails he's finished
		$numTrailsFinished = getNumberOfFinishedTrails($wgUser);
?>

<div id="divGlobalWrapper">
<a id="top"></a>
<?php
	drawToolBar($userName, $this->data['personal_urls'], $this->data['search'], $numTrailsFinished); 
?>	
	
	<div id="divContent">
		
		<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

		<h1 id="firstHeading" class="firstHeading">
			<table width=100%><tr>
				<td align=left>
					<?php $this->html('title') ?>
				</td>
				<?php if ($namespace == NS_MAIN) { ?>
					<td align=right>
						<div id="divCompleteTrail" title="Mark Trail Complete" style="background:
						<?php if ($bTrailFinished) echo "#864422"; else echo "#642200"; ?>">
							<img id="imgCompleteTrail" src="<?php 
								if ($bTrailFinished)
									echo "http://clevertrail.com/images/icons/finished_trail_check.png";
								else
									echo "http://clevertrail.com/images/icons/unfinished_trail.png";
								?>"> 
							<a id="aCompleteTrail">I've Done This Trail!</a>			
						</div>
						<div id="divCompleteTrailLoading" style="display:none">
							<img src="http://clevertrail.com/images/load_large.gif"> 
						</div>
						<script>
						window.onload= new function() { wgUserName = '<?php if($wgUser->isLoggedIn()) echo $wgUser; ?>'; sTrailName = '<?php echo str_replace("'", "\'", $trailTitle); ?>'; bTrailFinished = '<?php echo $bTrailFinished ?>'; }
						</script>
					</td>
					<td style="width:1px; font-size:60%">[<a href="<?php echo $server; ?>/CleverTrail:User_Accounts" target="_blank">?</a>]</td>
				<?php } elseif ($namespace == NS_USER && $trailTitle) { ?>
						<td align=right>
							<?php 
							//draw the hiking title if this is a user article							
							$userPageName = $trailTitle->getDBkey();
							$numUserPageTrailsFinished = getNumberOfFinishedTrails($userPageName);
							echo drawHikerTitle($numUserPageTrailsFinished, 24, true, true, true); 								
							?>
						</td>
				<?php } ?>
				</tr></table>
		</h1>
		<div id="bodyContent">
			<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
			<?php if($this->data['undelete']) { ?>
			<div id="contentSub2"><?php $this->html('undelete') ?></div>
			<?php } ?><?php if($this->data['newtalk'] ) { ?>
			<div class="usermessage"><?php $this->html('newtalk')  ?></div>
			<?php } ?>
				
			<!-- start content -->
			<?php $this->html('bodytext') ?>
			<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
			<!-- end content -->
			<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
			<div class="visualClear"></div>
		</div>
		<br>
		<?php
			drawNavigation();
		?>
		
		<?php
		$footerlinks = $this->getFooterLinks( "flat" ); // Additional footer links

		if ( count( $validFooterIcons ) + count( $footerlinks ) > 0 ) { ?>

		<div id="divFooter"<?php $this->html('userlangattributes') ?>>
			<?php
				$footerEnd = '</div>';
			} else {
				$footerEnd = '';
			}
			
			// Generate additional footer links
			$validFooterLinks = array();
			foreach( $footerlinks as $aLink ) {
				//screen out some of the links
				if ($aLink == 'privacy' || $aLink == 'about' || $aLink == 'disclaimer')
					continue;
				if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
					$validFooterLinks[] = $aLink;
				}
			}
			
			if ( count( $validFooterLinks ) > 0 ) { ?>
				<ul id="f-list">
				<?php drawAdditionalFooterLinks(); ?>
				<BR>
				<?php foreach( $validFooterLinks as $aLink ) { ?>
					<li id="<?php echo $aLink ?>"><?php $this->html($aLink) ?></li><br>
				<?php	} ?>
				</ul>
				<br>
			<?php	}
			
			drawCopyright();
			
			echo $footerEnd;
			?>

		</div>

	</div>

	
	<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
} // end of execute() method

/*************************************************************************************************/
} // end of class


