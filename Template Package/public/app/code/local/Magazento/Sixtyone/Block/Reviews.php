<?php
/**
 * 
 * @category  Magazento
 * @author    Ivan Proskuryakov http://www.magazento.com <volgodark@gmail.com>
 * @copyright Copyright (C)2013 Magazento
 *
 */
class Magazento_Sixtyone_Block_Reviews extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{

    protected function _construct() {
        parent::_construct();
    }

    public function getReviewsAmount () {
        return $this->getData('reviews_amount');
    }
    public function getMinimalRating () {
        return $this->getData('min_rating')*20;
    }

    function getReviews() {
        $review = Mage::getModel('review/review');
        $collection = $review
                ->getProductCollection()
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addStatusFilter( Mage_Review_Model_Review::STATUS_APPROVED )
                ->setDateOrder()
                ->addAttributeToSelect('*');
        $collection->getSelect()
                ->limit(100)
                ->order('rand()');
        $review->appendSummary($collection);
        
//        var_dump($collection->getSize());exit();
        $counter = 0;
        $review_array = array();
        foreach($collection as $_item) {
            if ($_item->getRatingSummary()->getData('rating_summary') >= $this->getMinimalRating()) {
                $review_array[] = $_item;
                $counter++;
            }
            if ($counter == $this->getReviewsAmount()) break;
        }

        
        return $review_array;
    }

}
