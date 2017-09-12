<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Platform;

use Buzzi\Base\Platform\SdkFactory;

class Registry
{
    /**
     * @var \Buzzi\Base\Platform\SdkFactory
     */
    protected $sdkFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Buzzi\Sdk[]
     */
    protected $register = [];

    /**
     * @param \Buzzi\Base\Platform\SdkFactory $sdkFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Buzzi\Base\Platform\SdkFactory $sdkFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->sdkFactory = $sdkFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int|string|null $storeId
     * @return \Buzzi\Sdk
     */
    public function getSdk($storeId = null)
    {
        $storeId = $this->storeManager->getStore($storeId)->getId();

        if (empty($this->register[$storeId])) {
            $this->register[$storeId] = $this->sdkFactory->create([SdkFactory::CONFIG_STORE => $storeId]);
        }

        return $this->register[$storeId];
    }
}
