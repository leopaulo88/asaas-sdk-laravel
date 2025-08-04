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
        $fieldToRemove = $isInstallment ? 'fixedValue' : 'totalFixedValue';
        unset($splitItem[$fieldToRemove]);
    }

    private function cleanSplitObject(object $splitItem, bool $isInstallment): void
    {
        $fieldToRemove = $isInstallment ? 'fixedValue' : 'totalFixedValue';
        if (property_exists($splitItem, $fieldToRemove)) {
            unset($splitItem->$fieldToRemove);
        }
    }
}
