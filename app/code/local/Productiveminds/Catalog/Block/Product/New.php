<?php
/**
 *  A Magento module by ProductiveMinds
 *
 * NOTICE OF LICENSE
 *
 * This code is the work and copyright of Productive Minds Ltd, A UK registered company.
 * The copyright owner prohibit any fom of distribution of this code
 *
 * DISCLAIMER
 *
 * You are strongly advised to backup ALL your server files and database before installing and/or configuring
 * this Magento module. ProductiveMinds will not take any form of responsibility for any adverse effects that
 * may be cause directly or indirectly by using this software. As a usual practice with Software deployment,
 * the copyright owner recommended that you first install this software on a test server verify its appropriateness
 * before finally deploying it to a live server.
 *
 * @category   	Productiveminds
 * @package    	Productiveminds_Reflexology002
 * @copyright   Copyright (c) 2010 - 2015 Productive Minds Ltd (http://www.productiveminds.com)
 * @license    	http://www.productiveminds.com/license/license.txt
 * @author     	ProductiveMinds <info@productiveminds.com>
 */

class Productiveminds_Catalog_Block_Product_New extends Mage_Catalog_Block_Product_New
{
	
	protected $_cache_lifetime = null;
	
	const DEFAULT_CACHE_LIFETIME = 86400;
	
	/**
	 * Initialize block's cache
	 */
	protected function _construct()
	{
		if ($this->hasData('template')) {
            $this->setTemplate($this->getData('template'));
        }

		$this->addColumnCountLayoutDepend('empty', 6)
		->addColumnCountLayoutDepend('one_column', 5)
		->addColumnCountLayoutDepend('two_columns_left', 4)
		->addColumnCountLayoutDepend('two_columns_right', 4)
		->addColumnCountLayoutDepend('three_columns', 3);
	
		// Added by Sola - Cache lifetime is reduced for hot new products to enable refresh of currency change 
        $this->addData(array(
            //'cache_lifetime'    => 86400,
        	'cache_lifetime'    => 0,
            'cache_tags'        => array(Mage_Catalog_Model_Product::CACHE_TAG),
        ));
	}

    /**
     * Prepare collection with new products and applied page limits.
     *
     * return Mage_Catalog_Block_Product_New
     */
    protected function _beforeToHtml()
    {
        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('news_from_date', array('or'=> array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or'=> array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is'=>new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is'=>new Zend_Db_Expr('not null'))
                    )
              )
            ->addAttributeToSort('updated_at', 'desc')
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1)
        ;
        $this->setProductCollection($collection);
    }
    
    
    /**
     * Set how long cache should be persisted
     *
     * @param $count
     * @return Productiveminds_Catalog_Block_Product_New
     */
    public function setCacheLifetime($lifetime)
    {
    	$this->_cache_lifetime = $lifetime;
    	return $this;
    }
    
    /**
     * Get how long cache should be persisted
     *
     * @return int
     */
    public function getCacheLifetime()
    {
    	if (null === $this->_cache_lifetime) {
    		$this->_cache_lifetime = self::DEFAULT_CACHE_LIFETIME;
    	}
    	return $this->_cache_lifetime;
    }

}
