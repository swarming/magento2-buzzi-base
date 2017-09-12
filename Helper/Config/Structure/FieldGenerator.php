<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Helper\Config\Structure;

class FieldGenerator
{
    /**
     * @var array
     */
    protected $elementFields = [
        '_elementType' => 'field',
        'id' => null,
        'path' => null,
        'label' => null,
        'comment' => null,
        'type' => null,
        'source_model' => null,
        'frontend_model' => null,
        'backend_model' => null,
        'sortOrder' => null,
        'showInDefault' => '1',
        'showInWebsite' => '0',
        'showInStore' => '0',
        'canRestore' => '0'
    ];

    /**
     * @param array $data
     * @return array
     */
    public function generate($data)
    {
        foreach ($this->elementFields as $fieldName => $defaultValue) {
            $value = isset($data[$fieldName]) ? $data[$fieldName] : $defaultValue;
            if (null === $value) {
                continue;
            }

            $data[$fieldName] = $value;
        }

        if (isset($data['depends'])) {
            $data['depends'] = $this->generateDepends($data['path'], (array)$data['depends']);
        }

        return $data;
    }

    /**
     * @param string $path
     * @param array $depends
     * @return array
     */
    protected function generateDepends($path, $depends)
    {
        $dependsFieldSets = [];
        foreach ($depends as $field => $value) {
            $dependPath = $path . '/' . $field;
            $dependsFieldSets[$field] = [
                '_elementType' => 'field',
                'id' => $dependPath,
                'value' => $value,
                'dependPath' => explode('/', $dependPath)
            ];
        }

        return ['fields' => $dependsFieldSets];
    }
}
