<?php

namespace Made\PaidModifier\Block\Adminhtml\Order\Totals;

class WeightedPaid extends \Magento\Sales\Block\Adminhtml\Order\Totals
{
    protected $helper;
    protected $modifier;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Made\PaidModifier\Helper\Data $helper,
        \Made\PaidModifier\Model\Modifier $modifier,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->modifier = $modifier;

        parent::__construct(
            $context,
            $registry,
            $adminHelper,
            $data
        );
    }

    public function initTotals()
    {
        if (!$this->helper->isModuleActive()) {
            return $this;
        }

        $modifier = $this->modifier->load($this->getOrder()->getId(), 'order_id');
        if (!$modifier || !$modifier->getId()) {
            return $this;
        }

        $total = new \Magento\Framework\DataObject([
            'code'       => $this->getNameInLayout(),
            'label'      => __('Total Paid (Modifier: %1)', $modifier->getModifier()),
            'base_value' => $modifier->getWeightedBaseTotalPaid(),
            'value'      => $modifier->getWeightedTotalPaid(),
            'area'       => 'footer'
        ]);

        $this->getParentBlock()->addTotalBefore($total, 'refunded');

        return $this;
    }
}
