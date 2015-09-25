
<?php
	require 'aws.phar';  //get this file here:  https://github.com/aws/aws-sdk-php/releases

//for more about getting an aws api key (and this process in general), look here:  http://kwynn.com/t/4/09/AWS_S3_PHP_example.html
//the steps are slightly different (since the link is a bit old), but you can probably stumble through them well enough.. like i did

	use Aws\S3\S3Client;

	$config = array(
      'region' => 'us-west-2',  //depends on what region your bucket is in
      'version' => '2006-03-01',  //use this to lock in this version of the php api
      'credentials' => array(
    'key' => 'XXXXXXXXXXXXXXXXXXXX',  //change this to your key
    'secret'  => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',  //change this to your secret key
  )
	);


	$s3v2 = S3Client::factory($config);


	$result = $s3v2->putObject(array(
	    'Bucket' => 'blsdata',   //name of the bucket to upload to
        'Key'          => 'colorado_bls.json',  //name of what you want the file to be called in your aws s3 bucket
    'SourceFile'   => './json/colorado_bls.json',  //name and path of the file to upload
    'ContentType'  => 'text/plain',  //json is just text
    'ACL'          => 'public-read',  //immediately set as publicly available
	));

	echo $result['ObjectURL'] . "\n";
?>