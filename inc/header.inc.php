<?php

/*  Poweradmin, a friendly web-based admin tool for PowerDNS.
 *  See <http://www.poweradmin.org> for more details.
 *
 *  Copyright 2007-2009  Rejo Zenger <rejo@zenger.nl>
 *  Copyright 2010-2014  Poweradmin Development Team
 *      <http://www.poweradmin.org/credits.html>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Web interface header
 *
 * @package     Poweradmin
 * @copyright   2007-2010 Rejo Zenger <rejo@zenger.nl>
 * @copyright   2010-2014 Poweradmin Development Team
 * @license     http://opensource.org/licenses/GPL-3.0 GPL
 */
global $iface_title;
global $ignore_install_dir;
global $session_key;

header('Content-type: text/html; charset=utf-8');
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo " <head>\n";
echo "  <title>" . $iface_title . "</title>\n";
echo "  <link href=\"http://fonts.googleapis.com/icon?family=Material+Icons\" rel=\"stylesheet\">\n";
echo "  <link rel=stylesheet href=\"css/materialize.min.css\" type=\"text/css\" media=\"screen,projection\">\n";
echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>\n";
echo " </head>\n";
echo " <body>\n";

if (file_exists('install')) {
    echo "<div>\n";
    error(ERR_INSTALL_DIR_EXISTS);
    include ('inc/footer.inc.php');
    exit();
} elseif (isset($_SESSION ["userid"])) {
    do_hook('verify_permission', 'search') ? $perm_search = "1" : $perm_search = "0";
    do_hook('verify_permission', 'zone_content_view_own') ? $perm_view_zone_own = "1" : $perm_view_zone_own = "0";
    do_hook('verify_permission', 'zone_content_view_others') ? $perm_view_zone_other = "1" : $perm_view_zone_other = "0";
    do_hook('verify_permission', 'supermaster_view') ? $perm_supermaster_view = "1" : $perm_supermaster_view = "0";
    do_hook('verify_permission', 'zone_master_add') ? $perm_zone_master_add = "1" : $perm_zone_master_add = "0";
    do_hook('verify_permission', 'zone_slave_add') ? $perm_zone_slave_add = "1" : $perm_zone_slave_add = "0";
    do_hook('verify_permission', 'supermaster_add') ? $perm_supermaster_add = "1" : $perm_supermaster_add = "0";
    do_hook('verify_permission', 'user_is_ueberuser') ? $perm_is_godlike = "1" : $perm_is_godlike = "0";

    if ($perm_is_godlike == 1 && $session_key == 'p0w3r4dm1n') {
        error(ERR_DEFAULT_CRYPTOKEY_USED);
        echo "<br>";
    }

    echo "<ul id=\"dropdown1\" class=\"dropdown-content\">\n";
    echo "<li><a href=\"users.php\">" . _('User administration') . "</a></li>\n<li class=\"divider\"></li>\n";
    if ($_SESSION ["auth_used"] != "ldap") {
        echo " <li><a href=\"change_password.php\">" . _('Change password') . "</a></li>\n";
    }
    echo "<li><a href=\"index.php?logout\">" . _('Logout') . "</a></li>\n</ul>\n";
    echo "<ul id=\"dropdown2\" class=\"dropdown-content\">\n";
    if ($perm_view_zone_own == "1" || $perm_view_zone_other == "1") { echo "<li><a href=\"list_zones.php\">" . _('List zones') . "</a></li>\n"; }
    if ($perm_zone_master_add) { echo " <li><a href=\"list_zone_templ.php\">" . _('List zone templates') . "</a></li>\n"; }
    if ($perm_zone_master_add) { echo " <li><a href=\"add_zone_master.php\">" . _('Add master zone') . "</a></li>\n"; }
    if ($perm_zone_slave_add) { echo " <li><a href=\"add_zone_slave.php\">" . _('Add slave zone') . "</a></li>\n"; }
    if ($perm_zone_master_add) { echo " <li><a href=\"bulk_registration.php\">" . _('Bulk registration') . "</a></li>\n"; }
    echo "</ul>\n";
    echo "<nav>
    <div class=\"nav-wrapper\">
    <a href=\"index.php\" class=\"brand-logo\">" . $iface_title . "</a>
    <ul class=\"right hide-on-med-and-down\">
    <li><a href=\"index.php\">Home</a></li>
    <li><a class=\"dropdown-button\" href=\"#!\" data-activates=\"dropdown2\">Zone Management<i class=\"material-icons right\">arrow_drop_down</i></a></li>
    <li><a class=\"dropdown-button\" href=\"#!\" data-activates=\"dropdown1\">" . $_SESSION["name"] . "<i class=\"material-icons right\">arrow_drop_down</i></a></li>
    </ul>
    </div>
    </nav>";

// this config variable is used only for development, do not use it in production
//if (($ignore_install_dir == NULL || $ignore_install_dir == false) && file_exists ( 'install' )) {


    echo "    <div class=\"menu\">\n";
    echo "    <span class=\"menuitem\"><a href=\"index.php\">" . _('Index') . "</a></span>\n";
    if ($perm_search == "1") {
        echo "    <span class=\"menuitem\"><a href=\"search.php\">" . _('Search zones and records') . "</a></span>\n";
    }
    if ($perm_view_zone_own == "1" || $perm_view_zone_other == "1") {
        echo "    <span class=\"menuitem\"><a href=\"list_zones.php\">" . _('List zones') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"list_zone_templ.php\">" . _('List zone templates') . "</a></span>\n";
    }
    if ($perm_supermaster_view) {
        echo "    <span class=\"menuitem\"><a href=\"list_supermasters.php\">" . _('List supermasters') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_zone_master.php\">" . _('Add master zone') . "</a></span>\n";
    }
    if ($perm_zone_slave_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_zone_slave.php\">" . _('Add slave zone') . "</a></span>\n";
    }
    if ($perm_supermaster_add) {
        echo "    <span class=\"menuitem\"><a href=\"add_supermaster.php\">" . _('Add supermaster') . "</a></span>\n";
    }
    if ($perm_zone_master_add) {
        echo "    <span class=\"menuitem\"><a href=\"bulk_registration.php\">" . _('Bulk registration') . "</a></span>\n";
    }
    if ($_SESSION ["auth_used"] != "ldap") {
        echo "    <span class=\"menuitem\"><a href=\"change_password.php\">" . _('Change password') . "</a></span>\n";
    }
    echo "    <span class=\"menuitem\"><a href=\"users.php\">" . _('User administration') . "</a></span>\n";
    echo "    <span class=\"menuitem\"><a href=\"index.php?logout\">" . _('Logout') . "</a></span>\n";
    echo "    </div> <!-- /menu -->\n";
}
echo "    <div class=\"content\">\n";
