<?php
$api_key = '';

$moviePoster = "";
$movieTitle = "";
$movieGenres = "";
$movieOverview = "";
$top5Cast = "";

if(!empty($_GET['search'])) {
  $url = 'https://api.themoviedb.org/3/search/movie?api_key='.$api_key.'&language=en-US&query='.urlencode($_GET['search']);
  $results_json = file_get_contents($url);
  $results_array = json_decode($results_json, true);
};

if(!empty($_GET['id'])) {

  $details_url = 'https://api.themoviedb.org/3/movie/'.$_GET['id'].'?api_key='.$api_key.'&language=en-US';
  $details_json = file_get_contents($details_url);
  $details_array = json_decode($details_json, true);

  $credits_url = 'https://api.themoviedb.org/3/movie/'.$_GET['id'].'/credits?api_key='.$api_key;
  $credits_json = file_get_contents($credits_url);
  $credits_array = json_decode($credits_json, true);

  $moviePoster = 'http://image.tmdb.org/t/p/w185'.$details_array['poster_path'];
  $movieTitle = $details_array['original_title'].' '.'('.explode('-', $details_array['release_date'])[0].')';

  for($i = 0; $i < count($details_array['genres']); $i++) {
    
    if($i < count($details_array['genres']) - 1) {
      $movieGenres .= $details_array['genres'][$i]['name'].", ";
    } else {
      $movieGenres .= $details_array['genres'][$i]['name'];
    }
  }

  $movieOverview = $details_array['overview'];

  for($j = 0; $j < 5; $j++) {
    
    if($j < 4) {
      $top5Cast .= $credits_array['cast'][$j]['name'].", ";
    } else {
      $top5Cast .= $credits_array['cast'][$j]['name'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Display Movie Information</title>
  <meta charset="utf-8" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <style>
    body {
      display: flex;
      flex-direction: column;
      margin: 0;
      background-color: #1f2833;
      font-family: 'Montserrat', sans-serif;
      position: relative;
      min-height: 100vh;
    }

    main {
      display: flex;
      color: white;
      flex-grow: 1;
    }

    content {
      flex-basis: 80%;
      background-color: #151c23;
      flex-grow: 1;
    }

    sidebar {
      flex-basis: 20%;
      background-color: #1f2833;
      flex-grow: 1;
      min-height: 0;
      position: relative;
      order: -1;
      padding: 10px;
    }

    @media all and (max-width: 640px) {

      main {

        flex-direction: column;
          flex-grow: 1;
      }
          
      nav {
        order: 0;
      }

      sidebar {
        flex-basis: 50%;
      }

      content {
        flex-basis: 50%;
      }
    }
  </style>
</head>
<body>
    <main>
      <sidebar>
        <div class="split-left left" id="search-results">
          <form action="">
            <label for="search">Movie title:</label>
            <input type="text" name="search"/>
            <button type="submit">Display Info</button>
          </form>
          <div class="centered" id="search-res">
            <?php
              if(is_array(@$results_array) || is_object(@$results_array)) {
                foreach(@$results_array['results'] as $result) { ?>
                  <br><a class="nav-link" href='movies.php?id=<?php print $result["id"]; ?>' style="color: white; font-weight: bold;"><?php print $result['title'].' '.'('.explode("-", $result['release_date'])[0].')'; ?></a><hr>
                  <?php
                }
              }?>
          </div>
        </div>
      </sidebar>
      <content>
	      <div class="split-right right">
	        <div id="movieDetails" style="align-items: center;">
	          <img src="<?php print $moviePoster; ?>" width="200px" height="300px" id="moviePoster" style="display: block; margin: auto; margin-top: 15%; border: 0;" onerror='this.style.display = "none"'>
	          <div style="padding: 0% 20% 0% 20%;">
	            <h1 id="movieTitle" style="text-align:center"><?php print $movieTitle; ?></h1>
	            <p id="movieGenres" style="text-align:center"><?php print $movieGenres; ?></p>
	            <p id="movieOverview" style="text-align: justify;"><?php print $movieOverview; ?></p>
	            <p id="top5cast" style="text-align:center"><?php print $top5Cast; ?></p>
	          </div>
	        </div>
	      </div>
      </content>
    </main>
</body>
</html>