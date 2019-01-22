<?php

namespace AHT\Blog\Controller\Index;

class Add extends \Magento\Framework\App\Action\Action {

    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ){
        parent::__construct($context);
    }

    public function execute()
    {

        if ($_POST) {

            $name = $this->getRequest()->getParam('name');
            $status = $this->getRequest()->getParam('status');
            $urlkey = $this->getRequest()->getParam('urlkey');
            if($status != 1)
            {
                $status=0;
            }
            $content = $this->getRequest()->getParam('content');
            $create = $this->getRequest()->getParam('create_at');
            $update = $this->getRequest()->getParam('update_at');

            $part = $this->_objectManager->create('AHT\Blog\Model\Post');

            $part->setName($name);
            $part->setUrlKey($urlkey);
            $part->setStatus($status);
            $part->setContent($content);
            $part->setCreateAt($create);
            $part->setUpdateAt($update);
            $part->save();

            $this->messageManager->addSuccess(__('congratulation'));

            return $this->_redirect('blog/index/show');

        }
        return $this->_redirect('blog/index/show');
    }
    
}