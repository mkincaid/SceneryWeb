<?php
require_once 'PGDatabase.php';

/**
 * DAO implementation for PostgreSQL database
 */
abstract class PgSqlDAO {
    protected $database;
    
    public function __construct(PGDatabase $database) {
        $this->database = $database;
        date_default_timezone_set("America/Los_Angeles");
    }
    
    /**
     * Generates WHERE clause from criteria
     * @param type $criteria
     * @return string Where clause
     */
    protected function generateWhereClauseCriteria($criteria) {
        $whereClause = "";
        if (isset($criteria) && count($criteria)>0) {
            $whereClause = "";
            $and = '';
            foreach ($criteria as $criterion) {
                $whereClause .= $and . $this->criterionToClause($criterion);
                $and = ' AND ';
            }
        }

        return $whereClause;
    }
    
    private function criterionToClause($criterion) {
        switch($criterion->getOperation()) {
        case Criterion::OPERATION_LIKE:
            $clause = $criterion->getVarName() 
                . " LIKE "
                . "'%" . pg_escape_string($criterion->getValue()) . "%'";
            break;
        case Criterion::OPERATION_LIKE_BEGIN:
            $clause = $criterion->getVarName() 
                . " LIKE "
                . "'" . pg_escape_string($criterion->getValue()) . "%'";
            break;
        case Criterion::OPERATION_LIKE_END:
            $clause = $criterion->getVarName() 
                . " LIKE "
                . "'%" . pg_escape_string($criterion->getValue()) . "'";
            break;
        default:
            $clause = $criterion->getVarName() 
                . $criterion->getOperation()
                . pg_escape_string($criterion->getValue());
        }
        
        return $clause;
    }
}
