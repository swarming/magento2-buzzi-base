<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Helper\Config\Backend;

use Buzzi\Base\Model\Config\Source\CronFrequency;
use Magento\Framework\Exception\CouldNotSaveException;

class CronSetup
{
    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    protected $configValueFactory;

    /**
     * @param \Magento\Framework\App\Config\ValueFactory $configValueFactory
     */
    public function __construct(\Magento\Framework\App\Config\ValueFactory $configValueFactory)
    {
        $this->configValueFactory = $configValueFactory;
    }

    /**
     * @param string $jobName
     * @return string
     */
    protected function getModelConfigPath($jobName)
    {
        return sprintf('crontab/default/jobs/%s/run/model', $jobName);
    }

    /**
     * @param string $jobName
     * @return string
     */
    protected function getScheduleConfigPath($jobName)
    {
        return sprintf('crontab/default/jobs/%s/schedule/cron_expr', $jobName);
    }

    /**
     * @param string $jobName
     * @param string $cronModel
     * @return void
     */
    public function setupModel($jobName, $cronModel)
    {
        $this->saveConfig($this->getModelConfigPath($jobName), $cronModel);
    }

    /**
     * @param string $jobName
     * @param string $cronExprString
     * @return void
     */
    public function setupCustomSchedule($jobName, $cronExprString)
    {
        $this->saveConfig($this->getScheduleConfigPath($jobName), $cronExprString);
    }

    /**
     * @param string $jobName
     * @param int[] $time
     * @param string $frequency
     * @return void
     */
    public function setupSchedule($jobName, $time, $frequency)
    {
        $cronExprString = $this->getCronExpr($time, $frequency);
        $this->setupCustomSchedule($jobName, $cronExprString);
    }

    /**
     * @param string $time
     * @param string $frequency
     * @return string
     */
    protected function getCronExpr($time, $frequency)
    {
        $hours = intval($time[0]);
        $minutes = intval($time[1]);

        $cronExprArray = [
            $this->calculateMinutes($minutes, $frequency),           # Minute
            $this->calculateHours($hours),                           # Hour
            ($frequency == CronFrequency::CRON_MONTHLY) ? '1' : '*', # Day of the Month
            '*',                                                     # Month of the Year
            ($frequency == CronFrequency::CRON_WEEKLY) ? '1' : '*',  # Day of the Week
        ];

        return join(' ', $cronExprArray);
    }

    /**
     * @param int $selectedHours
     * @return string
     */
    protected function calculateHours($selectedHours)
    {
        return $selectedHours === 0 ? '*' : $selectedHours . '-23';
    }

    /**
     * @param int $selectedMinutes
     * @param string $frequency
     * @return string
     */
    protected function calculateMinutes($selectedMinutes, $frequency)
    {
        $minutes = $selectedMinutes === 0 ? '*' : $selectedMinutes . '-59';

        switch ($frequency) {
            case CronFrequency::CRON_HALF_HOUR:
                $minutes .= '/30';
                break;
            case CronFrequency::CRON_QUARTER_HOUR:
                $minutes .= '/15';
                break;
            case CronFrequency::CRON_EVERY_FIVE_MINUTES:
                $minutes .= '/5';
                break;
        }

        return $minutes;
    }

    /**
     * @param string $configPath
     * @param string $value
     * @return void
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function saveConfig($configPath, $value)
    {
        try {
            /** @var $configValue \Magento\Framework\App\Config\Value */
            $configValue = $this->configValueFactory->create();
            $configValue->load($configPath, 'path');
            $configValue->setValue($value);
            $configValue->setPath($configPath);
            $configValue->save();
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('We can\'t save the Cron expression.'));
        }
    }
}
