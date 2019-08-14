<?php
/**
 * Conntosol Module developed for general use
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.

 *
 * @category   Conntosol
 * @package    Productiveminds_Contactus
 * @copyright  Copyright (c) 2013 Conntosol Ltd (http://www.conntosol.com)
 * @license   www.conntosol.com/magento/license
 * @author    Sola Ajiboye <info@Conntosol.com>
 *
 */
class Productiveminds_Catalog_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
     * Check is module exists and enabled in global config.
	 * This is only needed for 1.4.0.1 or less, but might aswell have in for all
	 * See Mage_Core_Helper_Abstract for defn
	 * Enter description here ...
	 * @param $moduleName
	*/
	public function isModuleActive($moduleName = null,$enabledLocation=null) {
        if ($moduleName === null) {
            $moduleName = $this->_getModuleName();
        }
        if (!Mage::getConfig()->getNode('modules/' . $moduleName)) {
            return false;
        }
        $isActive = Mage::getConfig()->getNode('modules/' . $moduleName . '/active');
        $test = (string)$isActive;
        if (!$isActive || !in_array((string)$isActive, array('true', '1'))) {
            return false;
        }
        if ($enabledLocation === null) {
        	return true;
        }
        if (!Mage::getStoreConfig($enabledLocation)) {
        	return false;
        }
        return true;
    }
}
?>