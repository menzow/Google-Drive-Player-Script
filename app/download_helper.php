<?php

set_time_limit(0);
function slugify($text) {
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
function download($filename, $url) {
	$path = dirname(__FILE__) . '/cache/' . $filename;
	if(!file_exists($path)) {
		$file = fopen($path, 'w+');
		$curl = curl_init($url);
		// Update as of PHP 5.4 array() can be written []
		curl_setopt_array($curl, [
		    CURLOPT_URL            => $url,
		//  CURLOPT_BINARYTRANSFER => 1, --- No effect from PHP 5.1.3
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_FILE           => $file,
		    CURLOPT_TIMEOUT        => 50,
		    CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
		]);

		$response = curl_exec($curl);

		if($response === false) {
		    // Update as of PHP 5.3 use of Namespaces Exception() becomes \Exception()
		    unlink($path);
		    throw new \Exception('Curl error: ' . curl_error($curl));
		}
	}

	return $path;
}