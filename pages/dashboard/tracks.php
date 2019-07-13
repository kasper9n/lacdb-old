<?

	// db query
	$query = " SELECT * FROM tracks ORDER BY catalog_id";
	$result = mysqli_query($db_connection, $query);
	// check for error
	if (!$result) {
		die("Database query failed.");
	}

?>

<main class="tracks-page">


	<aside>
		<div class="main">

			<div class="card add">
				<p>Tracks</p>
				<a href="/dashboard/tracks?item=add"></a>
			</div>

			<? while ($track = mysqli_fetch_assoc($result)) { ?>

				<?

					$id = $track["id"];
					$catalog_id = htmlentities($track["catalog_id"]);
					$artist = htmlentities($track["artist"]);
					$title = htmlentities($track["title"]);
					$link_soundcloud = htmlentities($track["link_soundcloud"]);
					$link_youtube = htmlentities($track["link_youtube"]);
					$link_spotify = htmlentities($track["link_spotify"]);
					$link_bandcamp = htmlentities($track["link_bandcamp"]);
					$link_itunes = htmlentities($track["link_itunes"]);
					$link_beatport = htmlentities($track["link_beatport"]);

					// Find image path
					if (file_exists("cdn/covers/$id-100.png")) {
						$img_path = "/cdn/covers/$id-100.png";
					} else {
						$img_path = "";
					}

					// Current page highlighting
					$class = "";
					if (isset($_GET["item"])) {
						if ($_GET["item"] == $id) {
							$class = "current-page";
						}
					}
				?>

				<a href="/dashboard/tracks?item=<?=$id?>">
					<div class="card">
						<img class="card-bg" src="<?=$img_path?>"/>
						<div class="text">
							<p class="title <?="$class"?>"><?=$title?></p>
							<p class="artist <?="$class"?>"><?=$artist?></p>
						</div>
					</div>
					<div class="invisible-card"></div>
				</a>
			<? } mysqli_free_result($result); ?>
		</div>
		<a href="/logout" class="logout">
			<div class="card">Logout</div>
		</a>
	</aside>

	<section>

		<? if (isset($_GET["item"]) && $_GET["item"] == "add"): ?>
			<form class="add" action="/post" method="post" enctype="multipart/form-data" autocomplete="off">
				<div class="main flex-col add">
					<input type="hidden" name="id" value="<?=$id?>"/>
					<div>
						<input type="text" name="artist" placeholder="Artist"/>
						<input type="text" name="title" placeholder="Title"/>
						<input type="text" name="catalog_id" placeholder="LCR???"/>
					</div>
					<div>
						<input class="link" type="text" name="link_soundcloud" placeholder="SoundCloud link"/>
						<input class="link" type="text" name="link_youtube" placeholder="YouTube link"/>
					</div>
					<div>
						<input class="link" type="text" name="link_spotify" placeholder="Spotify link"/>
						<input class="link" type="text" name="link_bandcamp" placeholder="Bandcamp link"/>
					</div>
					<div>
						<input class="link" type="text" name="link_itunes" placeholder="iTunes link"/>
						<input class="link" type="text" name="link_beatport" placeholder="Beatport link"/>
					</div>
					<div class="file-section">
						<div class="file-box">
							<p>Upload Cover</p>
							<label class="file" for="cover_png_jpg">png/jpg</label>
							<label class="file" for="cover_psd">psd</label>
						</div>
						<div class="file-box">
							<p>Upload Audio</p>
							<label class="file" for="song_wav">wav</label>
							<label class="file" for="song_mp3">mp3-320kbps</label>
						</div>
						<input id="cover_png_jpg" type="file" name="cover_png_jpg" accept="image/jpeg, image/png"/>
						<input id="cover_psd" type="file" name="cover_psd" accept="image/psd"/>
						<input id="song_wav" type="file" name="song_wav" accept="audio/wav"/>
						<input id="song_mp3" type="file" name="song_mp3" accept="audio/mp3"/>
					</div>
					<div class="action-section">
						<label for="add"></label>
						<input id="add" type="submit" name="submit_add" value="Add"/>
					</div>
				</div>
			</form>

		<? elseif (isset($_GET["item"]) && is_numeric($_GET["item"])):

				$query = " SELECT * FROM tracks WHERE id = '{$_GET["item"]}' ";
				$result = mysqli_query($db_connection, $query);
				$track = mysqli_fetch_assoc($result);

				$id = $track["id"];
				$catalog_id = htmlentities($track["catalog_id"]);
				$artist = htmlentities($track["artist"]);
				$title = htmlentities($track["title"]);
				$link_soundcloud = htmlentities($track["link_soundcloud"]);
				$link_youtube = htmlentities($track["link_youtube"]);
				$link_spotify = htmlentities($track["link_spotify"]);
				$link_bandcamp = htmlentities($track["link_bandcamp"]);
				$link_itunes = htmlentities($track["link_itunes"]);
				$link_beatport = htmlentities($track["link_beatport"]);

				?>

			<form action="/post?item=<?=$id?>" method="post" enctype="multipart/form-data" autocomplete="off">
				<div class="main flex-col">
					<input type="hidden" name="id" value="<?=$id?>"/>
					<div>
						<input type="text" name="artist" placeholder="Artist" value="<?=$artist?>"/>
						<input type="text" name="title" placeholder="Title" value="<?=$title?>"/>
						<input type="text" name="catalog_id" placeholder="LCR???" value="<?=$catalog_id?>"/>
					</div>
					<div>
						<input class="link" type="text" name="link_soundcloud" placeholder="SoundCloud link" value="<?=$link_soundcloud?>"/>
						<input class="link" type="text" name="link_youtube" placeholder="YouTube link" value="<?=$link_youtube?>"/>
					</div>
					<div>
						<input class="link" type="text" name="link_spotify" placeholder="Spotify link" value="<?=$link_spotify?>"/>
						<input class="link" type="text" name="link_bandcamp" placeholder="Bandcamp link" value="<?=$link_bandcamp?>"/>
					</div>
					<div>
						<input class="link" type="text" name="link_itunes" placeholder="iTunes link" value="<?=$link_itunes?>"/>
						<input class="link" type="text" name="link_beatport" placeholder="Beatport link" value="<?=$link_beatport?>"/>
					</div>
					<div class="file-section">
						<div class="file-box">
							<p>Upload Cover</p>
							<label class="file" for="cover_png_jpg">png/jpg</label>
							<label class="file" for="cover_psd">psd</label>
						</div>
						<div class="file-box">
							<p>Upload Audio</p>
							<label class="file" for="song_wav">wav</label>
							<label class="file" for="song_mp3">mp3-320kbps</label>
						</div>
						<input id="cover_png_jpg" type="file" name="cover_png_jpg" accept="image/jpeg, image/png"/>
						<input id="cover_psd" type="file" name="cover_psd" accept="image/psd"/>
						<input id="song_wav" type="file" name="song_wav" accept="audio/wav"/>
						<input id="song_mp3" type="file" name="song_mp3" accept="audio/mp3"/>
					</div>
					<div class="action-section">
						<label for="edit"></label>
						<label for="delete"></label>
						<input id="edit" type="submit" name="submit_edit" value="Edit"/>
						<input id="delete" type="submit" name="submit_delete" value="Delete"/>
					</div>
				</div>
				<div class="downloads flex-row">
					<div class="download-section flex-row">
						<div class="cover images">
							<? $id = $id;
							if (file_exists("cdn/covers/$id-100.png")) {
								$element = "<img class='img' src='/cdn/covers/$id-100.png'/>";
							} else {
								$element = "<div class='img'></div>";
							} ?>
							<?=$element?>
							<div class="flex-col">
								<a download href="/cdn/covers/<?=$id?>-1000.png">1000-png</a>
								<a download href="/cdn/covers/<?=$id?>-1500.png">1500-png</a>
								<a download href="/cdn/covers/<?=$id?>-1450.jpg">1450-jpg</a>
							</div>
						</div>
						<div class="cover audio">
							<div class="flex-col">
								<a download href="/cdn/songs/<?=$id?>.wav">mp3</a>
								<a download href="/cdn/songs/<?=$id?>.mp3">wav</a>
							</div>
						</div>
						<div class="cover description">
							<div class="flex-col">
								<a onclick="openDescription()">Description</a>
							</div>
						</div>
					</div>
				</div>
			</form>
			<? mysqli_free_result($result); ?>

		<? else: ?>
			<div class="flex-col">
				<h1>Lac.db</h1>
			</div>
		<? endif; ?>

	</section>

	<div class="description-modal hidden">
		<?include("./includes/description.php");?>
			<textarea rows="<?=$descr_rows?>"><?=$description?></textarea>
		<div class="description-modal two hidden" onclick="closeDescription()"></div>
	</div>

</main>
