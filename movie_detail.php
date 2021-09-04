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


//To get movies information
for ($x = 1; $x <= 500; $x++) { 

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.themoviedb.org/3/movie/popular?api_key=dec365ad7fbd4450c8e10736d8133f81&language=en-US&page='."$x",
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
    $api_results = $response->results;

    $result_values = NULL;
    
    foreach($api_results as $result_dataR){
    
      $result_dataR->genre_ids = implode(",",$result_dataR->genre_ids);

      $result_overview       = mysqli_real_escape_string($conn, $result_dataR->overview);
      $result_original_title = mysqli_real_escape_string($conn, $result_dataR->original_title);
      $result_title          = mysqli_real_escape_string($conn, $result_dataR->title);

      if(isset($result_dataR->id)){
        $resultID = $result_dataR->id;
      }
      else{
          $resultID = NULL;
      }

      if(isset($result_dataR->genre_ids)){
        $genre_ID = $result_dataR->genre_ids;
      }
      else{
          $genre_ID = NULL;
      }

      if(isset($result_dataR->original_language)){
        $original_lang = $result_dataR->original_language;
      }
      else{
          $original_lang = NULL;
      }

      if(isset($result_dataR->original_title)){
        $original_title = $result_dataR->original_title;
      }
      else{
          $original_title = NULL;
      }

      if(isset($result_dataR->popularity)){
        $popularity = $result_dataR->popularity;
      }
      else{
          $popularity = NULL;
      }

      if(isset($result_dataR->poster_path)){
        $poster_path = $result_dataR->poster_path;
      }
      else{
        $poster_path = NULL;
      }

      if(isset($result_dataR->release_date)){
        $release_date = $result_dataR->release_date;
      }
      else{
        $release_date = NULL;
      }
      if(isset($result_dataR->title)){
        $title = $result_dataR->title;
      }
      else{
        $title = NULL;
      }
      if(isset($result_dataR->overview)){
        $overview = $result_dataR->overview;
      }
      else{
        $overview = NULL;
      }
      if(isset($result_dataR->vote_average)){
        $vote_average = $result_dataR->vote_average;
      }
      else{
        $vote_average = NULL;
      }
      if(isset($result_dataR->vote_count)){
        $vote_count = $result_dataR->vote_count;
      }
      else{
        $vote_count = NULL;
      }

      if($resultID){
              $result_value = "('"
                                .$resultID."','"
                                .$genre_ID."','"
                                .$original_lang."','"
                                .$original_title."','"
                                .$popularity."','"
                                .$poster_path."','"
                                .$release_date."','"
                                .$title."','"
                                .$overview."','"
                                .$vote_average."','"
                                .$vote_count
                              ."'),";
      }
      else{
              $result_value = NULL;
      }

    };

      $result_value = rtrim($result_value, ",");

      $SQL = "INSERT INTO `movie_info`(
                                      `id`,
                                      `genre_ids`,
                                      `original_language`,
                                      `original_title`,
                                      `popularity`,
                                      `poster_path`,
                                      `release_date`,
                                      `title`,
                                      `overview`,
                                      `vote_average`,
                                      `vote_count`
                                      )
                                      VALUES
                                        $result_value
                                      "; 
 
    //echo $SQL; exit;
    mysqli_query($conn, $SQL);
    echo mysqli_affected_rows($conn). " ROWS INSERTED <br>";
    //echo count(array($result_value))." ROWS INSERTED <br>";


    

  };


?>