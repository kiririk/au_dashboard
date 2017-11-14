<?php
defined('_JEXEC') or die('Restricted access');
function logout($userid = null, $options = array())
{
        // Initialize variables
        $retval = false;
 
        // Get a user object from the JApplication
        $user = &JFactory::getUser($userid);
 
        // Build the credentials array
        $parameters['username'] = $user->get('username');
        $parameters['id']               = $user->get('id');
 
        // Set clientid in the options array if it hasn't been set already
        if(empty($options['clientid'])) {
                $options['clientid'][] = $this->getClientId();
        }
 
        // Import the user plugin group
        JPluginHelper::importPlugin('user');
 
        // OK, the credentials are built. Lets fire the onLogout event
        $results = $this->triggerEvent('onLogoutUser', array($parameters, $options));
 
        /*
         * If any of the authentication plugins did not successfully complete
         * the logout routine then the whole method fails.  Any errors raised
         * should be done in the plugin as this provides the ability to provide
         * much more information about why the routine may have failed.
         */
        if (!in_array(false, $results, true)) {
                setcookie( JUtility::getHash('JLOGIN_REMEMBER'), false, time() - 86400, '/' );
                return true;
        }
 
        // Trigger onLoginFailure Event
        $this->triggerEvent('onLogoutFailure', array($parameters));
 
        return false;
}

$app = JFactory::getApplication();              
$user = JFactory::getUser();
$user_id = $user->get('id');            
$app->logout($user_id, array());

$link = 'index.php'; 
$msg = 'You have been redirected to main page'; 
$app->redirect($link, $msg, $msgType='message');
?>