<?php

namespace AHT\Blog\Controller\Adminhtml\Post;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \AHT\Blog\Controller\Adminhtml\Post
{
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            $model = $this->_objectManager->create('AHT\Blog\Model\Post')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This item no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $uploader = $this->_objectManager->create(
                    'Magento\MediaStorage\Model\File\Uploader',
                    ['fileId' => 'image']
                );
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'svg', 'JPG', 'JPEG', 'GIF', 'PNG', 'SVG']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setAllowCreateFolders(true);
                $uploader->setFilesDispersion(true);
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if ($uploader->checkAllowedExtension($ext)) {
                    $path = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('blog/images/');
                    $uploader->save($path);
                    $fileName = $uploader->getUploadedFileName();
                    if ($fileName) {
                        $data['image'] = 'blog/images' . $fileName;
                    }
                } else {
                    $this->messageManager->addError(__('Disallowed file type.'));
                    return $this->redirectToEdit($model, $data);
                }
            } else {
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image'] = '';
                } else {
                    unset($data['image']);
                }
            }
            $model->setData($data);
            try {
                $model->save();
          
                $this->messageManager->addSuccess(__('You saved the item.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
