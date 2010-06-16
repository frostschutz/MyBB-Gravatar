<?php
/**
 * This file is part of Gravatar plugin for MyBB.
 * Copyright (C) 2010 Andreas Klauer <Andreas.Klauer@metamorpher.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />
         Please make sure IN_MYBB is defined.");
}

/* --- Hooks: --- */

$plugins->add_hook("usercp_avatar_start", "gravatar_usercp_avatar_start");
$plugins->add_hook("usercp_do_avatar_start", "gravatar_usercp_do_avatar_start");

/* --- Plugin API: --- */

function gravatar_info()
{
    return array(
        "name"          => "Gravatar",
        "description"   => "This plugin lets users display their Gravatar avatar.",
        "website"       => "http://www.gravatar.com",
        "author"        => "Andreas Klauer",
        "authorsite"    => "mailto:Andreas.Klauer@metamorpher.de",
        "version"       => "0.1",
        "guid"          => "1e97df95cb52aaf639aaa37f58c0fbee",
        "compatibility" => "14*,15*,16*"
        );
}

function gravatar_deactivate()
{
    global $db;

    require_once MYBB_ROOT."inc/adminfunctions_templates.php";

    find_replace_templatesets('usercp_avatar',
                              "#([\r\n ]*\\{\\\$gravatar\\}[\r\n ]*)#",
                              "\n",
                              0); // work around MyBB bug

    $db->delete_query("templates", "title='gravatar'");
}

function gravatar_activate()
{
    global $db;

    // Remove stuff first to avoid doubling problem.
    gravatar_deactivate();

    // Insert {$gravatar} into the usercp_avatar template.
    require_once MYBB_ROOT."inc/adminfunctions_templates.php";

    find_replace_templatesets('usercp_avatar',
                              "#(</table>[\r\n ]*<br />)#i",
                              "\n{\$gravatar}\n\\1");

    $template = array("title" => "gravatar",
                      "sid" => "-1",
                      "template" => "
<tr>
<td class=\"trow1\" width=\"40%\">
<strong>{\$lang->gravatar}</strong>
</td>
<td class=\"trow1\">
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
<tbody>
<tr>
<td>
{\$lang->gravatar_caption}<br />
{\$lang->gravatar_email}
</td>
<td align=\"right\">
<label><input type=\"checkbox\" name=\"gravatar\" value=\"1\" /><img align=\"middle\" src=\"{\$gravatar_url}\" alt=\"{\$lang->gravatar}\" title=\"{\$lang->gravatar}\"></label>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
",
        );

    $db->insert_query("templates", $template);
}

/* --- Helpers: --- */

function gravatar_get_link($email)
{
    return "http://www.gravatar.com/avatar/".md5(trim(my_strtolower($email)));
}

/* --- Functionality: --- */

/*
 * Display a Gravatar checkbox.
 */
function gravatar_usercp_avatar_start()
{
    global $mybb, $lang, $templates, $gravatar;

    $lang->load('gravatar');

    $lang->gravatar_email = $lang->sprintf($lang->gravatar_email,
                                           $mybb->user['email']);

    $gravatar_url = gravatar_get_link($mybb->user['email']);

    eval("\$gravatar = \"".$templates->get("gravatar")."\";");
}

/*
 * Check if the user checked the Gravatar box,
 * and then just set the Avatar URL to the Gravatar URL.
 */
function gravatar_usercp_do_avatar_start()
{
    global $mybb;

    if($mybb->input['gravatar'])
    {
        $mybb->input['avatarurl'] = gravatar_get_link($mybb->user['email']);
    }
}

/* --- End of file. --- */
?>
