<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Model\Config\Source;

class Environment implements \Magento\Framework\Option\ArrayInterface
{
    const PRODUCTION = 'production';
    const SANDBOX = 'sandbox';
    const CUSTOM = 'custom';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PRODUCTION, 'label' => __('Production')],
            ['value' => self::SANDBOX, 'label' => __('Sandbox')],
            ['value' => self::CUSTOM, 'label' => __('Custom')]
        ];
    }
}
