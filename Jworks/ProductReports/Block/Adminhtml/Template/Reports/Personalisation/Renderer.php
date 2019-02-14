<?php
/**
 * @category Jworks
 * @package ProductReports
 * @author Jitheesh V O <jitheeshvo@gmail.com>
 * @copyright Copyright (c) 2018 Digital Boutique (http://www.digitalboutique.co.uk/)
 */

namespace Jworks\ProductReports\Block\Adminhtml\Template\Reports\Personalisation;

use Magento\Framework\Serialize\Serializer\Json;

/**
 * grid block action item renderer
 */
class Renderer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    /** @var Logger */
    private $logger;

    /** @var Json */
    private $serializer;

    /**
     * Renderer constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     * @param Json $serializer
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Psr\Log\LoggerInterface $logger,
        Json $serializer,
        array $data = []
    ) {
        $this->serializer = $serializer;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * @param string Serialized data
     * @return array
     */
    public function getUnserializedData(string $serializedData): array
    {
        try {
            $additionalInfo = $this->serializer->unserialize($serializedData);
        } catch (\InvalidArgumentException $argumentException) {
            $additionalInfo = [];
            $this->logger->logException($argumentException, [$serializedData, __METHOD__, __LINE__]);
        }

        return $additionalInfo;
    }

    /**
     * Render grid column
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $customData = $row->getData("custom_data");
        $optionText = '';
        if ($customData) {
            $additionalInfo = $this->getUnserializedData($row->getData("custom_data"));
            if (array_key_exists("options", $additionalInfo)) {

                $options = $additionalInfo["options"];


                foreach ($options as $key => $option) {

                    if ($key > 0) {
                        $optionText .= "\n";
                    }
                    $optionText .= isset($option['print_value']) ?
                        $option['label'].' : '.strtoupper($option['print_value']) : '';
                }
            }

        }

        return $optionText;
    }
}
