<?php

namespace Made\PaidModifier\Model\ResourceModel;

class Modifier extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('made_paid_modifier', 'entity_id');
    }
}
