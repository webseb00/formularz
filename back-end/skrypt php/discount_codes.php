<?php 

  require_once('headers.php');
  require_once('dbConfig.php');
  require_once('database.php');

  $conn = new Database($db_host, $db_user, $db_password, $db_name);
  $connection = $conn->createConnection();

  $data = json_decode(file_get_contents("php://input"), true);

  if(isset($data['discountCode'])) {
    $discountCode = filter_var($data['discountCode'], FILTER_SANITIZE_STRING);
    $discountCode = trim($discountCode);

    if(!empty($discountCode)) {
      $conn->getDiscountCodes($connection);
    }
  }

?>