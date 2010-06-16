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
        "guid"          => "",
        "compatibility" => "14*,15*,16*"
        );
}

/* --- Helpers: --- */

function gravatar_get_link($email)
{
    return "http://www.gravatar.com/avatars/".md5(trim(my_strtolower($email)));
}

/* --- Functionality: --- */

/* --- End of file. --- */
?>
