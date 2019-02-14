<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Report Reviews collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Jworks\ProductReports\Model\ResourceModel\Product\Sold\Collection;

class Initial extends \Magento\Reports\Model\ResourceModel\Report\Collection
{
    /**
     * Report sub-collection class name
     *
     * @var string
     */
    protected $reportCollection = 'Jworks\ProductReports\Model\ResourceModel\Product\Sold\Collection';


    protected $sku;

    /**
     * @param $sku
     */
    public function setFilterSku($sku)
    {

        $this->sku = $sku;
    }

    public function getFilterSku()
    {

        return $this->sku;
    }

    /**
     * Get report for some interval
     *
     * @param int $fromDate
     * @param int $toDate
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     * @codingStandardsIgnoreStart
     */
    protected function _getReport($fromDate, $toDate)
    {
        if ($this->reportCollection === null) {
            return [];
        }
        $reportResource = $this->_collectionFactory->create($this->reportCollection);
        $reportResource->setFilters($fromDate, $toDate, $this->getFilterSku())->setStoreIds($this->getStoreIds());
        return $reportResource;
    }
}
