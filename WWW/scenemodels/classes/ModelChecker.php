<?php

/*
 * Copyright (C) 2014 Flightgear Team
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
 * Description of ModelChecker
 *
 * @author Julien Nguyen
 */
class ModelChecker {

    public function checkXMLFileArray($arrayXML) {
        $xmlName = $arrayXML['name'];
        $exceptions = array();
        
        // if file does not exist
        if ($xmlName == "") { 
            return $exceptions;
        }
        
        // check size file
        if ($arrayXML['size'] >= 2000000) {
            $exceptions[] = new Exception("Sorry, but the size of your XML file \"".$xmlName."\" exceeds 2Mb (current size: ".$arrayXML['size']." bytes).");
        }
        
        // check type
        if ($arrayXML['type'] != "text/xml") {
            $exceptions[] = new Exception("The format of your XML file \"".$xmlName."\"seems to be wrong. XML file needs to be an XML file.");
        }
        
        // If error is detected
        if ($arrayXML['error'] != 0) {

            switch ($arrayXML['error']) {
                case 1:
                    $errormsg = "The file \"".$xmlName."\" is bigger than this server installation allows.";
                    break;
                case 2:
                    $errormsg = "The file \"".$xmlName."\" is bigger than this form allows.";
                    break;
                case 3:
                    $errormsg = "Only part of the file \"".$xmlName."\" was uploaded.";
                    break;
                case 4:
                    $errormsg = "No file \"".$xmlName."\" was uploaded.";
                    break;
                default:
                    $errormsg = "There has been an unknown error while uploading the file \"".$xmlName."\".";
                    break;
            }

            $exceptions[] = new Exception($errormsg);
        }
        
        return $exceptions;
    }
    
    public function checkAC3DFileArray($arrayAC) {
        $ac3dName = $arrayAC['name'];
        $exceptions = array();
        
        // check size file
        if ($arrayAC['size'] >= 2000000) {
            $exceptions[] = new Exception("Sorry, but the size of your AC3D file \"".$ac3dName."\" is over 2Mb (current size: ".$arrayAC['size']." bytes).");
        }

        // check type & extension file
        if (($arrayAC['type'] != "application/octet-stream" && $arrayAC['type'] != "application/pkix-attr-cert")) {
            $exceptions[] = new Exception("The format seems to be wrong for your AC3D file \"".$ac3dName."\". AC file needs to be a AC3D file.");
        }
        
        // If error is detected
        if ($arrayAC['error'] != 0) {
            switch ($arrayAC['error']){
                case 1:
                    $errormsg = "The file \"".$ac3dName."\" is bigger than this server installation allows.";
                    break;
                case 2:
                    $errormsg = "The file \"".$ac3dName."\" is bigger than this form allows.";
                    break;
                case 3:
                    $errormsg = "Only part of the file \"".$ac3dName."\" was uploaded.";
                    break;
                case 4:
                    $errormsg = "No file \"".$ac3dName."\" was uploaded.";
                    break;
                default:
                    $errormsg = "There has been an unknown error while uploading the file \"".$ac3dName."\".";
                    break;
            }
            
            $exceptions[] = new Exception($errormsg);
        }
        
        return $exceptions;
    }
    
    public function checkThumbFileArray($arrayThumb) {
        $thumbName = $arrayThumb['name'];
        $exceptions = array();
        
        // check file size
        if ($arrayThumb['size'] >= 2000000) {
            $exceptions[] = new Exception("Sorry, but the size of your thumbnail file \"".$thumbName."\" exceeds 2Mb (current size: ".$_FILES['mo_thumbfile']['size']." bytes).");
        }
        
        // check type
        if ($arrayThumb['type'] != "image/jpeg") { 
            $exceptions[] = new Exception("The file format of your thumbnail file \"".$thumbName."\" seems to be wrong. Thumbnail needs to be a JPEG file.");
        }

        // If an error is detected
        if ($arrayThumb['error'] != 0) {
            switch ($arrayThumb['error']) {
                case 1:
                    $errormsg = "The file \"".$thumbName."\" is bigger than this server installation allows.";
                    break;
                case 2:
                    $errormsg = "The file \"".$thumbName."\" is bigger than this form allows.";
                    break;
                case 3:
                    $errormsg = "Only part of the file \"".$thumbName."\" was uploaded.";
                    break;
                case 4:
                    $errormsg = "No file \"".$thumbName."\" was uploaded.";
                    break;
                default:
                    $errormsg = "There has been an unknown error while uploading the file \"".$thumbName."\".";
                    break;
            }
            
            $exceptions[] = new Exception($errormsg);
        }

        return $exceptions;
    }
    
    public function checkPNGArray($arrayPNG) {
        $pngName = $arrayPNG['name'];
        $exceptions = array();
        
        // check size file
        if ($arrayPNG['size'] >= 2000000) {
            $exceptions[] = new Exception("Sorry, but the size of your texture file \"".$pngName."\" exceeds 2Mb (current size: ".$pngsize." bytes).");
        }
        
        // check type
        if ($arrayPNG['type'] != 'image/png') {
            $exceptions[] = new Exception("The format of your texture file \"".$pngName."\" seems to be wrong. Texture file needs to be a PNG file.");
        }
            
        
        // If error is detected
        if ($arrayPNG['error'] != 0) {
            switch ($arrayPNG['error']) {
                case 1:
                    $errormsg = "The file \"".$pngName."\" is bigger than this server installation allows.";
                    break;
                case 2:
                    $errormsg = "The file \"".$pngName."\" is bigger than this form allows.";
                    break;
                case 3:
                    $errormsg = "Only part of the file \"".$pngName."\" was uploaded.";
                    break;
                case 4:
                    $errormsg = "No file \"".$pngName."\" was uploaded.";
                    break;
                default:
                    $errormsg = "There has been an unknown error while uploading the file \"".$pngName."\".";
                    break;
            }
            
            $exceptions[] = new Exception($errormsg);
        }
        
        return $exceptions;
    }
    
    /**
     * Opens a working directory for the new uploaded model.
     * 
     * @param type $parentDir path
     * @return string new directory path
     * @throws Exception
     */
    public function openWorkingDirectory($parentDir) {
        $targetPath = $parentDir . "/static_".random_suffix()."/";
        while (file_exists($targetPath)) {
            // Makes concurrent access impossible: the script has to wait if this directory already exists.
            usleep(500);
        }

        if (!mkdir($targetPath)) {
            throw new Exception("Impossible to create temporary directory ".$targetPath);
        }
        
        return $targetPath;
    }
    
    public function generateModelFilesPackage($targetDir, $modelFolderPath) {
        // Create, fill archive file and compress it
        $phar = new PharData($targetDir . '/static.tar');
        $phar->buildFromDirectory($modelFolderPath);
        $phar->compress(Phar::GZ);
        
        // Delete archive file
        unlink($targetDir . '/static.tar');
        // Rename compress file
        rename($targetDir . '/static.tar.gz', $targetDir.'/static.tgz');

        $handle    = fopen($targetDir."/static.tgz", "r");
        $contents  = fread($handle, filesize($targetDir."/static.tgz"));
        fclose($handle);
        
        // Dump & encode the file
        return base64_encode($contents);
    }
    
    public function dos2Unix($filePath) {
        // Dos2unix file
        system('dos2unix '.$filePath);
    }
    
    public function archiveModel($targetPath) {
        $tmp_dir = sys_get_temp_dir();
        
        $phar = new PharData($tmp_dir . '/static.tar');                // Create archive file
        $phar->buildFromDirectory($targetPath);                        // Fills archive file
        $phar->compress(Phar::GZ);                                     // Convert archive file to compress file
        unlink($tmp_dir . '/static.tar');                              // Delete archive file
        rename($tmp_dir . '/static.tar.gz', $tmp_dir.'/static.tgz');   // Rename compress file

        $handle    = fopen($tmp_dir."/static.tgz", "r");
        $contents  = fread($handle, filesize($tmp_dir."/static.tgz"));
        fclose($handle);
        
        unlink($tmp_dir . '/static.tgz');
        
        return $contents;
    }
}
