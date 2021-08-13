<?php 

  class Validation {

    // /////////////////////
    // CLIENT DATA VALIDATION
    // /////////////////////

    public $clientErrorData = array();
    public $clientValidData = array();

    public function validate_client_data($clientsData) {
      // sanitize data
      $firstName = filter_var($clientsData['firstName'], FILTER_SANITIZE_STRING);
      $lastName = filter_var($clientsData['lastName'], FILTER_SANITIZE_STRING);
      $email = filter_var($clientsData['email'], FILTER_SANITIZE_EMAIL);
      $country = filter_var($clientsData['state'], FILTER_SANITIZE_STRING);
      $street = filter_var($clientsData['street'], FILTER_SANITIZE_STRING);
      $city = filter_var($clientsData['city'], FILTER_SANITIZE_STRING);
      $zip_code = filter_var($clientsData['zipCode'], FILTER_SANITIZE_STRING);
      $phone = filter_var($clientsData['phoneNumber'], FILTER_SANITIZE_STRING);

      // validate sanitized data
      
      //-----------------------------//
      // 1. FIRST NAME
      if($firstName) {
        $firstName = trim($firstName);
        
        if(!empty($firstName)) {
          $this->clientValidData['firstName'] = $firstName;
        } else {
          $this->clientErrorData['firstName'] = 'Proszę podać imię.';
        }
      }
      //-----------------------------//
      // 2. LAST NAME
      if($lastName) {
        $lastName = trim($lastName);
        
        if(!empty($lastName)) {
          $this->clientValidData['lastName'] = $lastName;
        } else {
          $this->clientErrorData['lastName'] = 'Proszę podać nazwisko.';
        }
      }
      //-----------------------------//
      // 3. EMAIL
      if($email) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if($email) {
          $this->clientValidData['email'] = $email;
        } else {
          $this->clientErrorData['email'] = 'Proszę podać poprawny adres email.';
        } 
      }
      //-----------------------------//
      // 4. COUNTRY
      if($country) {
        $country = trim($country);

        if(!empty($country)) {
          $this->clientValidData['country'] = $country;
        } else {
          $this->clientErrorData['country'] = 'Proszę wybrać kraj zamieszkania.';
        }
      }
      //-----------------------------//
      // 5. STREET
      if($street) {
        $regExp = "/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ]+\s[0-9]{1,4}$/";

        if(preg_match($regExp, $street)) {
          $this->clientValidData['street'] = $street;
        } else {
          $this->clientErrorData['street'] = 'Proszę podać nazwę ulicy wraz z numerem domu/mieszkania.';
        }
      }
      //-----------------------------//
      // 6. CITY
      if($city) {
        $city = trim($city);

        if(!empty($city)) {
          $this->clientValidData['city'] = $city;
        } else {
          $this->clientErrorData['city'] = 'Proszę uzupełnić nazwę miejscowości zamieszkania.'; 
        }
      }
      //-----------------------------//
      // 7. ZIP CODE
      if($zip_code) {
        $regExp = "/^[0-9]{2}-[0-9]{3}$/";
        $zip_code = trim($zip_code);

        if(preg_match($regExp, $zip_code)) {
          $this->clientValidData['zipCode'] = $zip_code;
        } else {
          $this->clientErrorData['zipCode'] = 'Kod pocztowy miejscowości.'; 
        } 
      }
      //-----------------------------//
      // 8. PHONE
      if($phone) {
        $regExp = "/^[0-9]{9}$/";
        $phone = trim($phone);
        
        if(preg_match($regExp, $phone)) {
          $this->clientValidData['phoneNumber'] = $phone;
        } else {
          $this->clientErrorData['phoneNumber'] = 'Proszę podać numer telefonu.';
        }
      }
    }

    public function getClientValidData() {
      return $this->clientValidData;
    }

    // /////////////////////
    // ORDER DATA VALIDATION
    // /////////////////////

    public $orderErrorData = array();
    public $orderValidData = array();

    public function validate_order_data($orderDetails) {
      $deliveryMethod = filter_var($orderDetails['deliveryMethod'], FILTER_SANITIZE_STRING);
      $deliveryCost = filter_var($orderDetails['deliveryCost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $paymentMethod = filter_var($orderDetails['paymentMethod'], FILTER_SANITIZE_STRING);

      //-----------------------------//
      // 1. DELIVERY METHOD
      if($deliveryMethod) {
        $deliveryMethod = trim($deliveryMethod);

        if(!empty($deliveryMethod)) {
          $this->orderValidData['deliveryMethod'] = $deliveryMethod;
        } else {
          $this->orderErrorData['deliveryMethod'] = 'Proszę określić sposób dostawy zamówienia.';
        }
      }
      //-----------------------------//
      // 2. DELIVERY COST
      if($deliveryCost) {
        $deliveryCost = filter_var($deliveryCost, FILTER_VALIDATE_FLOAT);
        
        if(!empty($deliveryCost)) {
          $this->orderValidData['deliveryCost'] = $deliveryCost;
        }
      }
      //-----------------------------//
      // 3. PAYMENT METHOD
      if($paymentMethod) {
        $paymentMethod = trim($paymentMethod);

        if(!empty($paymentMethod)) {
          $this->orderValidData['paymentMethod'] = $paymentMethod;
        } else {
          $this->orderErrorData['paymentMethod'] = 'Proszę wybrać methodę płatności';
        }
      }
    }

    public function getOrderValidData() {
      return $this->orderValidData;
    }

    // /////////////////////
    // ORDER DATA VALIDATION
    // /////////////////////

    public $productErrorData = array();
    public $productValidData = array();

    public function validate_product_data($productDetails) {
      $productName = filter_var($productDetails['productName'], FILTER_SANITIZE_STRING);
      $productQuantity = filter_var($productDetails['productQuantity'], FILTER_SANITIZE_NUMBER_INT);
      $productPrice = filter_var($productDetails['productPrice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);


      //-----------------------------//
      // 1. PRODUCT NAME
      if($productName) {
        $productName = trim($productName);

        if(!empty($productName)) {
          $this->productValidData['productName'] = $productName;
        } 
      }
      //-----------------------------//
      // 2. PRODUCT QUANTITY
      if($productQuantity) {
        $productQuantity = filter_var($productQuantity, FILTER_VALIDATE_INT, array(
          "options" => array(
            "min_range" => 1, 
            "max_range" => 20
            )
        ));

        if(!empty($productQuantity)) {
          $this->productValidData['productQuantity'] = $productQuantity;
        } 
      }
      //-----------------------------//
      // 3. PRODUCT PRICE
      if($productPrice) {
        $productPrice = filter_var($productPrice, FILTER_VALIDATE_FLOAT);

        if(!empty($productPrice)) {
          $this->productValidData['productPrice'] = $productPrice;
        }
      }
    }

    public function getProductValidData() {
      return $this->productValidData;
    }

    public function checkDataValidity($name) {
      if($name === 'client') {
        if(count($this->clientErrorData) !== 0) { return false; } 
        return true;
      }

      if($name === 'order') {
        if(count($this->orderErrorData) !== 0) { return false; } 
        return true;
      }

      if($name === 'product') {
        if(count($this->productErrorData) !== 0) { return false; } 
        return true;
      }
    }

  }

?>