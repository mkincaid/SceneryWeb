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

namespace controller;

/**
 * Controller for object view
 */
class ObjectsController extends ControllerMenu {
    private $objectDaoRO;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->objectDaoRO = \dao\DAOFactory::getInstance()->getObjectDaoRO();
    }
    
    /**
     * Action for object view
     */
    public function viewAction() {
        $id = $this->getVar('id');
        if (\FormChecker::isObjectId($id)) {
            $object = $this->objectDaoRO->getObject($id);
            $modelMetadata = $this->getModelDaoRO()->getModelMetadata($object->getModelId());
            $group = $this->objectDaoRO->getObjectsGroup($object->getGroupId());
            include 'view/objectview.php';
        } else {
            $page_title = "Object ID not valid";
            $error_text = "Sorry, but the object ID you are asking is not valid.";
            include 'view/error_page.php';
        }
    }
}