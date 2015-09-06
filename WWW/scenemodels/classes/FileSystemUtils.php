<?php

/*
 * Copyright (C) 2015 FlightGear Team
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Utils for FileSystem operation
 *
 * @author Julien Nguyen
 */
class FileSystemUtils {

    /**
     * Deletes a directory sent in parameter.
     * @param string $folder folder to delete.
     * @return boolean true if the deletion is successful, false otherwise.
     */
    public static function clearDir($folder) {
        $openedDir = opendir($folder);
        if (!$openedDir) {
            return false;
        }

        while ($file = readdir($openedDir)) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            if (is_dir($folder."/".$file)) {
                $r = $this->clear_dir($folder."/".$file);
            } else {
                $r = @unlink($folder."/".$file);
            }

            if (!$r) {
                return false;
            }
        }

        closedir($openedDir);
        return rmdir($folder);
    }
}