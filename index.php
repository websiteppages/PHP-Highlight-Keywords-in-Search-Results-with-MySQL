<?php
// Include database config file
require_once 'dbConfig.php';

// If search form is submitted
$searchKeyword = $whrSQL = '';
if(isset($_POST['searchSubmit'])){
	$searchKeyword = $_POST['keyword'];
	if(!empty($searchKeyword)){
		// SQL query to filter records based on the search term
		$whrSQL = "WHERE (title LIKE '%".$searchKeyword."%' OR content LIKE '%".$searchKeyword."%')";
	}
}

// Get matched records from the database
$result = $db->query("SELECT * FROM posts $whrSQL ORDER BY id DESC");

// Highlight words in text
function highlightWords($text, $word){
	$text = preg_replace('#'. preg_quote($word) .'#i', '<span class="hlw">\\0</span>', $text);
    return $text;
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title> Codeat21 - PHP Highlight Keywords in Search Results with MySQL </title>
<meta charset="utf-8">

<!-- Stylesheet file -->
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
	<div class="row">
		<h2>Posts List (<?php echo $result->num_rows; ?>) </h2>
		<!-- Search form -->
		<form method="post">
		<div class="input-group">
			<input type="text" name="keyword" class="form-control" value="<?php echo $searchKeyword; ?>" placeholder="Search by keyword..." >
			<div class="input-group-append">
				<input type="submit" name="searchSubmit" class="btn btn-outline-secondary btn-colors" value="Search">
				<a href="index.php" class="btn btn-outline-secondary btn-colors2">Reset</a>
			</div>
		</div>
		</form>
    
        <!-- Search results -->
		<?php
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){ 
				$title = !empty($searchKeyword)?highlightWords($row['title'], $searchKeyword):$row['title'];
				$contnet = !empty($searchKeyword)?highlightWords($row['content'], $searchKeyword):$row['content'];
		?>
		<div class="list-item">
			
			<h4><?php echo $title; ?></h4>
			<p><?php echo $contnet; ?></p>
		</div>
		<?php } }else{ ?>
		<div class="list-item">
			<p>No post(s) found...</p>
		</div>
		
		<?php } ?>
    </div>
</div>
</body>
</html>