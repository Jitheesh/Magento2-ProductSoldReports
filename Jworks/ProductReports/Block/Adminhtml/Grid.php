<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Jworks\ProductReports\Block\Adminhtml;

/**
 * Backend report grid block
 * @api
 * @author     Magento Core Team <core@magentocommerce.com>
 * @since 100.0.2
 */
class Grid extends \Magento\Reports\Block\Adminhtml\Grid
{


    /**
     * Default filters values
     * @var array
     * @codingStandardsIgnoreStart
     */
    protected $_defaultFilters = ['report_from' => '', 'report_to' => '', 'report_period' => 'day', 'sku' => ''];
    /**
     * @var string
     */
    protected $_defaultDir = 'desc';
    /**
     * @codingStandardsIgnoreEnd
     */

    /**
     * Apply sorting and filtering to collection
     * @return $this
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _prepareCollection()
    {
        $filter = $this->getParam($this->getVarNameFilter(), null);

        if (null === $filter) {
            $filter = $this->_defaultFilter;
        }

        if (is_string($filter)) {
            $data = [];
            $filter = trim($filter, '%3D'); // hot fix for url bug
            $filter = base64_decode($filter);
            parse_str(urldecode($filter), $data);

            if (!isset($data['report_from'])) {
                // getting all reports from 2001 year
                $date = (new \DateTime())->setTimestamp(mktime(0, 0, 0, 1, 1, 2001));
                $data['report_from'] = $this->_localeDate->formatDateTime(
                    $date,
                    \IntlDateFormatter::SHORT,
                    \IntlDateFormatter::NONE
                );
            }

            if (!isset($data['report_to'])) {
                // getting all reports from 2001 year
                $date = new \DateTime();
                $data['report_to'] = $this->_localeDate->formatDateTime(
                    $date,
                    \IntlDateFormatter::SHORT,
                    \IntlDateFormatter::NONE
                );
            }

            $this->_setFilterValues($data);
        } elseif ($filter && is_array($filter)) {
            $this->_setFilterValues($filter);
        } elseif (0 !== sizeof($this->_defaultFilter)) {
            $this->_setFilterValues($this->_defaultFilter);
        }

        /** @var $collection \Magento\Reports\Model\ResourceModel\Report\Collection */
        $collection = $this->getCollection();
        if ($collection) {
            $collection->setPeriod('year');
            $collection->setFilterSku($this->getFilter('sku'));

            if ($this->getFilter('report_from') && $this->getFilter('report_to')) {
                /**
                 * Validate from and to date
                 */
                try {
                    $from = $this->_localeDate->scopeDate(null, $this->getFilter('report_from'), false);
                    $to = $this->_localeDate->scopeDate(null, $this->getFilter('report_to'), false);

                    $collection->setInterval($from, $to);
                } catch (\Exception $e) {
                    $this->_errors[] = __('Invalid date specified');
                }
            }

            $collection->setStoreIds($this->_getAllowedStoreIds());

            if ($this->getSubReportSize() !== null) {
                $collection->setPageSize($this->getSubReportSize());
            }

            $this->_eventManager->dispatch(
                'adminhtml_widget_grid_filter_collection',
                ['collection' => $this->getCollection(), 'filter_values' => $this->_filterValues]
            );
        }

        return $this;
    }
}
