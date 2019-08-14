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


class Team23_SeoPagination_Model_Paginator extends Mage_Core_Model_Abstract
{

    /** @var Mage_Page_Block_Html_Pager|null $_pager */
    protected $_pager = null;

    /** @var int|null $_limit */
    protected $_limit = null;

    /**
     * Get product collection from layer
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function _getProductCollection()
    {
        /** @var Mage_Catalog_Model_Layer $layer */
        $layer = Mage::getSingleton('catalog/layer');

        $collection = $layer->getProductCollection();
        $collection->setPageSize($this->_getLimit());

        return $collection;
    }

    /**
     * Get toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    protected function _getToolbar()
    {
        return Mage::app()->getLayout()
            ->createBlock('catalog/product_list_toolbar');
    }

    /**
     * Get product collection limit
     *
     * @return int|null
     */
    protected function _getLimit()
    {
        if (is_null($this->_limit))
            $this->_limit = intval($this->_getToolbar()->getLimit());

        return $this->_limit;
    }

    /**
     * Get pager class
     *
     * @return Mage_Page_Block_Html_Pager
     */
    protected function _getPager()
    {
        if (is_null($this->_pager))
        {
            $collection = $this->_getProductCollection();

            $this->_pager = Mage::app()->getLayout()
                ->createBlock('page/html_pager');
            $this->_pager->setLimit($this->_getLimit())
                ->setCollection($collection);
        }

        return $this->_pager;
    }

    /**
     * Get first page URL
     *
     * @return string
     */
    protected function _getFirstPageUrl()
    {
        $pager = $this->_getPager();

        return $pager->getPagerUrl(array(
            $pager->getPageVarName() => null
        ));
    }

    /**
     * Get previous page URL
     *
     * @return string
     */
    protected function _getPreviousPageUrl()
    {
        $pager = $this->_getPager();

        if ($pager->getCurrentPage() > 2) {
            return $pager->getPreviousPageUrl();
        }

        return $this->_getFirstPageUrl();
    }

    /**
     * Create rel="next" and/or rel="prev" links and canonical tag, if enabled
     */
    public function createLinks()
    {
        /** @var Mage_Page_Block_Html_Head $headBlock */
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        /** @var Mage_Core_Model_Store $store */
        $store = Mage::app()->getStore();

        $usePrevNext = Mage::helper('seopagination')->usePrevNext($store);

        $pager = $this->_getPager();

        if ($pager->getCurrentPage() != 1) {
            $headBlock->setRobots(Mage::helper('seopagination')->getRobots($store));
        }

        if (Mage::helper('seopagination')->useCanonical($store)) {
            $headBlock->removeItem('link_rel', $this->_getCategory()->getUrl());
            $headBlock->removeItem('link_rel', $this->_getFirstPageUrl());

            if ($usePrevNext) {
                $headBlock->addLinkRel('canonical', $this->_getCategory()->getUrl());
            } else {
                $headBlock->addLinkRel('canonical', $this->_getToolbar()->getLimitUrl('all'));
            }
        }

        if ($usePrevNext) {
            if ($pager->getCurrentPage() < $pager->getLastPageNum()) {
                $headBlock->addLinkRel('next',
                    $this->_getPageUrl($pager->getCollection()->getCurPage(+1)));
            }

            if ($pager->getCurrentPage() > 1) {
                $headBlock->addLinkRel('prev',
                    $this->_getPageUrl($pager->getCollection()->getCurPage(-1)));
            }
        }
    }

    /**
     * Get pager URL
     *
     * @param int $page
     * @return string
     */
    protected function _getPageUrl($page)
    {
        return $this->_getCategory()->getUrl() . '?p' . (string) $page;
    }

    /**
     * Get current category
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _getCategory()
    {
        return Mage::registry('current_category');
    }

}
