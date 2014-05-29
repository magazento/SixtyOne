<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Tabs_Helper_Data extends Mage_Core_Helper_Abstract {

    public function versionUseAdminTitle() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function versionUseWysiwig() {
        $info = explode('.', Mage::getVersion());
        if ($info[0] > 1) {
            return true;
        }
        if ($info[1] > 3) {
            return true;
        }
        return false;
    }

    public function numberArray($max,$text) {

        $items = array();
        for ($index = 1; $index <= $max; $index++) {
            $items[$index]=$text.' '.$index;
        }
        return $items;
    }
	
    public function getDummyContent()
    {
		$dummycontent = '
			<table border="0" width="100%">
<tbody>
<tr>
<td>
<h2 class="menu-content-header">Categories</h2>
<ul>
<li><a href="/mobile-phones/sim-free-phones/">Smartphones</a></li>
<li> <a href="/mobile-phones/pay-monthly-phones/">Phone Accessories</a> 
<ul>
<li><a href="/mobile-phones/payg-phones/">Cables and Connectivity</a></li>
<li><a href="/mobile-phones/sim-cards/">Cases and Covers</a></li>
<li><a href="/mobile-phones/mobile-broadband/">Cradles and Docking Stations</a></li>
<li><a href="/mobile-phones/iphone-accessories/">Power Adaptors and Batteries</a></li>
<li><a href="/mobile-phones/phone-accessories/">Screen Protectors and Cleaning</a> </li>
<li><a href="/mobile-phones/phone-accessories/">Styli and Pens</a> </li>
</ul>
</li>
</ul>
</td>
<td>
<h2 class="menu-content-header">Os</h2>
<ul>
<li><a href="/store/apple-iphone-4/">Apple iOS</a></li>
<li><a href="/blackberry-smartphones/">Blackberry</a></li>
<li><a href="/android-smartphones/">Google Android</a></li>
<li><a href="/windows-phone-7-smartphones/">Windows Phone 7</a></li>
</ul>
<h2 class="menu-content-header">Price</h2>
<ul class="price">
<li><a href="/mobile-phones/?filter=5|0|0-50">Under &pound;50</a></li>
<li><a href="/mobile-phones/?filter=5|0|50-100">&pound;50 - &pound;100</a></li>
<li><a href="/mobile-phones/?filter=5|0|100-200">&pound;100 - &pound;200</a></li>
<li><a href="/mobile-phones/?filter=5|0|200-300">&pound;200 - &pound;300</a></li>
<li><a href="/mobile-phones/?filter=5|0|300-400">&pound;300 - &pound;400</a></li>
<li><a href="/mobile-phones/?filter=5|0|400-500">&pound;400 - &pound;500</a></li>
<li><a href="/mobile-phones/?filter=5|0|600-0">&pound;600+</a></li>
</ul>
</td>
<td>
<h2 class="menu-content-header">Brands</h2>

</td>
<td>
<h2 class="menu-content-header">Popular</h2>
{{block type="tabs/popular" categoryid="18" count="4"  template="magazento_tabs/popular.phtml"}}</td>
</tr>
</tbody>
</table>
		';
		return $dummycontent;
    }
		
	
	
}
