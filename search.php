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


for ($x = 1; $x <= 85; $x++) {

  $curl = curl_init();

  curl_setopt_array($curl, [
    CURLOPT_URL => "https://streaming-availability.p.rapidapi.com/search/basic?country=us&service=netflix&type=movie&page="."$x",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
      "x-rapidapi-host: streaming-availability.p.rapidapi.com",
      "x-rapidapi-key: 384ab96ab8msh38974f8e6d2b474p11e3a8jsn82a42196c1ae"
    ],
  ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    //echo $response;exit;

    $response = json_decode($response);
    $api_results = $response->results;

    $result_values = NULL;
    
    foreach($api_results as $result_dataR){
    
      $result_dataR->genres          =    implode(",",$result_dataR->genres);
      $result_dataR->countries       =    implode(",",$result_dataR->countries);
      $result_dataR->cast            =    implode(",",$result_dataR->cast);
      $result_dataR->significants    =    implode(",",$result_dataR->significants);

      $result_originalTitle     = mysqli_real_escape_string($conn, $result_dataR->originalTitle);
      $result_overview          = mysqli_real_escape_string($conn, $result_dataR->overview);
      $result_title             = mysqli_real_escape_string($conn, $result_dataR->title);


      if(isset($result_dataR->imdbID)){
        $imdbID = $result_dataR->imdbID;
      }
      else{
          $imdbID = NULL;
      }

      if(isset($result_dataR->tmdbID)){
        $tmdbID = $result_dataR->tmdbID;
      }
      else{
          $tmdbID = NULL;
      }

      if(isset($result_dataR->imdbRating)){
        $imdbRating = $result_dataR->imdbRating;
      }
      else{
          $imdbRating = NULL;
      }

      if(isset($result_dataR->imdbVoteCount)){
        $imdbVoteCount = $result_dataR->imdbVoteCount;
      }
      else{
          $imdbVoteCount = NULL;
      }

      if(isset($result_dataR->tmdbRating)){
        $tmdbRating = $result_dataR->tmdbRating;
      }
      else{
          $tmdbRating = NULL;
      }

      if(isset($result_dataR->originalTitle)){
        $originalTitle = $result_dataR->originalTitle;
      }
      else{
          $originalTitle = NULL;
      }

      if(isset($result_dataR->genres)){
        $genres = $result_dataR->genres;
      }
      else{
          $genres = NULL;
      }

      if(isset($result_dataR->countries)){
        $countries = $result_dataR->countries;
      }
      else{
          $countries = NULL;
      }

      if(isset($result_dataR->year)){
        $year = $result_dataR->year;
      }
      else{
          $year = NULL;
      }

      if(isset($result_dataR->runtime)){
        $runtime = $result_dataR->runtime;
      }
      else{
          $runtime = NULL;
      }

      if(isset($result_dataR->cast)){
        $cast = $result_dataR->cast;
      }
      else{
          $cast = NULL;
      }

      if(isset($result_dataR->significants)){
        $significants = $result_dataR->significants;
      }
      else{
          $significants = NULL;
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

      if(isset($result_dataR->tagline)){
        $tagline = $result_dataR->tagline;
      }
      else{
          $tagline = NULL;
      }

      if(isset($result_dataR->video)){
        $video = $result_dataR->video;
      }
      else{
          $video = NULL;
      }

      if(isset($result_dataR->posterPath)){
        $posterPath = $result_dataR->posterPath;
      }
      else{
          $title = NULL;
      }

      if(isset($result_dataR->age)){
        $age = $result_dataR->age;
      }
      else{
          $age = NULL;
      }

      if(isset($result_dataR->originalLanguage)){
        $originalLanguage = $result_dataR->originalLanguage;
      }
      else{
          $originalLanguage = NULL;
      }


      if($imdbID){
              $result_value = "('"
                                .$imdbID."','"
                                .$tmdbID."','"
                                .$imdbRating."','"
                                .$imdbVoteCount."','"
                                .$tmdbRating."','"
                                .$originalTitle."','"
                                .$genres."','"
                                .$countries."','"
                                .$year."','"
                                .$runtime."','"
                                .$cast."','"
                                .$significants."','"
                                .$title."','"
                                .$overview."','"
                                .$tagline."','"
                                .$video."','"
                                .$posterPath."','"
                                .$age."','"
                                .$originalLanguage
                              ."'),";
      }
      else{
              $result_value = NULL;
      }
    };

      $result_value = rtrim($result_value, ",");

      $SQL = "INSERT INTO `search`(
                                      `imdbID`,
                                      `tmdbID`,
                                      `imdbRating`,
                                      `imdbVoteCount`,
                                      `tmdbRating`,
                                      `originalTitle`,
                                      `genres`,
                                      `countries`,
                                      `year`,
                                      `runtime`,
                                      `cast`,
                                      `significants`,
                                      `title`,
                                      `overview`,
                                      `tagline`,
                                      `video`,
                                      `posterPath`,
                                      `age`,
                                      `originalLanguage`
                                      )
                                      VALUES
                                        $result_value
                                      "; 
 
    //echo $SQL; exit;
    mysqli_query($conn, $SQL);
    echo mysqli_affected_rows($conn). " ROWS INSERTED <br>";
    //echo count(array($result_value)). " ROWS INSERTED <br>";

  };   


?>