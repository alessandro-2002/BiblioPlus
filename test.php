
<?php

$page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:9788817079761");

$data = json_decode($page, true);

if ($data['totalItems'] == 1) {

	echo "Title = " . $data['items'][0]['volumeInfo']['title'];
	echo "Authors = " . @implode(",", $data['items'][0]['volumeInfo']['authors']);
	echo "Pagecount = " . $data['items'][0]['volumeInfo']['pageCount'];

	if (isset($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
		$url = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];

		$img = 'logo2.jpg';

		// Function to write image into file
		file_put_contents($img, file_get_contents($url));

		echo "File downloaded!";
	} else {
		echo "no img";
	}
} else {
	echo "no books";
}

//https://codepen.io/mkokes/pen/KqvZNY

?>