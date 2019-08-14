<?php

/**
 * Team23 SeoPagination
 *
 * @category  Team23
 * @package   Team23_SeoPagination
 * @version   1.0.0
 * @copyright 2016 Team23 GmbH & Co. KG (http://www.team23.de)
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */


class Team23_SeoPagination_Model_Observer
{

    /**
     * Inject SEO pagination, if enabled
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function injectPagination(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('seopagination')->isEnabled())
            return $this;

        try {
            $paginator = Mage::getModel('seopagination/paginator');
            $paginator->createLinks();
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $this;
    }
    
}
