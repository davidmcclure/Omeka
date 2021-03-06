<?php
class Table_SearchText extends Omeka_Db_Table
{
    /**
     * Find search text by record.
     * 
     * @param string $recordType
     * @param int $recordId
     * @return SearchText|null
     */
    public function findByRecord($recordType, $recordId)
    {
         $select = $this->getSelect();
         $select->where('record_type = ?', $recordType);
         $select->where('record_id = ?', $recordId);
         return $this->fetchObject($select);
    }
    
    public function applySearchFilters($select, $params)
    {
        // Set the base select statement.
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns(array(
            'record_type', 'record_id', 'title', 
            'relevance' => new Zend_Db_Expr(
                'MATCH (`text`) AGAINST (' . $this->getDb()->quote($params['query']) . ')'
            )
        ));
        $select->where('MATCH (`text`) AGAINST (?)', $params['query']);
        
        // Search only those record types that are configured to be searched.
        $searchRecordTypes = Mixin_Search::getSearchRecordTypes();
        if ($searchRecordTypes) {
            $select->where('`record_type` IN (?)', $searchRecordTypes);
        }
        
        // Search on an specific record type.
        if (isset($params['record_types'])) {
            $select->where('`record_type` IN (?)', $params['record_types']);
        }
        
        // Restrict access to private records.
        $showNotPublic = Zend_Registry::get('bootstrap')->getResource('Acl')
            ->isAllowed(current_user(), 'Search', 'showNotPublic');
        if (!$showNotPublic) {
            $select->where('`public` = 1');
        }
    }
}
