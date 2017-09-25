<?php

// TODO: Store constants in separate file?
// MySQL connection constants
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "forum";

/* DATABASE CLASS */
class DB {
  // Database connection, static to avoid connecting more than once
  protected static $conn;

  /* Connect to DB */
  public function connect() {
    // Only connect if not already set
    if(!isset(self::$conn)) {
      self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    // Connection not successful, handle error
    if(self::$conn === false) {
      if(self::$conn->connect_error) die("Connection failed: " . self::$conn->connect_error); // Check connection
      return false;
    }
    // Connection successful, return
    return self::$conn;
  }

  /* Query the DB */
  public function query($query) {
    // Connect to DB
    $conn = $this->connect();
    $res = $conn->query($query); // Query string
    return $res; // Return result
  }

  /* Prepare the DB */
  public function prepare($query) {
    $conn =  $this->connect();
    $prep = $conn->prepare($query);
    return $prep;
  }

  /* SELECT from DB */
  public function select($query) {
    $res = $this->query($query);
    $rows = array();
    if($res === false) {
      return false; // Error
    }
    if($res->num_rows > 0) {
      while($row = $res->fetch_object()) {
        $rows[] = $row; // Store each row object in array
      }
    }
    return $rows;
  }

  /* INSERT to DB */
  public function insert($query) {
    $res = $this->query($query);
    if($res) return true;
    else return false;
  }

  /* UPDATE DB */
  public function update($query) {
    $res = $this->query($query);
    if($res) return true;
    else return false;
  }

  /* DELETE from DB */
  public function delete($query) {
    $res = $this->query($query);
    if($res) return true;
    else return false;
  }

  /* Return last error */
  public function error() {
    $conn = $this->connect();
    return $conn->error;
  }

  /* Quote and escape value for use in DB query */
  public function quote($value) {
    $conn = $this->connect();
    return "'" . $conn->real_escape_string(stripslashes($value)) . "'";
  }

  /* Get id of last inserted row */
  public function lastInsertId() {
    return self::$conn->insert_id;
  }

}

?>
