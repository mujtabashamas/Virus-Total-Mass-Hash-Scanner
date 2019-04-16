
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Total Virus Mutpliple Scanner</title>

    <!-- Bootstrap core CSS -->
    <link href="https://bootswatch.com/3/united/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
   
  </head>

  <body>

    <div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="batchHash.php">Hashes</a></li>
          </ul>
        </nav>
        <h3 class="text-muted" style="color: #2c3e50">Total Virus Scan</h3>
      </div>

      <div class="jumbotron">
        <h1>Scan Hashes</h1>
        <form class="well" action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <h2 for="hash">Use: 4 Hashes Per Key Per Minute</h2>
            <textarea class="form-control" name="hashes" rows="8" style="margin-bottom: 10px;"></textarea>

            <label class="checkbox-inline"><input type="checkbox" value="fb83f53138c1db4e0c43bd389a6e8840db1ec0815e867c05835918e5023c9eb4" name="key1">Key 1</label>
            <label class="checkbox-inline"><input type="checkbox" value="7a922edbd57ffd99774d6e9d8a1d867883fefe27fae971cb9e46d48e2c065dca" name="key2">Key 2</label>
            <label class="checkbox-inline"><input type="checkbox" value="7876c4643cfd62471d2518acde198635760844675ea707e4113f7eb03c0bc5b9" name="key3">Key 3</label>
            <label class="checkbox-inline"><input type="checkbox" value="17e775364160afb13be549cecf150426e9bfb85553e531cceeb24658b2901442" name="key4">Key 4</label>
          </div>
          <button id="button" class="btn btn-lg btn-primary" type="submit" name="btn-upload">Submit</button>
          </form>
      </div>

  </div>
  <div class="container" style="width: 50%">

<?php
  require_once('VTAPI.php');
  
  if(isset($_POST['btn-upload']))
  {
    
    if(!isset($_POST['hashes']))
      $hashes = '';
    else
      $hashes = $_POST['hashes'];
    
    $apiKeys = array();
    $count = 0;
    
    if(!isset($_POST['key1']))
      $key1 = '';
    else { 
      $key1 = $_POST['key1'];
      array_push($apiKeys, $key1);
      $count += 4;
    }
    
    if(!isset($_POST['key2']))
      $key2 = '';
    else {  
      $key2 = $_POST['key2'];
      array_push($apiKeys, $key2);
      $count += 4;
    }

    if(!isset($_POST['key3']))
      $key3 = '';
    else {
      $key3 = $_POST['key3'];
      array_push($apiKeys, $key3);
      $count += 4;
    }
      
    if(!isset($_POST['key4']))
      $key4 = '';
    else { 
      $key4 = $_POST['key4'];
      array_push($apiKeys, $key4);
      $count += 4; 
    }

    $hcount = 0;

    $hash = explode("\n", $hashes);

    $hashLength = strlen($hashes);
    
    for($i=0;$i<$hashLength;$i++){
      if($hashes[$i] === "\n")
      {
        $hcount++;
      }
    }

    $data = array();
    
    $apiCount = 0;
    $api = new VirusTotalAPIV2($apiKeys[$apiCount]);
    $f = 1;
    $data1 = array("Hash","McAfee","TrendMicro","Fortinet","F-Secure");
    array_push($data, $data1);
    /* Get a file report.*/ 
    echo "<h1>Scan Results</h1>";
    for ($i=0; $i <= $hcount; $i++) { 
      if($count === 0){
        break;
      }

      if($i%4 === 0 && !($i === 0)){

        $apiCount++;
        $api = new VirusTotalAPIV2($apiKeys[$apiCount]);
      }
      echo "<div class='well'>";
      echo "<h4>Scan Number ". $f++ . "</h4></br>";
      echo "<p>Scan result for ".$hash[$i]."</p> </br>";
      $report = $api->getFileReport($hash[$i]);
      //$api->displayResult($report);
      //array_push($data, $hash[$i]);
      array_push($data, $api->displayResult($report, $hash[$i]));
      if($api->getSubmissionDate($report) === -1){
        print('Invalid Hash or API Limit Exceeded');
      } else{
        print( 'The report was submitted on '.$api->getSubmissionDate($report) . '</br>');
      }
      echo "</div>";
      $count--;
      //print_r($data);
    };
    echo '<a href="temp/report.csv"><button class="btn btn-lg btn-primary">Download</button></a>';
    $csvFileName = 'temp/report.csv';
    $fp = fopen($csvFileName, 'w');
    foreach($data as $row){
        fputcsv($fp, $row);
    }
    fclose($fp);
  
  }
?>

    </div> <!-- /container -->


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </body>
</html>