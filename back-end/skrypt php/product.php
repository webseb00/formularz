<?php 

  class Product {
    public function __construct($productDetails) {
      $this->productName = $productDetails['productName'];
      $this->productQuantity = $productDetails['productQuantity'];
      $this->productPrice = $productDetails['productPrice'];
      $this->productSum = ($productDetails['productPrice'] * $productDetails['productQuantity']);
    }

    public function getProductName() {
      return $this->productName;
    }

    public function getProductQuantity() {
      return $this->productQuantity;
    }

    public function getProductPrice() {
      return $this->productPrice;
    }

    public function getProductSum() {
      return $this->productSum;
    }
  }

?>