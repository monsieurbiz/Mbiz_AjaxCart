<?php
/**
 * This file is part of Mbiz_AjaxCart for Magento.
 *
 * @license All rights reserved
 * @author Jacques Bodin-Hullin <j.bodinhullin@monsieurbiz.com> <@jacquesbh>
 * @category Mbiz
 * @package Mbiz_AjaxCart
 * @copyright Copyright (c) 2016 Monsieur Biz (http://monsieurbiz.com)
 */

/**
 * Config Model
 * @package Mbiz_AjaxCart
 */
class Mbiz_AjaxCart_Model_Config
{

// Monsieur Biz Tag NEW_CONST

// Monsieur Biz Tag NEW_VAR

    /**
     * Is the module enabled?
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('mbiz_ajaxcart/enabled');
    }

// Monsieur Biz Tag NEW_METHOD

}