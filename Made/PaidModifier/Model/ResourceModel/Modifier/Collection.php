<?php

namespace Made\PaidModifier\Model\ResourceModel\Modifier;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Made\PaidModifier\Model\Modifier::class,
            \Made\PaidModifier\Model\ResourceModel\Modifier::class
        );
    }
}
