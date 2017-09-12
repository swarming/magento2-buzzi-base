<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Model\Config;

use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;

class ScopeDefiner
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \Magento\Config\Model\Config\ScopeDefiner
     */
    protected $appScopeDefiner;

    /**
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Config\Model\Config\ScopeDefiner $appScopeDefiner
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\Config\Model\Config\ScopeDefiner $appScopeDefiner
    ) {
        $this->appState = $appState;
        $this->appScopeDefiner = $appScopeDefiner;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->appState->getAreaCode() == Area::AREA_ADMINHTML
            ? $this->appScopeDefiner->getScope()
            : ScopeInterface::SCOPE_STORE;
    }
}
