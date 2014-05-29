<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Sixtyone_Helper_Template extends Mage_Core_Helper_Abstract {

    function getInstallationContent() {
        $content = '
                    <strong>Pages:</strong>
                    <ul>
                        <li>home</li>
                        <li>sample</li>
                    </ul><br/>
                    <strong>Static blocks:</strong>
                    <ul>
                        <li>product_upsell</li>
                        <li>product_tabs_info1</li>
                        <li>product_tabs_bottom</li>
                        <li>product_tabs_sidebar</li>
                        <li>new_products</li>
                        <li>sale_products</li>
                        <li>no_products_category</li>
                        <li>no_products_cart</li>
                        <li>sale_divider</li>
                        <li>footer_divider</li>
                        <li>company_information</li>
                        <li>footer_one</li>
                        <li>footer_two</li>
                        <li>footer_three</li>
                        <li>recently_viewed_heading</li>
                        <li>recently_viewed_divider</li>
                        <li>cart_crosssell</li>
                        <li>cart_heading</li>
                    </ul>';
        return $content;
    }
    function getInstallationNote() {
        $content = 'Make sure that you have at least 5 products marked as new and 5 products with special price to display widgets on pages correctly.';
        return $content;
    }
}
