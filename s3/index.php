<?php
include_once 'vendor/autoload.php';

$provider = \Aws\Credentials\CredentialProvider::ini('default', 'config.ini');
$sdk = new \Aws\Sdk(['credentials' => $provider]);
$client = $sdk->createS3(['region' => 'us-west-2', 'version' => 'latest']);


function upload(\Aws\S3\S3Client $client)
{
    $request = [
        'Bucket'      => 's3-presentation',
        'Key'         => 'photos1/Example.png',
        'ContentType' => 'image/png',
        'ContentDisposition' => 'attachment; filename=Example.png',
        'ACL'         => 'public-read',
        'Body'        => fopen('files/Example.png', 'r')
    ];
    $client->putObject($request);
}

function delete(\Aws\S3\S3Client $client)
{
    $client->deleteObject([
        'Bucket' => 's3-presentation',
        'Key'    => 'Example.png'
    ]);
}

function getUploadUrl(\Aws\S3\S3Client $client)
{
    $request = [
        'Bucket'      => 's3-presentation',
        'Key'         => 'Example.png',
        'ContentType' => 'image/png',
        'ACL'         => 'public-read',
        //'ContentMD5'  => '',
    ];
    $command = $client->getCommand('PutObject', $request);
    return $client->createPresignedRequest($command, '+2 hours')
        ->getUri()
        ->withScheme('https')
        ->__toString();
}

function getUrl(\Aws\S3\S3Client $client) {
    return $client->getObjectUrl('s3-presentation', 'photos1/Example.png');
}

function getPresignedUrl(\Aws\S3\S3Client $client)
{
    $cmd = $client->getCommand('GetObject', [
        'Bucket' => 's3-presentation',
        'Key'    => 'Example.png'
    ]);
    $request = $client->createPresignedRequest($cmd, '+1 minutes');
    return (string)$request->getUri();
}

//upload($client);
//delete($client);
//echo getUploadUrl($client);
//echo getUrl($client);
//echo getPresignedUrl($client);

echo PHP_EOL . 'ok';

?>