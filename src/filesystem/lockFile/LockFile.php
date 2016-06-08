<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 07.06.2016 10:09
 */

namespace skobka\appDi\filesystem\lockFile;

use skobka\appDi\filesystem\lockFile\exceptions\LockFileExistsException;

/**
 * Class LockFile
 */
class LockFile
{
    /**
     * @var string
     */
    protected $fileName;
    /**
     * @var int
     */
    protected $fileMode;

    /**
     * LockFile constructor.
     * @param string $fileName
     * @param int $fileMode
     */
    public function __construct($fileName, $fileMode)
    {
        $this->fileName = $fileName;
        $this->fileMode = $fileMode;
    }

    /**
     * Creates lock file and set up permissions
     * Target directory must exists and be writable
     * @throws LockFileExistsException
     */
    public function create()
    {
        if ($this->exists()) {
            throw new LockFileExistsException();
        }

        file_put_contents($this->fileName, "");
        chmod($this->fileName, $this->fileMode);
    }

    /**
     * Write data in lock file
     * @param string $data
     */
    public function write($data)
    {
        file_put_contents($this->fileName, $data);
    }

    /**
     * Remove lock file
     */
    public function delete()
    {
        if ($this->exists()) {
            unlink($this->fileName);
        }
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->fileName);
    }
}
