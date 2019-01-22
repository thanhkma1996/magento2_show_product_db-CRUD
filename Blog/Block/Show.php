<?php
namespace AHT\Blog\Block;

use  Magento\Framework\View\Element\Template;

class Show extends \Magento\Framework\View\Element\Template
{
    private $_BlogCollection;


    public function __construct(
        Template\Context $context,
        \AHT\Blog\Model\ResourceModel\Post\CollectionFactory $BlogCollection,
       array $data=[])
    {
        parent::__construct($context, $data);
        $this->_BlogCollection = $BlogCollection;

    }

    public function getshow()
    {
        $collection = $this->_BlogCollection->create();
        return $collection;
    }
}