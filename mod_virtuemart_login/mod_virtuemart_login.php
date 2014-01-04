<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* @version		$Id: mod_virtuemart_login.php 1824 2009-06-24 20:08:11Z soeren_nb $
* @package		VirtueMart
* @subpackage modules
* @copyright	Copyright (C) 2007 Greg Perkins. All rights reserved.
* @license		GNU/GPL, http://www.gnu.org/copyleft/gpl.html
* 
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

// TODO: Joomla! 1.5 compatibility - do these global vars depend on the legacy plugin?
global $mosConfig_absolute_path, $mosConfig_allowUserRegistration;

// Load the virtuemart main parse code
if( file_exists(dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(__FILE__).'/../components/com_virtuemart/virtuemart_parser.php' );
}

global $mm_action_url, $sess, $VM_LANG;

//	Show login or logout?
if( vmIsJoomla(1.5) ) {
	$user = & JFactory::getUser();
	$type = (!$user->get('guest')) ? 'logout' : 'login';
} else {
	$type = ($my->id) ? 'logout' : 'login';
}

// Determine settings based on CMS version
if( vmIsJoomla('1.5') ) {
// Joomla 1.5

	if( $type == 'login' ) {
		// Redirect type
		$redirect = $params->get('login');
		
		// Lost password
		$reset_url = JRoute::_( 'index.php?option=com_users&amp;view=reset' );
		
		// User name reminder (Joomla 1.5 only)
		$remind_url = JRoute::_( 'index.php?option=com_users&amp;view=remind' );
		
		// Set the validation value
		$validate = JUtility::getToken();
	} else {
		// Redirect type
		$redirect = $params->get('logout');
		
		// Return URL
		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
		$return_url = base64_encode( $url );
	
		// Set the greeting name
		$user =& JFactory::getUser();
		$name = ( $params->get( 'name') ) ? $user->name : $user->username;
	}
	
	// Post action
	$action =  $mm_action_url. 'index.php?option=com_users&amp;task='.$type;

	// Set the redirection URL
	if( $redirect == 'home' ) {
		// The Joomla! home page
		$menu = &JSite::getMenu();
		$default = $menu->getDefault();
		$uri = JFactory::getURI( $default->link.'&Itemid='.$default->id );
		$url = $uri->toString(array('path', 'query', 'fragment'));
	} elseif( $redirect == 'vmhome' ) {
		// The VirtueMart home page
		$url = JRoute::_( 'index.php?option=com_virtuemart&amp;page='.HOMEPAGE.'&amp;Itemid='.$sess->getShopItemid(), false );
	} else {
		// The same page
		$uri = JFactory::getURI();
		$url = $uri->toString(array('path', 'query', 'fragment'));
	}
	
	$return_url = base64_encode( $url );

} else {
// Not Joomla 1.5

	if( $type == 'login' ) {
		// Redirect type
		$redirect = $params->get('login');
		
		// Lost password url
		$reset_url = sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword&amp;Itemid='.(int)mosGetParam($_REQUEST, 'Itemid', 0) );
		
		// Set user name reminder to nothing
		$remind_url = '';

		// Set the validation value
		if( function_exists( 'josspoofvalue' ) ) {
			$validate = josSpoofValue(1);
		} else {
			$validate = vmSpoofValue(1);
		}
	} else {
		// Redirect type
		$redirect = $params->get('logout');
		
		// Set the greeting name
		$name = ( $params->get( 'name') ) ? $my->name : $my->username;
	}

	// Post action
	$action = sefRelToAbs( $mm_action_url . 'index.php?option='.$type );

	// Set the redirection URL
	if( $redirect == 'home' ) {
		$url = sefRelToAbs( 'index.php' );
	} elseif( $redirect == 'vmhome' ) {
		// The VirtueMart home page
		$url = $sess->url( URL.'index.php?option=com_virtuemart&amp;page='.HOMEPAGE );
	} else {
		// The same page
		$url = $sess->url( basename($_SERVER['PHP_SELF']).'?'.mosGetParam($_SERVER,'QUERY_STRING'), true, false );
	}
	
	$return_url = sefRelToAbs( $url );

}

// Registration URL
$registration_url = $sess->url( SECUREURL.'index.php?option=com_virtuemart&amp;page=shop.registration' );

?>
	
<?php
			global $option;
			$page=JRequest::getVar('page');
			?>	
	
<?php if( $type == 'logout' ) : 
//"<?php echo $action ?>
<div id="vmlogout" class="moduletable_login">
	<form action="/index.php/component/users/?task=user.logout" method="post" name="login" id="login">
		<?php if( $params->get('greeting') ) : ?>
		<div style="float:left; text-align:center; margin:2px 10px 0px 0px; font-size:14x ; font-weight:bold;"><?php echo $VM_LANG->_('HI') . ' ' . $name ?></div> <div class="mclr"></div>
		<?php endif; ?>
		<?php if( $params->get('accountlink') || ENABLE_DOWNLOADS == '1' ) : ?>
		
			<?php if( $params->get('accountlink') ) : ?>
			<a href="<?php echo $sess->url(SECUREURL . "index.php?page=account.index");?>" <?php if($page=='account.index'&&$option=='com_virtuemart') echo "class='selected'";?>   >My Account </a>
			<?php endif; ?>
			<?php if( ENABLE_DOWNLOADS == '1' ) : ?>
        	<a href="<?php $sess->purl(SECUREURL . "index.php?page=shop.downloads");?>"><?php echo $VM_LANG->_('PHPSHOP_DOWNLOADS_TITLE') ?></a>
			
			<?php endif; ?>
			<input type="submit" name="logout" class="addtocart_button_module" value="<?php echo JTEXT::_('LOGOUT') ?>" id="ss"/>
		
		<?php endif; ?>
		
		
		<input type="hidden" name="op2" value="logout" />
		<?php echo JHtml::_('form.token'); ?>
		<input type="hidden" name="return" value="<?php echo $return_url ?>" />
		<input type="hidden" name="lang" value="english" />
		<input type="hidden" name="message" value="0" />
	</form>
</div>
<?php else : ?> 
<div id='vmlogin'>
	<form action="/index.php/component/users/?task=user.login" name="login" id="login" method="post">
		<?php if( $params->get('pretext') ) : ?>
		<?php echo $params->get('pretext'); ?>
		<?php endif; ?>
					
			
		<div id="loginbox" style="display:;">
			<label for="username_vmlogin" style="float:left;"><?php echo JTEXT::_('username') ?></label> <div class="mclr"></div>
			<input class="inputbox" type="text" id="username_vmlogin" size="12" name="username" /><div class="mclr"></div>
			<div class="mar"> <a style="float:left;" href="<?php echo $reset_url ?>"><?php echo JTEXT::_('FORGOT_YOUR_USERNAME') ?></a>
			</div> <br /><div class="mclr"></div>
			
			
			<label for="password_vmlogin" style="float:left;"><?php echo JTEXT::_('PASSWORD') ?></label><div class="mclr"></div>
			<input type="password" class="inputbox" id="password_vmlogin" size="12" name="password" /> <div class="mclr"></div>
			<div class="mar" ><a style="float:left;" href="<?php echo $reset_url ?>"><?php echo JTEXT::_('FORGOT_YOUR_PASSWORD') ?></a>
			</div>
			<div class="mclr"></div>
			
			<input style="margin-top:10px;" type="submit" id="loginbutton" value="<?php echo JTEXT::_('LOGIN') ?>" /><div class="clr mar">&nbsp;</div>
			
			<?php if( @VM_SHOW_REMEMBER_ME_BOX == '1' ) { 
						$remember_me_checked = $params->get('remember_me_default', 1) ? 'checked="checked"' : '';
				?>
			<div style="margin-top:8px;">
			<input style="float:left;" type="checkbox" name="remember" id="remember_vmlogin" value="yes" <?php echo $remember_me_checked ?>  />
			<label for="remember_vmlogin" style="float:left; margin-top:2px;"><?php echo $VM_LANG->_('REMEMBER_ME') ?></label>
			</div>

			<?php } else { ?>
						<input type="hidden" name="remember" value="yes" style="float:left;" />
			<?php } ?>
			<div class="clr"></div>
			
			
			
		<?php if( $mosConfig_allowUserRegistration == '1' ) : ?>
			<a style="border:none;" id="registerlink" href="<?php echo $registration_url ?>" <?php if($page=='shop.registration'&&$option=='com_virtuemart') echo "class='selected'";?>><?php echo JTEXT::_('CREATE_AN_ACCOUNT') ?></a>			
			<?php endif; ?>	
			
		</div>
		
		
	
		<input type="hidden" value="login" name="op2" />
		<input type="hidden" value="<?php echo $return_url ?>" name="return" />
		<input type="hidden" name="<?php echo $validate; ?>" value="1" />
		<?php echo $params->get('posttext'); ?>
	</form>
</div>
<?php endif; ?>

<script type="text/javascript">
jQuery(document).ready(function() {

jQuery('#ss').addClass('vmlogout2');

});
</script>

<?php	if($_SESSION['site']==1) {?>
			

<script type="text/javascript">

function logot(){
document.getElementById('pp').style.background='#000000';
document.getElementById('ss').style.color='#ffffff';
}
function logot1(){
document.getElementById('pp').style.background='transparent';
document.getElementById('ss').style.color='#000000';
}
</script>
			
			<?php } else {?>

<script type="text/javascript">

function logot(){

}
function logot1(){

}
</script> <?php }?>
