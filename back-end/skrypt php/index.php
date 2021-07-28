<?php 

  session_start();

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: Content-Type, origin");
  header("Access-Control-Allow-Credentials: true");
  header("Access-Control-Max-Age: 1000");
  header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
  header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

  require_once('dbConfig.php');

  // make connection to the database
  $connection = @new mysqli($db_host, $db_user, $db_password, $db_name);

  if ($connection->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // get raw data from the front-end and return as an array
  $data = json_decode(file_get_contents("php://input"), true);

  // SCRIPT FOR HANDLING DISCOUNT CODE
  if(isset($data['discountCode'])) {
    function compareCodes() {
      global $connection;

      $sql = "SELECT * FROM `discount-codes`";
      $result = $connection->query($sql);
      $rowsLength = $result->num_rows;

      $array_codes = array(); 

      if($rowsLength > 0) {
        while($row = $result->fetch_assoc()) {
          array_push($array_codes, $row);
        }
      }

      $obj = json_encode($array_codes);
      
      echo $obj;   
    }
    compareCodes();
  } 

  // SCRIPT FOR HANDLING USER ORDER
  
  if(isset($data['client']) && isset($data['orderDetails'])) {
    $client = $data['client'];
    $details = $data['orderDetails'];

    function createClientsTable() {
      global $connection, $client;
      // get values and escape special characters for SQL query
      $firstname = mysqli_real_escape_string($connection, $client['firstName']);
      $lastname = mysqli_real_escape_string($connection, $client['lastName']);
      $email = mysqli_real_escape_string($connection, $client['email']);
      $country = mysqli_real_escape_string($connection, $client['state']);
      $street = mysqli_real_escape_string($connection, $client['street']);
      $city = mysqli_real_escape_string($connection, $client['city']);
      $zipcode = mysqli_real_escape_string($connection, $client['zipCode']);
      $phone = mysqli_real_escape_string($connection, $client['phoneNumber']);
  
      $table_clients_sql = "CREATE TABLE Clients (
        client_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(30) NOT NULL,
        country VARCHAR(30) NOT NULL,
        city VARCHAR(30) NOT NULL,
        street VARCHAR(30) NOT NULL,
        zipCode VARCHAR(7) NOT NULL,
        phone INT(9) NOT NULL
      )";
    
      $connection->query($table_clients_sql);
  
      // insert clients data into table clients
      $client_sql = "INSERT INTO Clients (firstname, lastname, email, country, city, street, zipCode, phone)
      VALUES ('$firstname', '$lastname','$email', '$country', '$city', '$street','$zipcode', '$phone')";
  
      $connection->query($client_sql);
    }
    // call createClientsTable for creating table and inserting users data
    createClientsTable();
  
    function createOrderDetailsTable() {
      global $connection, $details;
  
      $methodName = mysqli_real_escape_string($connection, $details['deliveryMethod']);
      $cost = mysqli_real_escape_string($connection, $details['deliveryCost']);
      $payment = mysqli_real_escape_string($connection, $details['paymentMethod']);
      $productName = mysqli_real_escape_string($connection, $details['productName']);
      $productPrice = mysqli_real_escape_string($connection, $details['productPrice']);
      $productQuantity = mysqli_real_escape_string($connection, $details['productQuantity']);
  
      $table_order_sql = "CREATE TABLE details (
        order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        method VARCHAR(30) NOT NULL,
        payment VARCHAR(30) NOT NULL,
        cost INT(10) NOT NULL,
        productName VARCHAR(30) NOT NULL,
        quantity VARCHAR(30) NOT NULL,
        price INT(10) NOT NULL
      )";
  
      $connection->query($table_order_sql);
  
      // insert order data details into table
      $order_sql = "INSERT INTO details (method, payment, cost, productName, quantity, price)
      VALUES ('$methodName', '$payment', '$cost', '$productName', '$productQuantity', '$productPrice')";
  
      $connection->query($order_sql);
    }
  
    createOrderDetailsTable();
  
    function sendResponse() {
      global $connection;
  
      header("Content-type: application/json; charset=utf-8");
      // fetch last id from the database and send to the server as a order number
      $sql = 'SELECT `order_id` FROM details ORDER BY `order_id` DESC LIMIT 1';
      $result = $connection->query($sql);
      $row = $result->fetch_assoc();
  
      echo $row['order_id'];
    }
    sendResponse();
	} else {
    exit();
  }

  $connection->close();

?>