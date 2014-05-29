<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Tabs_Block_Category extends Mage_Core_Block_Template {

        public function __construct()
        {
            parent::__construct();
            $this->setTemplate('magazento_tabs/category.phtml');
        }    
        
        
        public function drawNestedMenus($children, $level=1,$morehref ='') {
            $html = '<ul>';
            $i=0;
            foreach ($children as $child) {

                    $_category = Mage::getModel("catalog/category")->load($child);

                    $html .= '<li class="level' . $level . '">';
                    $html .= '<a class="category-item-icon " href="' . $_category->getUrl() . '"><span class="level' . $level . '">' . $this->htmlEscape($_category->getName()) . '</span></a>';
                    $activeChildren = $_category->getChildren();    
                    if ($activeChildren) {
                        $activeChildren = explode(",",$activeChildren);    
//                        var_dump($activeChildren);
                        $html .= $this->drawNestedMenus($activeChildren, $level + 1,$_category->getUrl() );
                    }
                    $i++;
                    $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        }

    
}