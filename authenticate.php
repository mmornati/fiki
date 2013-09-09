<?php
require_once('config.php');

function authenticate($user, $password) {
    global $settings;
    $type = $settings["auth"]["type"];
    if ($type == "ldap") {
        return authenticate_ldap($user, $password);        
    } else if ($type == "file") {
        return authenticate_file($user, $password);
    } else if ($type == "noauth") {
        $_SESSION['user'] = $user;
        $_SESSION['access'] = 1;

        return true;
    }

}

function authenticate_file($user, $password) {
    global $settings;
    //TODO: Complete this auth type
    //
    $_SESSION['user'] = $user;
    $_SESSION['access'] = 1;

    return true;
}

function authenticate_ldap($user, $password) {
    global $settings;
	// Active Directory server
	$ldap_host = $settings["ldap"]["ldap_host"];
 
	// Active Directory DN
	$ldap_dn = $settings["ldap"]["ldap_dn"];
 
	// Active Directory user group
	$ldap_user_group = $settings["ldap"]["ldap_user_group"];
 
	// Active Directory manager group
	$ldap_manager_group = $settings["ldap"]["ldap_manager_group"];
 
	// Domain, for purposes of constructing $user
	$ldap_usr_dom = $settings["ldap"]["ldap_domain"];
 
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
 
	// verify user and password
	if($bind = @ldap_bind($ldap, "uid=".$user.",".$ldap_dn , $password)) {
		// valid
		// check presence in groups
		$filter = "(uid=" . $user . ")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
 
		// check groups
		foreach($entries[0]['memberof'] as $grps) {
			// is manager, break loop
			if (strpos($grps, $ldap_manager_group)) { $access = 2; break; }
 
			// is user
			if (strpos($grps, $ldap_user_group)) $access = 1;
		}
 
		if ($access != 0) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true;
		} else {
			// user has no rights
			return false;
		}
 
	} else {
		// invalid name or password
		return false;
	}
}
?>
