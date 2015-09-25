<?php
//todo: error catching



//call the bls api
function callapi($geo, $startyear, $endyear){
  
$url = 'http://api.bls.gov/publicAPI/v2/timeseries/data/';
        $method = 'POST';
        $query = array(
                'seriesid'  => $geo,
                'startyear' => $startyear,
                'endyear'   => $endyear,
                'registrationKey' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',  //GET YOUR OWN KEY
                'annualaverage' => true
        );
        $pd = json_encode($query);
        $contentType = 'Content-Type: application/json';
        $contentLength = 'Content-Length: ' . strlen($pd);

        $result = file_get_contents(
                $url, null, stream_context_create(
                        array(
                                'http' => array(
                                        'method' => $method,
                                        'header' => $contentType . "\r\n" . $contentLength . "\r\n",
                                        'content' => $pd
                                ),
                        )
                )
        );

$data = json_decode($result, TRUE);
  
  return $data;
}


//create an easy to use object from the api result set
function crunchdata($data){

$alldata = array();

//create the perfect json from bls result set    
for($i=0;$i<count($data['Results']['series']); $i=$i+1){
       $output = array( 
         's' => $data['Results']['series'][$i]['seriesID'],
         'd' => array()
       );
     
       for($j=0; $j<count($data['Results']['series'][$i]['data']);$j=$j+1){
         $tmparray = array(
           'k' => substr($data['Results']['series'][$i]['data'][$j]['periodName'],0,3).($data['Results']['series'][$i]['data'][$j]['year']),
           'v' => $data['Results']['series'][$i]['data'][$j]['value']
         );
         
         array_push($output['d'], $tmparray);
         
         }
  
  array_push($alldata, $output);
     
     }
  
  return $alldata;
  
}

//iterate over arrays, when key matches, merge data, deposit into result array
function mergearrays($array1, $array2){
  
$result = array();

foreach($array1 as $i){
  
  foreach($array2 as $j){
    
    if($i['s']==$j['s']){
       $output = array( 
         's' => $i['s'],
         'd' => array_merge($i['d'],$j['d'])
       );
      
      array_push($result, $output);
      
    } 
  }
}

return $result;
}

//This example is for Colorado.

//first set of county id's: Adams to Kiowa
$codes1="LAUCN080010000000003,LAUCN080030000000003,LAUCN080050000000003,LAUCN080070000000003,LAUCN080090000000003,LAUCN080110000000003,LAUCN080130000000003,LAUCN080140000000003,LAUCN080150000000003,LAUCN080170000000003,LAUCN080190000000003,LAUCN080210000000003,LAUCN080230000000003,LAUCN080250000000003,LAUCN080270000000003,LAUCN080290000000003,LAUCN080310000000003,LAUCN080330000000003,LAUCN080350000000003,LAUCN080370000000003,LAUCN080390000000003,LAUCN080410000000003,LAUCN080430000000003,LAUCN080450000000003,LAUCN080470000000003,LAUCN080490000000003,LAUCN080510000000003,LAUCN080530000000003,LAUCN080550000000003,LAUCN080570000000003,LAUCN080590000000003,LAUCN080610000000003";

$geo1 = explode(",", $codes1);

//second set of county id's: Kit Carson to Yuma
$codes2="LAUCN080630000000003,LAUCN080650000000003,LAUCN080670000000003,LAUCN080690000000003,LAUCN080710000000003,LAUCN080730000000003,LAUCN080750000000003,LAUCN080770000000003,LAUCN080790000000003,LAUCN080810000000003,LAUCN080830000000003,LAUCN080850000000003,LAUCN080870000000003,LAUCN080890000000003,LAUCN080910000000003,LAUCN080930000000003,LAUCN080950000000003,LAUCN080970000000003,LAUCN080990000000003,LAUCN081010000000003,LAUCN081030000000003,LAUCN081050000000003,LAUCN081070000000003,LAUCN081090000000003,LAUCN081110000000003,LAUCN081130000000003,LAUCN081150000000003,LAUCN081170000000003,LAUCN081190000000003,LAUCN081210000000003,LAUCN081230000000003,LAUCN081250000000003";

$geo2 = explode(",", $codes2);


//program logic

//processing first set of counties

//bls api needs to be called twice to get all years (they have 20 year max)
$outputset1=callapi($geo1, "2010", date("Y"));
$outputset2=callapi($geo1, "1990", "2009");

//reorganize data
$crunch1=crunchdata($outputset1);
$crunch2=crunchdata($outputset2);

//use the mergearrays function to combine all years into one php object
$result1=mergearrays($crunch1, $crunch2);


//processing second set of counties

//bls api needs to be called twice to get all years (they have 20 year max)
$outputset3=callapi($geo2, "2010", date("Y"));
$outputset4=callapi($geo2, "1990", "2009");

//reorganize data
$crunch3=crunchdata($outputset3);
$crunch4=crunchdata($outputset4);

//use the mergearrays function to combine all years into one php object
$result2=mergearrays($crunch3, $crunch4);


//merge all colorado counties together
$result=array_merge($result1, $result2);



//create a json folder to hold the data
if(!file_exists('json')){
mkdir("json", 0777, true) or die("cant do it");
}

//write json data to a file
$myfile = fopen("json/colorado_bls.json", "w") or die("Unable to open file!");
fwrite($myfile, json_encode($result));
fclose($myfile);


?>