<?php 

  require_once('headers.php');
  require_once('dbConfig.php');
  require_once('database.php');
  require_once('client.php');
  require_once('order.php');
  require_once('product.php');
  require_once('validation.php');

  // set connection to the database
  $conn = new Database($db_host, $db_user, $db_password, $db_name);
  $connection = $conn->createConnection();
  
  $data = json_decode(file_get_contents("php://input"), true);

  if(isset($data['client']) && isset($data['orderDetails'])) {
    //-------------------------//
    // handle client details data
    $clientValidation = new Validation();
    $clientValidation->validate_client_data($data['client']);

    $checkClient = $clientValidation->checkDataValidity('client');
    
    //-------------------------//
    // handle order details data
    $orderDetails = array();
    
    $orderDetails['deliveryMethod'] = $data['orderDetails']['deliveryMethod'];
    $orderDetails['deliveryCost'] = $data['orderDetails']['deliveryCost'];
    $orderDetails['paymentMethod'] = $data['orderDetails']['paymentMethod'];

    $orderValidation = new Validation();
    $orderValidation->validate_order_data($orderDetails);

    $checkOrder = $clientValidation->checkDataValidity('order');

    //-------------------------//
    // handle product details data
    $productDetails = array();

    $productDetails['productName'] = $data['orderDetails']['productName'];
    $productDetails['productQuantity'] = $data['orderDetails']['productQuantity'];
    $productDetails['productPrice'] = $data['orderDetails']['productPrice'];

    $productValidation = new Validation();
    $productValidation->validate_product_data($productDetails);

    $checkProduct = $clientValidation->checkDataValidity('product');

    // if all data are valid, create instances and pass them to the database
    if($checkClient && $checkOrder && $checkProduct) {  
      /* /////////////////////// */
      // 1. Client data
      /* /////////////////////// */

      // get validated client data
      $validClientData = $clientValidation->getClientValidData();
      // create new Client instance based on validated client data
      $newClient = new Client($validClientData);
      // sanitazed and validated client data pass to the database and save   
      $conn->addClientDetails($newClient, $connection);

      /* /////////////////////// */
      // 2. Order data
      /* /////////////////////// */

      $orderValidData = $orderValidation->getOrderValidData();
      $newOrderDetails = new Order($orderValidData);
      $conn->addOrderDetails($newOrderDetails, $connection);

      /* /////////////////////// */
      // 3. Product data
      /* /////////////////////// */
      $productValidData = $productValidation->getProductValidData();
      $newProduct = new Product($productValidData);
      $conn->addProductDetails($newProduct, $connection);

      // send back ID of order
      $conn->getOrderID($connection);

    } else {
      // send error when form is not filled completely
      $response = array(
        "msg" => "Proszę uzupełnić wymagane dane",
        "error" => true
      );

      echo json_encode($response);
    }
  } 
  // close connection to the database
  $conn->closeConnection($connection);
?>
