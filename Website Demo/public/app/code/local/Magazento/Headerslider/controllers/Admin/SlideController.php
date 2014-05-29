<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Admin_SlideController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('magazento/fistslider')
                ->_addBreadcrumb(Mage::helper('headerslider')->__('headerslider'), Mage::helper('headerslider')->__('headerslider'))
                ->_addBreadcrumb(Mage::helper('headerslider')->__('headerslider Items'), Mage::helper('headerslider')->__('headerslider Items'))
        ;
        return $this;
    }

    /**
     * Related part
     */
    public function relatedAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('related.grid');
        $this->renderLayout();
    }

    public function relatedgridAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock('related.grid');
        $this->renderLayout();
    }

    public function indexAction() {
        $this->_initAction()
                ->_addContent($this->getLayout()->createBlock('headerslider/admin_slide'))
                ->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        if (Mage::helper('headerslider')->versionUseAdminTitle()) {
            $this->_title($this->__('headerslider'));
        }
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('slide_id');
        $model = Mage::getModel('headerslider/slide');
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('headerslider')->__('This slide no longer exists'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        // 4. Register model to use later in blocks
        Mage::register('headerslider_slide', $model);
        // 5. Build edit form
        $this->_initAction()
                ->_addBreadcrumb($id ? Mage::helper('headerslider')->__('Edit Slide') : Mage::helper('headerslider')->__('New Item'), $id ? Mage::helper('headerslider')->__('Edit Item') : Mage::helper('headerslider')->__('New Slide'))
                ->_addContent($this->getLayout()->createBlock('headerslider/admin_slide_edit')->setData('action', $this->getUrl('*/admin_slide/save')))
                ->_addLeft($this->getLayout()->createBlock('headerslider/admin_slide_edit_tabs'))
                ->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                    try {
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','bmp'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);
                        $path = Mage::helper('headerslider')->getImageFilePath(); 

                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $image_md5_name = md5($_FILES['image']['name']).'.'.$ext;
                        
                        $uploader -> save($path, $image_md5_name);
                        
                        $data['image_filename'] = $image_md5_name;
                        
                    } catch (Exception $e) {
                        echo $e;
                        exit();
                    }
            } else {       
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    $data['image_filename'] = '';
                }
            }

            // Assigned items goes down
            if (isset($data['related_prodlist'])) {
                $data['products'] = $data['related_prodlist'];
            }
            if (isset($data['in_products'])) $data['in_products'] = true;

//            var_dump($data);
//            exit();
            $model = Mage::getModel('headerslider/slide');            
            $model->setData($data);                
            
            try {
                // save the data
                $model->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('headerslider')->__('Slide was successfully saved'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('slide_id' => $model->getId()));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit', array('slide_id' => $this->getRequest()->getParam('slide_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('slide_id')) {
            $name = "";
            try {
                // init model and delete
                $model = Mage::getModel('headerslider/slide');
                $model->load($id);
                $name = $model->getName();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('headerslider')->__('Item was successfully deleted'));
                // go to grid
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('slide_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('headerslider')->__('Unable to find a item to delete'));
        // go to grid
        $this->_redirect('*/*/');
    }


    public function wysiwygAction() {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $content = $this->getLayout()->createBlock('adminhtml/catalog_helper_form_wysiwyg_content', '', array(
                    'editor_element_id' => $elementId
                ));
        $this->getResponse()->setBody($content->toHtml());
    }



    public function massStatusAction()
    {
        $itemIds = $this->getRequest()->getParam('massaction');
        if(!is_array($itemIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $model = Mage::getSingleton('headerslider/slide')
                        ->load($itemId)
                        ->setIs_active($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($itemIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
   public function massDeleteAction() {
        $itemIds = $this->getRequest()->getParam('massaction');
        if(!is_array($itemIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($itemIds as $itemId) {
                    $mass = Mage::getModel('headerslider/slide')->load($itemId);
                    $mass->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($itemIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    
    public function exportCsvAction()
    {
        $fileName   = 'items.csv';
        $content    = $this->getLayout()->createBlock('headerslider/admin_slide_grid')
            ->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'items.xml';
        $content    = $this->getLayout()->createBlock('headerslider/admin_slide_grid')
            ->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}