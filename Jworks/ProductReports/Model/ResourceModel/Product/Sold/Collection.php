<?php

namespace Jworks\ProductReports\Model\ResourceModel\Product\Sold;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Collection extends \Magento\Reports\Model\ResourceModel\Order\Collection
{
    /**
     * @param $from
     * @param $to
     * @param string $sku
     * @return $this
     */
    public function setFilters($from, $to, $sku = '')
    {
        $this->_reset()->addAttributeToSelect(
            '*'
        )->addOrderedQty(
            $from,
            $to,
            $sku
        );

        return $this;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $sku
     * @return $this
     */
    public function addOrderedQty($from = '', $to = '', $sku = '')
    {
        $connection = $this->getConnection();
        $orderTableAliasName = $connection->quoteIdentifier('order');

        $orderJoinCondition = [
            $orderTableAliasName.'.entity_id = order_items.order_id',
            $connection->quoteInto("{$orderTableAliasName}.state <> ?", \Magento\Sales\Model\Order::STATE_CANCELED),
        ];

        $addressJoinCondition = [
            $orderTableAliasName.'.entity_id = address.parent_id',
            $connection->quoteInto("address.address_type = ?", 'shipping'),
        ];


        if ($from != '' && $to != '') {
            $fieldName = $orderTableAliasName.'.created_at';
            $orderJoinCondition[] = $this->prepareBetweenSql($fieldName, $from, $to);
        }

        $this->getSelect()->reset()->from(
            ['order' => $this->getTable('sales_order')],
            ['customer_email', 'order_id' => 'increment_id', 'created_at' => 'created_at', 'status' => 'status']
        )->joinLeft(
            ['order_items' => $this->getTable('sales_order_item')],
            implode(' AND ', $orderJoinCondition),
            [
                'ordered_qty' => 'order_items.qty_ordered',
                'sku' => 'sku',
                'order_items_name' => 'order_items.name',
                'custom_data' => 'product_options',
            ]
        )->joinLeft(
            ['address' => $this->getTable('sales_order_address')],
            implode(' AND ', $addressJoinCondition),
            [
                'name' => "CONCAT(firstname,' ',lastname)",
                'street',
                'city',
                'region',
                'postcode',
                'country_id',
                'telephone',
                'company',
            ]
        )->where(
            'order_items.parent_item_id IS NULL'
        )->order('order_id', self::SORT_ORDER_DESC);


        if ($sku != '') {
            $this->getSelect()->where(
                'sku = ?',
                trim($sku)
            );
        }

        return $this;
    }

    /**
     * Set store filter to collection
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds)
    {
        if ($storeIds) {
            $this->getSelect()->where('order_items.store_id IN (?)', (array)$storeIds);
        }

        return $this;
    }


    /**
     * Prepare between sql
     * @param string $fieldName Field name with table suffix ('created_at' or 'main_table.created_at')
     * @param string $from
     * @param string $to
     * @return string Formatted sql string
     */
    protected function prepareBetweenSql($fieldName, $from, $to)
    {
        return sprintf(
            '(%s BETWEEN %s AND %s)',
            $fieldName,
            $this->getConnection()->quote($from),
            $this->getConnection()->quote($to)
        );
    }
}
