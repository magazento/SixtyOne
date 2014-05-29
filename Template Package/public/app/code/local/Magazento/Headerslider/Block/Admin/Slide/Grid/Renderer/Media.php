<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Headerslider_Block_Admin_Slide_Grid_Renderer_Media extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    public function render(Varien_Object $row)
    {
        $html ='';
        if ($row->getData('image_filename')) {
            $html = '<img style="width:200px; border:1px solid #aaa;" src="'.Mage::helper('headerslider')->getImageFileHttp().DS.$row->getData('image_filename').'">';
        }
        return $html;
    }    
    
}
