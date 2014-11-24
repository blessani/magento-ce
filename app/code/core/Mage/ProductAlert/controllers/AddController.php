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
 * @category   Mage
 * @package    Mage_ProductAlert
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * ProductAlert controller
 *
 * @category   Mage
 * @package    Mage_ProductAlert
 */
class Mage_ProductAlert_AddController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
            if(!Mage::getSingleton('customer/session')->getBeforeUrl()) {
                Mage::getSingleton('customer/session')->setBeforeUrl($this->_getRefererUrl());
            }
        }
    }

    public function priceAction()
    {
        $session = Mage::getSingleton('catalog/session');
        /* @var $session Mage_Catalog_Model_Session */
        if (!($backUrl = base64_decode($this->getRequest()->getParam(Mage_Core_Controller_Front_Action::PARAM_NAME_BASE64_URL)))) {
            $this->_redirect('/');
            return ;
        }
        if (!$product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id'))) {
            /* @var $product Mage_Catalog_Model_Product */
            $session->addError(Mage::helper('productalert')->__('Not enough parameters'));
            $this->_redirectUrl($backUrl);
            return ;
        }

        try {
            $helper = Mage::helper('productalert');
            $model  = Mage::getModel('productalert/price')
                ->setCustomerId(Mage::getSingleton('customer/session')->getId())
                ->setProductId($product->getId())
                ->setPrice($product->getFinalPrice())
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            $model->save();

            $session->addSuccess(Mage::helper('productalert')->__('Alert subscription was saved successfully'));
        }
        catch (Exception $e) {
            $session->addException($e, Mage::helper('productalert')->__('Please try again later'));
        }
        $this->_redirectUrl($backUrl);
    }

    public function stockAction()
    {
        $session = Mage::getSingleton('catalog/session');
        /* @var $session Mage_Catalog_Model_Session */
        if (!($backUrl = base64_decode($this->getRequest()->getParam(Mage_Core_Controller_Front_Action::PARAM_NAME_BASE64_URL)))) {
            $this->_redirect('/');
            return ;
        }
        if (!$product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product_id'))) {
            /* @var $product Mage_Catalog_Model_Product */
            $session->addError(Mage::helper('productalert')->__('Not enough parameters'));
            $this->_redirectUrl($backUrl);
            return ;
        }

        try {
            $helper = Mage::helper('productalert');
            $model  = Mage::getModel('productalert/stock')
                ->setCustomerId(Mage::getSingleton('customer/session')->getId())
                ->setProductId($product->getId())
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            $model->save();

            $session->addSuccess(Mage::helper('productalert')->__('Alert subscription was saved successfully'));
        }
        catch (Exception $e) {
            $session->addException($e, Mage::helper('productalert')->__('Please try again later'));
        }
        $this->_redirectUrl($backUrl);
    }
}