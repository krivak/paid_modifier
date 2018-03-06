<?php

namespace Made\PaidModifier\Observer;

class SalesOrderInvoicePayObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $helper;
    protected $order;
    protected $priceCurrency;
    protected $modifierFactory;
    protected $logger;

    public function __construct(
        \Made\PaidModifier\Helper\Data $helper,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Made\PaidModifier\Model\ModifierFactory $modifierFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->order = $order;
        $this->priceCurrency = $priceCurrency;
        $this->modifierFactory = $modifierFactory;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->isModuleActive()) {
            return $this;
        }

        $invoice = $observer->getEvent()->getInvoice();
        $orderId = $invoice->getOrderId();
        $order   = $this->order->load($orderId);

        $orderBaseTotalDue     = $order->getBaseTotalDue();
        $orderTotalDue         = $order->getTotalDue();
        $invoiceBaseGrandTotal = $invoice->getBaseGrandTotal();
        $invoiceGrandTotal     = $invoice->getGrandTotal();

        $remainingBaseTotalDue = max($this->priceCurrency->round($orderBaseTotalDue - $invoiceBaseGrandTotal), 0);
        $remainingTotalDue     = max($this->priceCurrency->round($orderTotalDue - $invoiceGrandTotal), 0);

        if ($remainingBaseTotalDue === 0.0 && $remainingTotalDue === 0.0) {
            $modifier = $this->helper->getModifier();

            $weightedBaseTotalPaid = $this->priceCurrency->round(($order->getBaseTotalPaid() + $invoiceBaseGrandTotal) * $modifier);
            $weightedTotalPaid     = $this->priceCurrency->round(($order->getTotalPaid() + $invoiceGrandTotal) * $modifier);

            try {
                $this->modifierFactory
                    ->create()
                    ->setOrderId($orderId)
                    ->setModifier($modifier)
                    ->setWeightedBaseTotalPaid($weightedBaseTotalPaid)
                    ->setWeightedTotalPaid($weightedTotalPaid)
                    ->save();
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $this;
    }
}
