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


class Team23_SeoPagination_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Paths to module config
     */
    const XML_PATH_USE_PREVNEXT   = 'catalog/seo/relprevnext';
    const XML_PATH_USE_CANONICAL  = 'catalog/seo/canonical';
    const XML_PATH_ROBOTS_SETTING = 'catalog/seo/robots';
    const XML_PATH_ALLOW_ALL      = 'catalog/frontend/list_allow_all';

    /**
     * Check if module output is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if (!parent::isModuleOutputEnabled($this->_getModuleName())) {
            return false;
        }

        return true;
    }

    /**
     * Check if rel="prev" and rel="next" is in use
     *
     * @param Mage_Core_Model_Store $store
     * @return bool
     */
    public function usePrevNext($store)
    {
        return (bool) Mage::getStoreConfigFlag(self::XML_PATH_USE_PREVNEXT, $store)
            && !$this->isAllowAll($store);
    }

    /**
     * Check if 'Allow All Products per Page' is enabled
     *
     * @param Mage_Core_Model_Store $store
     * @return bool
     */
    public function isAllowAll($store) {
        return (bool) Mage::getStoreConfigFlag(self::XML_PATH_ALLOW_ALL, $store);
    }

    /**
     * Check if canonical tag is used
     *
     * @param Mage_Core_Model_Store $store
     * @return bool
     */
    public function useCanonical($store)
    {
        return (bool) Mage::getStoreConfigFlag(
            self::XML_PATH_USE_CANONICAL, $store);
    }

    /**
     * Get robot setting
     *
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function getRobots($store) {
        return Mage::getStoreConfig(self::XML_PATH_ROBOTS_SETTING, $store);
    }

}
