<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>CiviCRM buildkit on Docker</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>

  <div class="container">

    <h1>CiviCRM buildkit on Docker</h1>
    <p><a href="localhost:8081">phpMyAdmin</a></p>
    <p><a href="localhost:8082">maildev</a></p>
    <?php
    $sites = explode("\n", `grep -h ^CMS_URL /buildkit/build/*.sh`);
    if(count($sites)){
      echo "<h2>Sites</h2>";

      foreach($sites as $site){
        $url = explode('"', $site)[1];
        echo "<p><a href='$url'>$url</a></p>";
      }
    }
    ?>

  </div>


</body>
</html>
