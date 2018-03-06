<?php

namespace Made\PaidModifier\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SETTINGS_ACTIVE   = 'paid_modifier/settings/active';
    const XML_PATH_SETTINGS_MODIFIER = 'paid_modifier/settings/modifier';

    public function isModuleActive()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_SETTINGS_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getModifier()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SETTINGS_MODIFIER,
            ScopeInterface::SCOPE_STORE
        );
    }
}
