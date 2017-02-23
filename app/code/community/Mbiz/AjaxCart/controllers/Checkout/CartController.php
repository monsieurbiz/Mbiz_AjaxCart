<?php
/**
 * This file is part of Mbiz_AjaxCart for Magento.
 *
 * @license All rights reserved
 * @author Jacques Bodin-Hullin <@jacquesbh> <j.bodinhullin@monsieurbiz.com>
 * @category Mbiz
 * @package Mbiz_AjaxCart
 * @copyright Copyright (c) 2014 Monsieur Biz (http://monsieurbiz.com/)
 */

require_once 'Mage/Checkout/controllers/CartController.php';

/**
 * Checkout_Cart Controller
 * @package Mbiz_AjaxCart
 */
class Mbiz_AjaxCart_Checkout_CartController extends Mage_Checkout_CartController
{

// Monsieur Biz Tag NEW_CONST

    protected $_products = [];

// Monsieur Biz Tag NEW_VAR

    /**
     * Add product to shopping cart action
     *
     * @return Mage_Core_Controller_Varien_Action
     * @throws Exception
     */
    public function addAction()
    {
        // Is ajax call?
        if ($this->_isEnabled() && $this->getRequest()->isAjax()) {
            // No display
            $this->setFlag('', 'mbiz_ajaxcart', true);
            $this->setFlag('', 'no-dispatch', true);
        }

        return parent::addAction();
    }

    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        // Is ajax call?
        if ($this->_isEnabled() && $this->getRequest()->isAjax()) {
            // No display
            $this->setFlag('', 'mbiz_ajaxcart', true);
            $this->setFlag('', 'no-dispatch', true);
        }

        return parent::deleteAction();
    }

    /**
     * Update shopping cart data action
     */
    public function updatePostAction()
    {
        // Is ajax call?
        if ($this->_isEnabled() && $this->getRequest()->isAjax()) {
            // No display
            $this->setFlag('', 'mbiz_ajaxcart', true);
            $this->setFlag('', 'no-dispatch', true);
        }

        return parent::updatePostAction();
    }

    /**
     * Set back redirect url to response
     *
     * @return Mage_Checkout_CartController
     * @throws Mage_Exception
     */
    protected function _goBack()
    {
        if ($this->_isEnabled() && $this->getFlag('', 'mbiz_ajaxcart')) {
            $this->_sendResponse();
        }

        return parent::_goBack();
    }

    /**
     * Set referer url for redirect in response
     *
     * @param   string $defaultUrl
     * @return  Mage_Core_Controller_Varien_Action
     */
    protected function _redirectReferer($defaultUrl = null)
    {
        if ($this->_isEnabled() && $this->getFlag('', 'mbiz_ajaxcart')) {
            $this->_sendResponse();
        }

        return parent::_redirectReferer($defaultUrl);
    }

    /**
     * Send the ajax response
     */
    protected function _sendResponse()
    {
        // The response
        $product = $this->_initProduct();
        $item = $this->_getQuote()->getItemByProduct($product);
        $res = [
            'success' => true,
            'error'   => false,
            'message' => null,
            'qty'      => $item->getQty(),
            'subtotal' => $this->_getQuote()->getStore()->formatPrice($item->getRowTotal(), false),
            'action'  => $this->getRequest()->getActionName(),
            'cart'    => $this->_getJsonCart(),
        ];

        // Id ?
        if ($id = $this->getRequest()->getParam('id')) {
            $res['id'] = $id;
        }

        // The session, and get all messages
        $session = $this->_getSession();

        // Last added message
        $messages = $session->getMessages(true);
        if ($messages->count()) {
            $message = $messages->getLastAddedMessage();
            if ($message instanceof Mage_Core_Model_Message_Error) {
                $res['success'] = false;
                $res['error']   = true;
            }
            $res['message'] = $message->getText();
        }

        // Send the response (JSONP)
        $callback = $this->getRequest()->getParam('callback');

        //$this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        //$this->getResponse()->setHeader('Access-Control-Allow-Methods', 'POST');
        //$this->getResponse()->setHeader('Access-Control-Max-Age', '1000');
        $this->getResponse()->setHeader('Content-Type', 'application/json');

        if ($callback) {
            $this->getResponse()->setBody($callback . '(' . Mage::helper('core')->jsonEncode($res) . ')');
        } else {
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($res));
        }

        $this->getResponse()->sendResponse();
        exit;
    }

    /**
     * Retrieve the json cart
     * return string
     */
    protected function _getJsonCart()
    {
        return $this->getLayout()->createBlock('mbiz_ajaxcart/jsoncart')->asArray();
    }

    /**
     * Is the module enabled in configuration?
     * @return bool
     */
    protected function _isEnabled()
    {
        return Mage::getSingleton('mbiz_ajaxcart/config')->isEnabled();
    }

    /**
     * @inheritDoc
     */
    protected function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            if (isset($this->_products[$productId])) {
                return $this->_products[$productId];
            } else {
                $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
                if ($product->getId()) {
                    $this->_products[$productId] = $product;
                    return $product;
                }
            }
        }
        return false;
    }

// Monsieur Biz Tag NEW_METHOD

}