<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */

class Magazento_Ajaxcart_IndexController extends Mage_Core_Controller_Front_Action
{

    const CONFIGURABLE_PRODUCT_IMAGE= 'checkout/cart/configurable_product_image';
    const USE_PARENT_IMAGE          = 'parent';

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function cartdeleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                Mage::getSingleton('checkout/cart')->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addError($this->__('Cannot remove item'));
            }
        }
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');

        $this->renderLayout();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function cartAction()
    {
        if ($this->getRequest()->getParam('cart')){
            if ($this->getRequest()->getParam('cart') == "delete"){
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    try {
                        Mage::getSingleton('checkout/cart')->removeItem($id)
                          ->save();
                    } catch (Exception $e) {
                        Mage::getSingleton('checkout/session')->addError($this->__('Cannot remove item'));
                    }
                }
            }
        }

        if ($this->getRequest()->getParam('product')) {
            $cart   = Mage::getSingleton('checkout/cart');
            $params = $this->getRequest()->getParams();
            $related = $this->getRequest()->getParam('related_product');

            $productId = (int) $this->getRequest()->getParam('product');


            if ($productId) {
                $product = Mage::getModel('catalog/product')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->load($productId);
                try {

                    if (!isset($params['qty'])) {
                        $params['qty'] = 1;
                    }

                    $cart->addProduct($product, $params);
                    if (!empty($related)) {
                        $cart->addProductsByIds(explode(',', $related));
                    }
                    $cart->save();
                    $this->_getSession()->setCartWasUpdated(true);


                    Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
                    Mage::getSingleton('checkout/session')->setCartInsertedItem($product->getId());

//                    $img = '';
                    Mage::dispatchEvent('checkout_cart_add_product_complete', array('product'=>$product, 'request'=>$this->getRequest()));

                    $prod_img = $product;
//                    if($product->isConfigurable() && isset($params['super_attribute'])){
//                        $attribute = $params['super_attribute'];
//                        if (Mage::getStoreConfig(self::CONFIGURABLE_PRODUCT_IMAGE) != self::USE_PARENT_IMAGE) {
//                            $prod_img_temp = Mage::getModel("catalog/product_type_configurable")->getProductByAttributes($attribute, $product);
//                            if ($prod_img_temp->getImage() != 'no_selection' && $prod_img_temp->getImage()){
//                                $prod_img = $prod_img_temp;
//                            }
//                        }
//                    }
                    $img = '<img src="'.Mage::helper('catalog/image')->init($prod_img, 'thumbnail')->resize(50,60).'" />';
                    $message = $this->__('%s was successfully added to your shopping cart.', $product->getName());

                    Mage::getSingleton('checkout/session')->addSuccess('<div class="ajaxcart-product-img">'.$img.'</div><div class="ajaxcart-product-message">'.$message.'</div>');
                }
                catch (Mage_Core_Exception $e) {
                    if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
                        Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
                    } else {
                        $messages = array_unique(explode("\n", $e->getMessage()));
                        foreach ($messages as $message) {
                            Mage::getSingleton('checkout/session')->addError($message);
                        }
                    }
                }
                catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addException($e, $this->__('Can not add item to shopping cart'));
                }

            }
        }
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');

        $this->renderLayout();
    }

    
    public function productcheckAction()
    {
        $params = $this->getRequest()->getParams();
        
        $productTypes = array(
            Mage_Catalog_Model_Product_Type::TYPE_GROUPED,
            Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
            Mage_Catalog_Model_Product_Type::TYPE_BUNDLE,
        );
        
        $storeId = Mage::app()->getStore()->getId();
        
        if($product_id = $params['product']){
            
            $product = Mage::getModel('catalog/product')->setStoreId($storeId)->load($product_id);
            if ($product->getId()){
                if($product->getHasOptions() || in_array($product->getTypeId(), $productTypes)){
                    //echo get product url
                    echo $product->getProductUrl();
                    exit();
                }
            }
        }
        echo 0;
        exit();
    }

    public function addtocartAction()
    {
        $this->indexAction();
    }



    public function preDispatch()
    {
        parent::preDispatch();
        $action = $this->getRequest()->getActionName();
    }


}