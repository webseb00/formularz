<?php 

  class Client {
    public function __construct($clientData) {
      $this->firstname = $clientData['firstName'];
      $this->lastname = $clientData['lastName'];
      $this->email = $clientData['email'];
      $this->country = $clientData['country'];
      $this->street = $clientData['street'];
      $this->city = $clientData['city'];
      $this->zip_code = $clientData['zipCode'];
      $this->phone = $clientData['phoneNumber'];
    }

    public function getFirstname() {
      return $this->firstname;
    }

    public function getLastname() {
      return $this->lastname;
    }

    public function getEmail() {
      return $this->email;
    }

    public function getCountry() {
      return $this->country;
    }

    public function getStreet() {
      return $this->street;
    }

    public function getCity() {
      return $this->city;
    }

    public function getZipCode() {
      return $this->zip_code;
    }

    public function getPhoneNumber() {
      return $this->phone;
    }
  }

?>