<?php
	error_reporting(0);
	include "curl_gd.php";
	include "download_helper.php";
	$file = null;

	if(isset($_POST['submit']) && $_POST['submit'] != ""){
		$url = $_POST['url'];
		$gid = get_drive_id($url);
		if(!$gid) {
			die('GID not found');
		}
		$backup = 'https://drive.google.com/file/d/'.$gid.'/preview';
		$iframeid = my_simple_crypt($gid);
		$filename = slugify($iframeid) . '.mp4';
		$poster_filename = $filename . '.png';
		$posterimg = './cache/' . $poster_filename;
		if(!file_exists($posterimg)) {
			$posterurl = PosterImg($backup);
			download($poster_filename, $posterurl);
		}
		
		if(!file_exists('./cache/' . $filename)) {
			$linkdown = Drive($url);
			download($filename, $linkdown);
		}
		$file = '[{"type": "video/mp4", "label": "HD", "src": "./cache/'.$filename.'"}]';
	}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
	<title>Embed google drive generator</title>
	<link rel="stylesheet" type="text/css" href="./bower_components/plyr/dist/plyr.css">
	<style>
		.container {
		  max-width: 800px;
		  margin: 0 auto;
		}
	</style>
</head>
<body>

  <!-- Docs styles -->
	

	<div class="container">
		<br />
		<form action="" method="POST">
			<input type="text" size="80" name="url" placeholder="https://drive.google.com/file/d/0ByaRd0R0Qyatcmw2dVhQS0NDU0U/view"/>
			<input type="submit" value="GET" name="submit" />
		</form>
		<br/>

		<?php if(!is_null($file)): ?>
			<video class="js-player" controls></video>
			<script type="text/javascript" src="./bower_components/plyr/dist/plyr.js"></script>
			<script>
				var players = plyr.setup(document.querySelector('.js-player'), {
					iconUrl: "./bower_components/plyr/dist/plyr.svg"
				});
				var player = players[0];
				player.source({
					type:       'video',
					title:      'Embed Video',
					sources:	<?php echo $file; ?>,
					poster:		'<?php echo $posterimg; ?>'
				});
			</script>
		<?php endif; ?>
	</div>
</body>
</html>
