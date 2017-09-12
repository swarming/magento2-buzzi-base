<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Helper\Config\Structure;

class CronFieldsGenerator
{
    /**
     * @var \Buzzi\Base\Helper\Config\Structure\FieldGenerator
     */
    protected $fieldGenerator;

    /**
     * @param \Buzzi\Base\Helper\Config\Structure\FieldGenerator $fieldGenerator
     */
    public function __construct(
        \Buzzi\Base\Helper\Config\Structure\FieldGenerator $fieldGenerator
    ) {
        $this->fieldGenerator = $fieldGenerator;
    }

    /**
     * @param string $path
     * @param bool $isCronOnly
     * @return array
     */
    public function generate($path, $isCronOnly)
    {
        $sortOrder = 100;

        $fields = [];

        $depends = [];
        if (!$isCronOnly) {
            $fields['is_cron'] = $this->fieldGenerator->generate(
                [
                    'id' => 'is_cron',
                    'path' => $path,
                    'type' => 'select',
                    'source_model' => \Magento\Config\Model\Config\Source\Yesno::class,
                    'label' => (string)__('Process on Cron'),
                    'comment' => (string)__('Disabling can impact performance.'),
                    'showInWebsite' => '1',
                    'sortOrder' => ++$sortOrder,
                    'canRestore' => '1'
                ]
            );
            $depends = ['is_cron' => '1'];
        }

        $fields['cron_settings'] = $this->fieldGenerator->generate(
            [
                'id' => 'cron_settings',
                'path' => $path,
                'frontend_model' => \Magento\Config\Block\System\Config\Form\Field\Heading::class,
                'label' => (string)__('Cron Settings'),
                'sortOrder' => ++$sortOrder,
                'depends' => $depends
            ]
        );

        $fields['global_schedule'] = $this->fieldGenerator->generate(
            [
                'id' => 'global_schedule',
                'path' => $path,
                'type' => 'select',
                'source_model' => \Magento\Config\Model\Config\Source\Yesno::class,
                'label' => (string)__('Global Schedule'),
                'sortOrder' => ++$sortOrder,
                'depends' => $depends,
                'canRestore' => '1'
            ]
        );

        $fields['custom_schedule'] = $this->fieldGenerator->generate(
            [
                'id' => 'custom_schedule',
                'path' => $path,
                'type' => 'select',
                'source_model' => \Magento\Config\Model\Config\Source\Yesno::class,
                'label' => (string)__('Global Schedule'),
                'comment' => (string)__('Enter if you know what you are doing. The value is not validated.'),
                'sortOrder' => ++$sortOrder,
                'depends' => array_merge($depends, ['global_schedule' => '0']),
                'canRestore' => '1'
            ]
        );

        $fields['cron_schedule'] = $this->fieldGenerator->generate(
            [
                'id' => 'cron_schedule',
                'path' => $path,
                'type' => 'text',
                'label' => (string)__('Cron Schedule'),
                'sortOrder' => ++$sortOrder,
                'depends' => array_merge($depends, ['global_schedule' => '0', 'custom_schedule' => '1'])
            ]
        );

        $fields['cron_start_time'] = $this->fieldGenerator->generate(
            [
                'id' => 'cron_start_time',
                'path' => $path,
                'type' => 'time',
                'label' => (string)__('Start Time'),
                'sortOrder' => ++$sortOrder,
                'depends' => array_merge($depends, ['global_schedule' => '0', 'custom_schedule' => '0'])
            ]
        );

        $fields['cron_frequency'] = $this->fieldGenerator->generate(
            [
                'id' => 'cron_frequency',
                'path' => $path,
                'type' => 'select',
                'source_model' => \Buzzi\Base\Model\Config\Source\CronFrequency::class,
                'label' => (string)__('Frequency'),
                'sortOrder' => ++$sortOrder,
                'depends' => array_merge($depends, ['global_schedule' => '0', 'custom_schedule' => '0'])
            ]
        );

        return $fields;
    }
}
