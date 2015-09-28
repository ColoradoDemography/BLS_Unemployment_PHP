
<?php
	require 'aws.phar';  //get this file here:  https://github.com/aws/aws-sdk-php/releases
  require 'keydir/keys.php'; //AWS keys

//for more about getting an aws api key (and this process in general), look here:  http://kwynn.com/t/4/09/AWS_S3_PHP_example.html
//the steps are slightly different (since the link is a bit old), but you can probably stumble through them well enough.. like i did

	use Aws\S3\S3Client;

	$config = array(
      'region' => 'us-west-2',  //depends on what region your bucket is in
      'version' => '2006-03-01',  //use this to lock in this version of the php api
      'credentials' => array(
    'key' => $pubkey,  //change this to your key
    'secret'  => $privkey,  //change this to your secret key
  )
	);


	$s3v2 = S3Client::factory($config);


//array list all states
$statesarray=["01","02","04","05","06","08","09","10","11","12","13","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","44","45","46","47","48","49","50","51","53","54","55","56"];

//create a loop here that will loop through all states and upload to s3
for($p=0;$p<count($statesarray);$p=$p+1){
  
  
	$result = $s3v2->putObject(array(
	    'Bucket' => 'blsdata',   //name of the bucket to upload to
        'Key'          => $statesarray[$p].'_bls.json',  //name of what you want the file to be called in your aws s3 bucket
    'SourceFile'   => './json/'.$statesarray[$p].'_bls.json',  //name and path of the file to upload
    'ContentType'  => 'text/plain',  //json is just text
    'ACL'          => 'public-read',  //immediately set as publicly available
	));

	echo $result['ObjectURL'] . "\n";

} //end 'p' loop



?>