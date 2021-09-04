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

$moviesRS = mysqli_query($conn, "SELECT `id` FROM `movie_info` ORDER BY RAND() limit 10");

while($moviesR = mysqli_fetch_assoc($moviesRS)){

  $movie_id = $moviesR['id'];

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.themoviedb.org/3/movie/'.$movie_id.'/reviews?api_key=dec365ad7fbd4450c8e10736d8133f81&language=en-US&page=1',
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

  if($response->results){

    $api_review_results = $response->results;

    $result_val = null; 

    if (is_array($api_review_results) || is_object($api_review_results)){

        foreach($api_review_results as $result_dataR1){
          
              if(isset($result_dataR1->author)){
                $author = $result_dataR1->author;
              }
              else{
                  $author = NULL;
              }
          
              if(isset($result_dataR1->author_details)){
                $author_details = $result_dataR1->author_details;
              }
              else{
                  $author_details = NULL;
              }
          
              if(isset($result_dataR1->content)){
                $content = $result_dataR1->content;
              }
              else{
                $content = NULL;
              }
          
              if(isset($result_dataR1->created_at)){
                $created_at = $result_dataR1->created_at;
              }
              else{
                $created_at = NULL;
              }
              if(isset($result_dataR1->id)){
                $author_id = $result_dataR1->id;
              }
              else{
                $author_id = NULL;
              }

              if(isset($result_dataR1->updated_at)){
                $updated_at = $result_dataR1->updated_at;
              }
              else{
                $updated_at = NULL;
              }

              if(isset($result_dataR1->url)){
                $url = $result_dataR1->url;
              }
              else{
                $url = NULL;
              }

              $author_details_result = json_encode($result_dataR1->author_details); 

              $content = mysqli_real_escape_string($conn, $content);

              if($author_id){
                      $result_val = "('"
                                        .$movie_id."','"
                                        .$author."','"
                                        .$author_details_result."','"
                                        .$content."','"
                                        .$created_at."','"
                                        .$author_id."','"
                                        .$updated_at."','"
                                        .$url
                                      ."'),";
              }
              else{
                      $result_val = NULL;
              }
      }

    };

            $result_val = rtrim($result_val, ",");
    
            $SQL1 = "INSERT INTO `get_review`(
                                            `id`,
                                            `movie_id`,
                                            `author`,
                                            `author_details`,
                                            `content`,
                                            `created_at`,
                                            `author_id`,
                                            `updated_at`,
                                            `url`
                                            )
                                            VALUES
                                              $result_val
                                            "; 
    

        //echo $SQL1; exit;
        mysqli_query($conn, $SQL1); 
        $rows_inserted = mysqli_affected_rows($conn);

        if($rows_inserted > 0){
          echo $rows_inserted." ROWS INSERTED <br>";
        }
        else{
          echo "Something went wrong, may be review already exists! <br>";
        }

  }
  else{
    echo "Data not found! <br>";
  }

  
};


?>