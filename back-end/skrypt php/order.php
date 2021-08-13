<?php 

  class Order {
    public function __construct($orderDetails) {
      $this->deliveryMethod = $orderDetails['deliveryMethod'];
      $this->deliveryCost = $orderDetails['deliveryCost'];
      $this->paymentMethod = $orderDetails['paymentMethod'];
    }

    public function getDeliveryMethod() {
      return $this->deliveryMethod;
    }

    public function getDeliveryCost() {
      return $this->deliveryCost;
    }

    public function getPaymentMethod() {
      return $this->paymentMethod;
    }
  }

?>