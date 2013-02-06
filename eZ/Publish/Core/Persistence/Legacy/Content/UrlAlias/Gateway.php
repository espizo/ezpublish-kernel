<?php
/**
 * File containing the UrlAlias Gateway class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Legacy\Content\UrlAlias;

/**
 * UrlAlias Gateway
 */
abstract class Gateway
{
    /**
     * Loads list of aliases by given $locationId.
     *
     * @param mixed $locationId
     * @param boolean $custom
     *
     * @return array
     */
    abstract public function loadLocationEntries( $locationId, $custom = false );

    /**
     * Loads paged list of global aliases.
     *
     * @param string|null $languageCode
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    abstract public function listGlobalEntries( $languageCode = null, $offset = 0, $limit = -1 );

    /**
     * Returns boolean indicating if the row with given $id is special root entry.
     *
     * Special root entry entry will have parentId=0 and text=''.
     * In standard installation this entry will point to location with id=2.
     *
     * @param mixed $id
     *
     * @return boolean
     */
    abstract public function isRootEntry( $id );

    /**
     * Updates single row data matched by composite primary key.
     *
     * Use optional parameter $languageMaskMatch to additionally limit the query match with languages.
     *
     * @param mixed $parentId
     * @param string $textMD5
     * @param array $values associative array with column names as keys and column values as values
     *
     * @return void
     */
    abstract public function updateRow( $parentId, $textMD5, array $values );

    /**
     * Inserts new row in urlalias_ml table.
     *
     * @param array $values
     *
     * @return mixed
     */
    abstract public function insertRow( array $values );

    /**
     * Loads single row matched by composite primary key.
     *
     * @param mixed $parentId
     * @param string $textMD5
     *
     * @return array
     */
    abstract public function loadRow( $parentId, $textMD5 );

    /**
     * Downgrades autogenerated entry matched by given $action and $languageId and negatively matched by
     * composite primary key.
     *
     * If language mask of the found entry is composite (meaning it consists of multiple language ids) given
     * $languageId will be removed from mask. Otherwise entry will be marked as history.
     *
     * @param string $action
     * @param mixed $languageId
     * @param mixed $newId
     * @param mixed $parentId
     * @param string $textMD5
     *
     * @return void
     */
    abstract public function cleanupAfterPublish( $action, $languageId, $newId, $parentId, $textMD5 );

    /**
     * Marks all entries with given $id as history entries.
     *
     * This method is used by Handler::locationMoved(). For this reason rows are not updated with next id value as
     * all entries with given id are being marked as history and there is no need for id separation.
     * Thus only "link" and "is_original" columns are updated.
     *
     * @param mixed $id
     * @param mixed $link
     *
     * @return void
     */
    abstract public function historizeId( $id, $link );

    /**
     * Updates parent id of autogenerated entries.
     *
     * Update includes history entries.
     *
     * @param mixed $oldParentId
     * @param mixed $newParentId
     *
     * @return void
     */
    abstract public function reparent( $oldParentId, $newParentId );

    /**
     * Loads path data identified by given $id.
     *
     * @param mixed $id
     *
     * @return array
     */
    abstract public function loadPathData( $id );

    /**
     * Loads path data identified by given ordered array of hierarchy data.
     *
     * The first entry in $hierarchyData corresponds to the top-most path element in the path, the second entry the
     * child of the first path element and so on.
     * This method is faster than self::getPath() since it can fetch all elements using only one query, but can be used
     * only for autogenerated paths.
     *
     * @param array $hierarchyData
     *
     * @return array
     */
    abstract public function loadPathDataByHierarchy( array $hierarchyData );

    /**
     * Loads complete URL alias data by given array of path hashes.
     *
     * @param string[] $urlHashes URL string hashes
     *
     * @return array
     */
    abstract public function loadUrlAliasData( array $urlHashes );

    /**
     * Loads autogenerated entry id by given $action and optionally $parentId.
     *
     * @param string $action
     * @param mixed|null $parentId
     *
     * @return array
     */
    abstract public function loadAutogeneratedEntry( $action, $parentId = null );

    /**
     * Converts single row matched by composite primary key to NOP type row.
     *
     * @param mixed $parentId
     * @param string $textMD5
     *
     * @return boolean
     */
    abstract public function removeCustomAlias( $parentId, $textMD5 );

    /**
     * Converts all rows with given $action to NOP type rows.
     *
     * @param string $action
     *
     * @return void
     */
    abstract public function removeByAction( $action );

    /**
     * Loads all autogenerated entries with given $parentId with optionally included history entries.
     *
     * @param mixed $parentId
     * @param boolean $includeHistory
     *
     * @return array
     */
    abstract public function loadAutogeneratedEntries( $parentId, $includeHistory = false );

    /**
     * Returns next value for "id" column.
     *
     * @return mixed
     */
    abstract public function getNextId();
}
