<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * WebShopApps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    WebShopApps
 * @package     WebShopApps WsaLogger
 * @copyright   Copyright (c) 2013 Zowta Ltd (http://www.WebShopApps.com)
 *              Copyright, 2013, Zowta, LLC - US license
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Webshopapps_Wsalogger_Adminhtml_LogController extends Mage_Adminhtml_Controller_Action
{
	
	protected function _initAction() {
        $this->_title($this->__('System'))->_title($this->__('WebShopApps Logs'));
       
        $this->loadLayout()
            ->_setActiveMenu('system/log')
            ->_addBreadcrumb(Mage::helper('wsalogger')->__('WebShopApps Log'), Mage::helper('wsalogger')->__('WebShopApps Log'));
        return $this;
    }
	
	
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function markAsReadAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $session = Mage::getSingleton('adminhtml/session');
            $model = Mage::getModel('wsalogger/log')
                ->load($id);

            if (!$model->getId()) {
                $session->addError(Mage::helper('adminnotification')->__('Unable to proceed. Please, try again.'));
                $this->_redirect('*/*/');
                return ;
            }

            try {
                $model->setIsRead(1)
                    ->save();
                $session->addSuccess(Mage::helper('adminnotification')->__('The message has been marked as read.'));
            }
            catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('An error occurred while marking notification as read.'));
            }

            $this->_redirectReferer();
            return;
        }
        $this->_redirect('*/*/');
    }

    public function massMarkAsReadAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $ids = $this->getRequest()->getParam('notification');
        if (!is_array($ids)) {
            $session->addError(Mage::helper('adminnotification')->__('Please select messages.'));
        }
        else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('wsalogger/log')
                        ->load($id);
                    if ($model->getId()) {
                        $model->setIsRead(1)
                            ->save();
                    }
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('adminnotification')->__('Total of %d record(s) have been marked as read.', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('An error occurred while marking the messages as read.'));
            }
        }
        $this->_redirect('*/*/');
    }
    
    public function viewAction() {
		
		  $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('wsalogger_adminhtml/log_view'))
            ->renderLayout();
            
		
	}	
    

    public function removeAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $session = Mage::getSingleton('adminhtml/session');
            $model = Mage::getModel('wsalogger/log')
                ->load($id);

            if (!$model->getId()) {
                $this->_redirect('*/*/');
                return ;
            }

            try {
                $model->setIsRemove(1)
                    ->save();
                $session->addSuccess(Mage::helper('adminnotification')->__('The message has been removed.'));
            }
            catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('An error occurred while removing the message.'));
            }

            $this->_redirectReferer();
            return;
        }
        $this->_redirect('*/*/');
    }

    public function massRemoveAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $ids = $this->getRequest()->getParam('notification');
        if (!is_array($ids)) {
            $session->addError(Mage::helper('adminnotification')->__('Please select messages.'));
        }
        else {
            try {
                foreach ($ids as $id) {
                    $model = Mage::getModel('wsalogger/log')
                        ->load($id);
                    if ($model->getId()) {
                        $model->setIsRemove(1)
                            ->save();
                    }
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('adminnotification')->__('Total of %d record(s) have been removed.', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('An error occurred while removing messages.'));
            }
        }
        $this->_redirectReferer();
    }
    
     public function massDestroyAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        
            try {
               	$model = Mage::getModel('wsalogger/log')->truncate();
                $this->_getSession()->addSuccess(
                    Mage::helper('adminnotification')->__('All logs have been removed.')
                );
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, Mage::helper('adminnotification')->__('An error occurred while destroying messages.'));
            }
        $this->_redirectReferer();
    }
    

    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'markAsRead':
                $acl = 'system/adminnotification/mark_as_read';
                break;

            case 'massMarkAsRead':
                $acl = 'system/adminnotification/mark_as_read';
                break;

            case 'remove':
                $acl = 'system/adminnotification/remove';
                break;

            case 'massRemove':
                $acl = 'system/adminnotification/remove';
                break;

            default:
                $acl = 'system/adminnotification/show_list';
        }
        return Mage::getSingleton('admin/session')->isAllowed($acl);
    }
}
