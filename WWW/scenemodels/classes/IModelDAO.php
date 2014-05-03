<?php

/**
 * Interface for Model Data Access Object
 *
 * @author     Julien Nguyen <julien.nguyen3@gmail.com>
 * @copyright  2014 - FlightGear Team
 * @license    http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

interface IModelDAO {

    public function addModel($model);

    public function updateModel($model);
    
    public function getModel($modelId);
    
    public function countTotalModels();
    
    public function countModelsNoThumb();
    
    public function addModelMetadata($modelMetadata);

    public function updateModelMetadata($modelMetadata);
    
    public function getModelMetadata($modelId);
    
    public function getModelMetadatas($offset, $pagesize);

    public function getModelMetadatasByAuthor($authorId);
    
    public function getModelMetadatasByGroup($modelId, $offset, $pagesize);
    
    public function getModelMetadatasNoThumb($offset, $pagesize);
    
    public function getPaths();
    
    public function getModelsGroup($groupId);
    
    public function getModelsGroups();
    
    public function getModelFiles($modelId);
}

?>
