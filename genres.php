<?php

ini_set('max_execution_time', '0');

$servername = "localhost";
$username = "root";
$password = "";
$db = "discussnetflix";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} 

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.themoviedb.org/3/genre/movie/list?api_key=dec365ad7fbd4450c8e10736d8133f81&language=en-US',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response);
    $api_results = $response->genres;

    $result_values = NULL;
    
    foreach($api_results as $result_dataR){

      if(isset($result_dataR->id)){
        $resultID = $result_dataR->id;
      }
      else{
          $resultID = NULL;
      }

      if(isset($result_dataR->name)){
        $genre_ID = $result_dataR->name;
      }
      else{
          $genre_ID = NULL;
      }

      if($resultID){
              $result_value = "('"
                                .$resultID."','"
                                .$genre_ID
                              ."'),";
      }
      else{
              $result_value = NULL;
      }
    
  };
      $result_value = rtrim($result_value, ",");
      $SQL = "INSERT INTO `genres`(
                                      `id`,
                                      `name`
                                      )
                                      VALUES
                                        $result_value
                                      "; 
 
    //echo $SQL; exit;
    mysqli_query($conn, $SQL);
    echo mysqli_affected_rows($conn). " ROWS INSERTED <br>";
    //echo count(array($result_value)). " ROWS INSERTED <br>";


?>