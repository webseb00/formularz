<?php 

  class Database {

    public function __construct($db_host, $db_user, $db_password, $db_name) {
      $this->db_host = $db_host;
      $this->db_user = $db_user;
      $this->db_password = $db_password;
      $this->db_name = $db_name;
    }

    public function createConnection() {
      $connection = @new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

      if($connection->connect_error) {
        die('Connection failed');
      } 

      return $connection;
    }

    public function closeConnection($conn) {
      $conn->close();
    }

    public function addClientDetails($clientData, $conn) {
      $firstname = $clientData->firstname;
      $lastname = $clientData->lastname;
      $email = $clientData->email;
      $country = $clientData->country;
      $street = $clientData->street;
      $city = $clientData->city;
      $zip_code = $clientData->zip_code;
      $phone = $clientData->phone;

      $sql_client_query = "INSERT INTO Clients (Imie, Nazwisko, Email, Kraj, Miejscowosc, Ulica, Kod_pocztowy, Telefon)
      VALUES ('$firstname', '$lastname','$email', '$country', '$city', '$street','$zip_code', '$phone')";

      if(!$conn->query($sql_client_query)) {
        echo $conn->error;
      }
    }

    public function addOrderDetails($orderDetails, $conn) {
      $deliveryMethod = $orderDetails->deliveryMethod;
      $deliveryCost = $orderDetails->deliveryCost;
      $paymentMethod = $orderDetails->paymentMethod;
      
      $sql_order_query = "INSERT INTO Orders (Dostawa, Platnosc, Koszt)
      VALUES ('$deliveryMethod', '$paymentMethod', '$deliveryCost')";

      if(!$conn->query($sql_order_query)) {
        echo $conn->error;
      }
    }

    public function addProductDetails($productDetails, $conn) {
      $productName = $productDetails->productName;
      $productQauntity = $productDetails->productQuantity;
      $productPrice = $productDetails->productPrice;
      $productSum = $productDetails->productSum;

      $sql_product_query = "INSERT INTO Products (Nazwa, Ilosc, Cena_jednostkowa, Cena_calkowita)
      VALUES ('$productName', '$productQauntity', '$productPrice', '$productSum')";

      if(!$conn->query($sql_product_query)) {
        echo $conn->error;
      }
    }

    public function getOrderID($connection) {
      // fetch last id from the database and send to the server as a order number
      $sql_query = 'SELECT `order_id` FROM orders ORDER BY `order_id` DESC LIMIT 1';
      $result = $connection->query($sql_query);
      $row = $result->fetch_assoc();

      echo $row['order_id'];
    }

    public function getDiscountCodes($connection) {

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

  }

?>