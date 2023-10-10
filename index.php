<?php

use phpseclib3\Crypt\AES;

require __DIR__ . '/vendor/autoload.php';

$filename = 'whoosh(trimmed).mp4';

$aes = new AES('ofb');
$key = '9pdwCG0ZL7BcgPtA';
$aes->setKey($key);
$aes->setIV($key);
$file_src = fopen('assets/' . $filename, 'r') or die("Unable to open file!");
$raw = fread($file_src,filesize('assets/' . $filename));
fclose($file_src);

$file_dest = fopen('encrypted/' . $filename, 'w+') or die("Unable to open file!");
fwrite($file_dest, $aes->encrypt($raw));
fclose($file_dest);

$file_src = fopen('encrypted/' . $filename, 'r') or die("Unable to open file!");
$raw = fread($file_src,filesize('encrypted/' . $filename));
fclose($file_src);

$file_dest = fopen('decrypted/' . $filename, 'w+') or die("Unable to open file!");
fwrite($file_dest, $aes->decrypt($raw));
fclose($file_dest);