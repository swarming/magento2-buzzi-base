<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Model\Config;

use Magento\Framework\App\Filesystem\DirectoryList;
use Buzzi\Base\Model\Config\Source\Environment;

class Api extends \Buzzi\Base\Model\Config\General
{
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Buzzi\Base\Model\Config\ScopeDefiner $scopeDefiner
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Buzzi\Base\Model\Config\ScopeDefiner $scopeDefiner,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->directoryList = $directoryList;
        parent::__construct($scopeConfig, $scopeDefiner);
    }

    /**
     * @param int|string|null $store
     * @return string
     */
    public function getEnvironment($store = null)
    {
        return $this->scopeConfig->getValue('buzzi_base/api/environment', $this->scopeDefiner->getScope(), $store);
    }

    /**
     * @param int|string|null $store
     * @return string
     */
    public function getHost($store = null)
    {
        return $this->getEnvironment($store) == Environment::CUSTOM
            ? $this->scopeConfig->getValue('buzzi_base/api/custom_host', $this->scopeDefiner->getScope(), $store)
            : null;
    }

    /**
     * @param int|string|null $store
     * @return string
     */
    public function getAuthId($store = null)
    {
        return $this->scopeConfig->getValue(
            "buzzi_base/api/{$this->getEnvironment($store)}_id",
            $this->scopeDefiner->getScope(),
            $store
        );
    }

    /**
     * @param int|string|null $store
     * @return string
     */
    public function getAuthSecret($store = null)
    {
        return $this->getAuthSecretForEnvironment($this->getEnvironment($store), $store);
    }

    /**
     * @param string $environment
     * @param int|string|null $store
     * @return string
     */
    public function getAuthSecretForEnvironment($environment, $store = null)
    {
        return $this->scopeConfig->getValue(
            "buzzi_base/api/{$environment}_secret",
            $this->scopeDefiner->getScope(),
            $store
        );
    }

    /**
     * @param int|string|null $store
     * @return bool
     */
    public function isDebug($store = null)
    {
        return $this->scopeConfig->isSetFlag('buzzi_base/api/debug', $this->scopeDefiner->getScope(), $store);
    }

    /**
     * @return string
     */
    public function getLogFilename()
    {
        $fileName = $this->scopeConfig->getValue('buzzi_base/api/log_filename');
        $varDir = $this->directoryList->getPath(DirectoryList::VAR_DIR);
        return $varDir . DIRECTORY_SEPARATOR . ltrim($fileName, DIRECTORY_SEPARATOR);
    }
}
