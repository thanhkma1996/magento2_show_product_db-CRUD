<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

class MassStatus extends \AHT\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $ids = $this->getRequest()->getPost('ids');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select item(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $model = $this->_objectManager->create('AHT\Blog\Model\Post')
                        ->load($id)
                        ->setStatus($this->getRequest()->getPost('status'))
                        ->save();
                }
                $this->messageManager->addSuccess(__('Total of %1 record(s) were successfully updated.', count($ids)));

            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}


