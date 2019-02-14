<?php
/**
 * @category Jworks
 * @package ProductReports
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 * @copyright Copyright (c) 2018 Digital Boutique (http://www.digitalboutique.co.uk/)
 */

namespace Jworks\ProductReports\Controller\Adminhtml\Report\Product;

class Details extends \Magento\Reports\Controller\Adminhtml\Report\Sales
{
    /**
     * Authorization level of a basic admin session
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Jworks_ProductReports::details';

    /**
     * Sold Products Report Action
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Jworks_ProductReports::report_products_details'
        )->_addBreadcrumb(
            __('Order by product Report'),
            __('Order by product Report')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Order by product Report'));
        $this->_view->renderLayout();
    }
}
