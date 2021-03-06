<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

/**
 * Storage adapter that uses the system temp directory for its filesystem. 
 *
 * After the adapter is no longer needed (__destruct()), all the files that were 
 * created during its lifetime are removed.
 *
 * Used primarily by the test framework.
 *
 * @package Omeka
 */
class Omeka_Storage_Adapter_TempFilesystem extends Omeka_Storage_Adapter_Filesystem
{
    /**
     * No need to perform this check.
     */
    public function canStore()
    {
        return true;
    }

    /**
     * Move a local file to "storage."
     *
     * @param string $source Local filesystem path to file.
     * @param string $dest Destination path.
     */
    public function store($source, $dest)
    {
        $this->_mkdir($dest);
        parent::store($source, $dest);
    }

    /**
     * Move a file between two "storage" locations.
     *
     * @param string $source Original stored path.
     * @param string $dest Destination stored path.
     */
    public function move($source, $dest)
    {
        $this->_mkdir($dest);
        return parent::move($source, $dest);
    }

    public function getUri($path)
    {
        return '/' . $path;
    }

    private function _mkdir($filepath)
    {
        $absPath = $this->_localDir . '/' . $filepath;
        // Meant to stub out filesystem behavior, prevent failure due to 
        // missing subdirectories.
        if (!is_dir(dirname($absPath))) {
            mkdir(dirname($absPath), 0770, true);
        }
    }
}
