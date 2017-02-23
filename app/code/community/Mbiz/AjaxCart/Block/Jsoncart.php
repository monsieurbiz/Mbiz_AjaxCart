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

/**
 * Jsoncart Block
 * @package Mbiz_AjaxCart
 */
class Mbiz_AjaxCart_Block_Jsoncart extends Mage_Checkout_Block_Cart
{

// Monsieur Biz Tag NEW_CONST

// Monsieur Biz Tag NEW_VAR

    /**
     * Retrieve the cart in array
     * @return array
     */
    public function asArray()
    {
        $cart = [];
        
        $items = $this->getItems();
        $cart['items_count'] = count($items);
        $cart['items'] = [];
        foreach ($items as $item) {
            $cart['items'][$item->getId()] = $this->_getItem($item);
        }

        $cart['subtotal'] = $this->getQuote()->getStore()->formatPrice($this->getQuote()->getSubtotal(), false);

        return $cart;
    }

    /**
     * Returns the item in array
     * @return array
     */
    protected function _getItem(Mage_Sales_Model_Quote_Item $item)
    {
        $product = $item->getProduct();

        return [
            'id'            => $item->getId(),
            'name'          => $product->getName(),
            'url'           => $product->getProductUrl(),
            'qty'           => $item->getQty(),
            'configure_url' => $this->getUrl('checkout/cart/configure', array('id' => $item->getId())),
            'delete_url'    => $this->getUrl('checkout/cart/delete', array(
                'id' => $item->getId()
            )),
            'image_100x80'  => (string) Mage::helper('catalog/image')->init($product, 'small_image')->resize(100, 80),
            'image_30x30'   => (string) Mage::helper('catalog/image')->init($product, 'small_image')->resize(30),
            'row_total'     => $this->getQuote()->getStore()->formatPrice($item->getRowTotal(), false),
        ];
    }

// Monsieur Biz Tag NEW_METHOD

}