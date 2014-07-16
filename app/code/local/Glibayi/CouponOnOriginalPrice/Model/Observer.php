<?php
 
/**
 * Nuestra clase debe nombrarse siguiendo la estructura de nuestro
 * Observer.php, empezando desde el namespace, separando los directorios
 * con guiones bajos.
 */
 
class Glibayi_CouponOnOriginalPrice_Model_Observer{
    /**
     * Magento pasa como primer parámetro de los eventos un Varien_Event_Observer
     */
     public function apply(Varien_Event_Observer $observer){

        $item = $observer['item'];
        $rule = $observer['rule'];
        $result = $observer['result'];
        //only for by_percent rules
        if($rule->getSimpleAction()=='by_percent') {
            $disPer = $rule->getDiscountAmount();
            $product = Mage::getModel('catalog/product')->load($item->getProductId());
            $actualPrice = $product->getPrice();
            $specialPrice = $product->getFinalPrice();
            $discountAmount = $item->getDiscountAmount();
            $baseDiscountAmount = $item->getBaseDiscountAmount();
            $ratdisc = ($actualPrice * $disPer) / 100;
            $targetPrice = $actualPrice - $ratdisc;
            if($actualPrice > $specialPrice){

                if($targetPrice < $specialPrice){
                    $discountAmount += $specialPrice - $targetPrice;
                    $baseDiscountAmount += $specialPrice - $targetPrice;
                }

                $result->setDiscountAmount($discountAmount);
                $result->setBaseDiscountAmount($baseDiscountAmount);
            }
        }
    }
} 
 
?>