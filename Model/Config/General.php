<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Model\Config;

class General
{
    const XML_PATH_ENABLED = 'buzzi_base/general/enabled';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Buzzi\Base\Model\Config\ScopeDefiner
     */
    protected $scopeDefiner;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Buzzi\Base\Model\Config\ScopeDefiner $scopeDefiner
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Buzzi\Base\Model\Config\ScopeDefiner $scopeDefiner
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->scopeDefiner = $scopeDefiner;
    }

    /**
     * @param int|string|null $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, $this->scopeDefiner->getScope(), $store);
    }
}
