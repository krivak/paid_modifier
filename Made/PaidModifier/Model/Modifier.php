<?php

namespace Made\PaidModifier\Model;

class Modifier extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Made\PaidModifier\Model\ResourceModel\Modifier::class);
    }
}
