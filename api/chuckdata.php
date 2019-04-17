<?php

if ($method = $_SERVER['REQUEST_METHOD'])
{

  $servername = "localhost";
  $username = "afknscientist";
  $password = "ThUnDoEx2748$";
  $dbname = "chuckquotes";

  class chuckquote
  {
    public $ID;
    public $ChuckQuote;
    public $EnteredBy;
    public $QuoteDate;

    public function __construct($ID, $ChuckQuote, $EnteredBy, $QuoteDate)
        {
            $this->ID = $ID;
            $this->ChuckQuote = $ChuckQuote;
            $this->EnteredBy = $EnteredBy;
            $this->QuoteDate = $QuoteDate;
        }

  }

  $conn = new mysqli($servername, $username, $password,$dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  //echo "Connected successfully";

  if($method == "GET")
  {

    $sql = "SELECT * FROM chuckquotes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {

      while($row = $result->fetch_assoc())
      {

        $crow = new chuckquote($row["ID"], $row["ChuckQuote"], $row["EnteredBy"], $row["QuoteDate"]);
        $Jsonarray[] = $crow;

      }

      echo json_encode($Jsonarray);

    }
    else
    {
      echo "0 results";
    }

    $conn->close();

  }

  if($method == "POST")
  {
    $data = file_get_contents('php://input');
    $aquote = json_decode($data,true);
    $phpdate = strtotime( $aquote["QuoteDate"] );
    $mysqldate = date( 'Y-m-d H:i:s', $phpdate );
    $ChuckQuote = "'".$aquote["ChuckQuote"]."'";
    $EnteredBy = "'".$aquote["EnteredBy"]."'";
    $QuoteDate = "'".$mysqldate."'";


    $sql = "INSERT INTO chuckquotes (ChuckQuote,EnteredBy,QuoteDate) VALUES ($ChuckQuote,$EnteredBy,$QuoteDate)";

    if ($conn->query($sql) === TRUE)
    {
      echo "New record created successfully";
      http_response_code(201);
    }
    else
    {
      echo "Error: " . $sql . "<br>" . $conn->error;
      http_response_code(500);
    }

    $conn->close();


  }

  if($method == "PUT")
  {

    $data = file_get_contents('php://input');
    $aquote = json_decode($data,true);
    $phpdate = strtotime( $aquote["QuoteDate"] );
    $mysqldate = date( 'Y-m-d H:i:s', $phpdate );
    $ChuckQuote = "'".$aquote["ChuckQuote"]."'";
    $EnteredBy = "'".$aquote["EnteredBy"]."'";
    $QuoteDate = "'".$mysqldate."'";
    $ID = "'".$aquote["ID"]."'";

    $sql = "UPDATE chuckquotes Set ChuckQuote = $ChuckQuote, EnteredBy = $EnteredBy, QuoteDate = $QuoteDate WHERE ID=$ID";
    echo $result = $conn->query($sql);

  }

  if($method == "DELETE")
  {

     $sql = "DELETE FROM chuckquotes WHERE ID=".$_GET["ID"];

     echo $result = $conn->query($sql);

  }

}
else
{

  http_response_code(500);

}


?>
