<?php

/*
 * Copyright (C) 2014 julien
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

require_once 'Validator.php';

/**
 * Description of ModelFilesValidator
 *
 * @author julien
 */
class ModelFilesValidator implements Validator {
    
    static private $validDimension = array(1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192);
    
    private $folderPath;
    private $ac3dName;
    private $xmlName;
    private $pngNames;
    
    protected function __construct($folderPath, $ac3dName, $pngNames) {
        $this->folderPath = $folderPath;
        $this->ac3dName = $ac3dName;
        $this->pngNames = $pngNames;
    }
    
    static function instanceWithAC3DOnly($folderPath, $ac3dName, $pngNames) {
        $instance = new self($folderPath, $ac3dName, $pngNames);
        return $instance;
    }
    
    static function instanceWithXML($folderPath, $xmlName, $ac3dName, $pngNames) {
        $instance = new self($folderPath, $ac3dName, $pngNames);
        $instance->setXMLName($xmlName);
        return $instance;
    }
    
    public function validate() {
        $exceptions = array();
        
        // Check XML if set
        if (isset($this->xmlName)) {
            $xmlPath = $this->folderPath . $this->xmlName;
            if (file_exists($xmlPath)) {
                $exceptions = array_merge($exceptions, $this->checkXML($xmlPath, $this->ac3dName));
            } else {
                $exceptions[] = new Exception("XML file not found");
            }
        }

        // Check AC3D file
        $ac3dPath = $this->folderPath . $this->ac3dName;
        $exceptions = array_merge($exceptions, $this->checkAC3D($ac3dPath, $this->pngNames));

        // Check textures files
        for ($i=0; $i<12; $i++) {
            if (isset($this->pngNames[$i]) && ($this->pngNames[$i] != '')) {
                $pngPath  = $this->folderPath . $this->pngNames[$i];
                $pngName  = $this->pngNames[$i];

                $exceptions = array_merge($exceptions, $this->checkPNG($pngName, $pngPath));
            }
        }
        
        return $exceptions;
    }
    
    private function checkXML($xmlPath, $ac3dName) {
        $errors = array();
        $this->depth = array();
        $xml_parser = xml_parser_create();

        xml_set_object($xml_parser, $this);
        xml_set_element_handler($xml_parser, "startElement", "endElement");

        $fp = fopen($xmlPath, "r");
        if (!$fp) {
            $errors[] = new Exception("Could not open XML.");
        } else {
            while ($data = fread($fp, 4096)) {
                // check if tags are closed and if <PropertyList> is present
                if (!xml_parse($xml_parser, $data, feof($fp))) {
                    $errors[] = new Exception("XML error : ".xml_error_string(xml_get_error_code($xml_parser))." at line ".xml_get_current_line_number($xml_parser));
                }
            }
            xml_parser_free($xml_parser);
        }

        if (count($errors) == 0) {
            // Check if <path> == $ac3dName
            $xmlcontent = simplexml_load_file($xmlPath);
            if ($ac3dName != $xmlcontent->path) {
                $errors[] = new Exception("The value of the &lt;path&gt; tag in your XML file doesn't match the AC file you provided!");
            }

            // Check if the file begin with <?xml> tag
            $xmltag = str_replace(array("<", ">"), array("&lt;", "&gt;"), file_get_contents($xmlPath));
            if (!preg_match('#^&lt;\?xml version="1\.0" encoding="UTF-8" \?&gt;#i', $xmltag)) {
                $errors[] = new Exception("Your XML must start with &lt;?xml version=\"1.0\" encoding=\"UTF-8\" ?&gt;!");
            }
        }
        
        return $errors;
    }
    
    private function startElement($parser, $name, $attrs) {
        $parserInt = intval($parser);
        if (!isset($this->depth[$parserInt])) {
            $this->depth[$parserInt] = 0;
        }
        $this->depth[$parserInt]++;
    }

    private function endElement($parser, $name) {
        $parserInt = intval($parser);
        if (!isset($this->depth[$parserInt])) {
            $this->depth[$parserInt] = 0;
        }
        $this->depth[$parserInt]--;
    }
    
    private function checkAC3D($ac3dPath, $pngNames) {
        $errors = array();
        $handle = fopen($ac3dPath, 'r');

        if (!$handle) {
            $errors[] = new Exception("The AC file does not exist on the server. Please try to upload it again!");
            return $errors;
        }
        
        $i = 1;
        while (!feof($handle)) {
            $line = fgets($handle);
            $line = rtrim($line, "\r\n") . PHP_EOL;

            // Check if the file begins with the string "AC3D"
            if ($i == 1 && substr($line,0,4) != "AC3D") {
                $errors[] = new Exception("The AC file does not seem to be a valid AC3D file. The first line must show \"AC3Dx\" with x = version");
            }

            // Check if the texture reference matches $pngName
            if (preg_match('#^texture#', $line)) {
                $data = preg_replace('#texture "(.+)"$#', '$1', $line);
                $data = substr($data, 0, -1);
                if (!in_array($data, $pngNames)) {
                    $errors[] = new Exception("The texture reference (".$data.") in your AC file at line ".$i." seems to have a different name than the PNG texture(s) file(s) name(s) you provided!");
                }
            }
            $i++;
        }
        fclose($handle);
        
        return $errors;
    }
    
    private function checkPNG($pngName, $pngPath) {
        $errors = array();
        
        if (file_exists($pngPath)) {
            $tmp    = getimagesize($pngPath);
            $width  = $tmp[0];
            $height = $tmp[1];
            $mime   = $tmp["mime"];

            // Check if PNG file is a valid PNG file (compare the type file)
            if ($mime != "image/png") {
                $errors[] = new Exception("Your texture file does not seem to be a PNG file. Please upload a valid PNG file.");
            }

            // Check if PNG dimensions are a multiple of ^2
            if (!in_array($height, self::$validDimension) || !in_array($width, self::$validDimension)) {
                $errors[] = new Exception("The size in pixels of your texture file (".$pngName.") appears not to be a power of 2.");
            }
        }
        else {
            $errors[] = new Exception("The texture file does not exist on the server. Please try to upload it again.");
        }
        
        return $errors;
    }
    
    public function setXMLName($xmlName) {
        $this->xmlName = $xmlName;
    }
}
