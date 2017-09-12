<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class PayloadFile
{
    /**
     * @var string
     */
    protected static $namespace = 'buzzi';

    /**
     * @var string
     */
    protected $subFolder;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $directory;

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     * @param string $subFolder
     */
    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        $subFolder
    ) {
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->subFolder = (string)$subFolder;
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getFilePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [self::$namespace, $this->subFolder, $fileName]);
    }

    /**
     * @param string $jsonData
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function save($jsonData)
    {
        $fileName = md5($jsonData) . '.json';

        $this->directory->writeFile($this->getFilePath($fileName), $jsonData);

        return $fileName;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function load($fileName)
    {
        return $this->directory->readFile($this->getFilePath($fileName));
    }

    /**
     * @param string $fileName
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function delete($fileName)
    {
        return $this->directory->delete($this->getFilePath($fileName));
    }
}
