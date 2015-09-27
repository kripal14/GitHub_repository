<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//this is the link or url that the user will provide
$link = $_POST["url"];
//if it is empty then It will show message to enter a url
if(!empty($link)){
//first string is exploded on the basis of '.'
$explodeOnDot = explode('.',$link);
//the second element of the array will contain the repo name and owner
//this is the url structute
//"https://api.github.com/repos/:owner/:repo/issues"

$explodeOnSLash = explode('/',$explodeOnDot[1]);
//$explodeOnDot[1] and $explodeOnDot[2] are the owner and repo filed respectively
$orgDate = new DateTime(date('c'));
$urlForCurrent = 'https://api.github.com/repos/'.$explodeOnSLash[1].'/'.$explodeOnSLash[2].'/'.'issues';
$orgDate->modify('-1 day');
//since parameter is used to set the Date and time in ISO 8601 format 
//it returns the issues opened or updated after that date and time
$urlForLastOneDay = 'https://api.github.com/repos/'.$explodeOnSLash[1].'/'.$explodeOnSLash[2].'/'.'issues?since='.$orgDate->format(DateTime::ISO8601);;
$orgDate->modify('-6 day');
$urlForLastSevenDay = 'https://api.github.com/repos/'.$explodeOnSLash[1].'/'.$explodeOnSLash[2].'/'.'issues?since='.$orgDate->format(DateTime::ISO8601);;

//here while loop is used for looping through each page of results 
//because there is limitaion on hitting api and the results we get

$pageNo = 1;
$total = 0;
//this loop is used to get total result count of issues
while(true){
  $urlForCurrentPageWise = $urlForCurrent.'?page='.$pageNo.'&per_page=100';
  $curl = curl_init($urlForCurrentPageWise);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Random');
  $response = curl_exec($curl);
  $count = count(json_decode($response));
  if($count == 0){
  	//this loop runs until we get the result
    break;
  }else{
    $total = $total + $count;
  }
  curl_close($curl);
  ++$pageNo;
}

$pageNo = 1;
$lastOneDay = 0;
//this loop is used to get total issues opened in last 24 hours
while(true){
$urlForLastOneDayPageWise = $urlForLastOneDay.'&page='.$pageNo.'&per_page=100';
  $curl = curl_init($urlForLastOneDayPageWise);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Random');
  $response = curl_exec($curl);
  $count = count(json_decode($response));
  if($count == 0){
    break;
  }else{
    $lastOneDay = $lastOneDay + $count;
  }
  curl_close($curl);
  ++$pageNo;
}

$pageNo = 1;
$lastSevenDay = 0;
//this loop is used to get the result opened more than 24 hours ago but less than 7 days ago
while(true){
$urlForLastSevenDayPageWise = $urlForLastSevenDay .'&page='.$pageNo.'&per_page=100';
  $curl = curl_init($urlForLastSevenDayPageWise);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Random');
  $response = curl_exec($curl);
  $count = count(json_decode($response));
  if($count == 0){
    break;
  }else{
    $lastSevenDay = $lastSevenDay + $count;
  }
  curl_close($curl);
  ++$pageNo;
}


echo '<br>Total number of open issues :<b>'.$total."</b><br>";
echo '<br>Number of open issues that were opened in the last 24 hours :<b>'.$lastOneDay."</b><br>";
echo '<br>Number of open issues that were opened more than 24 hours ago but less than 7 days ago :<b>'.($lastSevenDay - $lastOneDay)."</b><br>";
echo '<br>Number of open issues that were opened more than 7 days ago :<b>'.($total-$lastSevenDay)."</b><br>";
} else{
echo 'Enter a vaid Url';
}


?>
