<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Leopaulo88\Asaas\Entities\BaseEntity;

class SplitProcessor extends BaseEntity
{
    public function process(array &$data): void
    {

        $isInstallment = ! empty($data['installmentCount']) && $data['installmentCount'] > 1;

        if ($isInstallment) {
            unset($data['installmentValue']);
        }

        foreach ($data['split'] as $key => $splitItem) {
            if (is_array($splitItem)) {
                $this->cleanSplitArray($data['split'][$key], $isInstallment);
            } elseif (is_object($splitItem)) {
                $this->cleanSplitObject($splitItem, $isInstallment);
            }
        }
    }

    private function cleanSplitArray(array &$splitItem, bool $isInstallment): void
    {
        $hasPercentual = ! empty($splitItem['percentualValue']);
        $hasFixed = ! empty($splitItem['fixedValue']);
        $hasTotalFixed = ! empty($splitItem['totalFixedValue']);

        if ($hasPercentual) {
            $this->removeFields($splitItem, ['fixedValue', 'totalFixedValue']);
        } elseif ($isInstallment && $hasTotalFixed) {
            $this->removeFields($splitItem, ['fixedValue', 'percentualValue']);
        } elseif (! $isInstallment && $hasFixed) {
            $this->removeFields($splitItem, ['percentualValue', 'totalFixedValue']);
        } else {
            $fieldsToRemove = $isInstallment ? ['fixedValue'] : ['totalFixedValue'];
            $this->removeFields($splitItem, $fieldsToRemove);
        }
    }

    private function cleanSplitObject(object $splitItem, bool $isInstallment): void
    {
        $hasPercentual = property_exists($splitItem, 'percentualValue') && ! empty($splitItem->percentualValue);
        $hasFixed = property_exists($splitItem, 'fixedValue') && ! empty($splitItem->fixedValue);
        $hasTotalFixed = property_exists($splitItem, 'totalFixedValue') && ! empty($splitItem->totalFixedValue);

        if ($hasPercentual) {
            $this->removeProperties($splitItem, ['fixedValue', 'totalFixedValue']);
        } elseif ($isInstallment && $hasTotalFixed) {
            $this->removeProperties($splitItem, ['fixedValue', 'percentualValue']);
        } elseif (! $isInstallment && $hasFixed) {
            $this->removeProperties($splitItem, ['percentualValue', 'totalFixedValue']);
        } else {
            $fieldsToRemove = $isInstallment ? ['fixedValue'] : ['totalFixedValue'];
            $this->removeProperties($splitItem, $fieldsToRemove);
        }
    }

    private function removeFields(array &$splitItem, array $fields): void
    {
        foreach ($fields as $field) {
            unset($splitItem[$field]);
        }
    }

    private function removeProperties(object $splitItem, array $properties): void
    {
        foreach ($properties as $property) {
            if (property_exists($splitItem, $property)) {
                unset($splitItem->$property);
            }
        }
    }
}
