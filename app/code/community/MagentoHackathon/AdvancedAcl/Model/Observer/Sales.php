<?php

class MagentoHackathon_AdvancedAcl_Model_Observer_Sales
{

    /**
     * filter the sales order grid for allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterOrderGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderGridCollection();
        $this->filterCollection($collection);

    }

    /**
     * filter sales order invoices collection by  allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterInvoiceGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderInvoiceGridCollection();
        $this->filterCollection($collection);
    }

    /**
     * filters shipment grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterShipmentsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderShipmentGridCollection();
        $this->filterCollection($collection);
    }


    /**
     * filters credit memos grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCreditMemoGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $this->filterCollection($collection);
    }

    public function filterAgreements(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $this->filterCollection($collection);
    }

    /**
     * filters agreements grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterAgreementsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Checkout_Model_Resource_Agreement_Collection) {
            // getStoreIds() includes a DB query, so only execute this if this is the correct collection!
            $storeIds = $this->getStoreIds();
            if (! empty($storeIds) && ! in_array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeIds)) {
                $collection->setIsStoreFilterWithAdmin(false);
                $collection->addStoreFilter($storeIds);
            }
        }
        if ($collection instanceof Mage_Sales_Model_Resource_Order_Payment_Transaction_Collection) {
            // getStoreIds() includes a DB query, so only execute this if this is the correct collection!
            $storeIds = $this->getStoreIds();
            $collection->addStoreFilter($storeIds);
        }
    }


    /**
     * filters recurring profile grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterRecurringProfilesGrid(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Resource_Recurring_Profile_Collection $collection */
        $collection = $observer->getRecurringProfileCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addFieldToFilter('store_id', array('in' => $storeIds));
        }
    }


    /**
     * retrieves allowed store ids 
     *
     * @return mixed
     */
    protected function getStoreIds()
    {
        return Mage::helper('magentohackathon_advancedacl/data')->getActiveRole()->getStoreIds();
    }

    /**
     * @param $collection
     */
    public function filterCollection($collection)
    {
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }

}
