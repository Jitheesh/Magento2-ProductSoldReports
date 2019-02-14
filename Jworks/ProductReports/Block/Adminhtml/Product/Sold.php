<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Jworks\ProductReports\Block\Adminhtml\Product;

/**
 * Backend Report Sold Product Content Block
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Sold extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_blockGroup = 'Jworks_ProductReports';
    /**
     * @codingStandardsIgnoreEnd
     */

    /**
     * Initialize container block settings
     * @return void
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Jworks_ProductReports';
        $this->_controller = 'adminhtml_product_sold';
        $this->_headerText = __('Products sold Report');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
