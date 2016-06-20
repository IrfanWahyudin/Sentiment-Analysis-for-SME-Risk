<html>
<?php
	$doc_id = $_GET["doc_id"];
	$db = new SQLite3('C:\demo\DB_WORDS');
	
	$sql = 'select content from mst_corpus where id = ' . $doc_id;
	$results = $db->query($sql);

	while ($row = $results->fetchArray()) {
		echo  '<textarea style="width:800; height:600;" >' . $row['content'] . '</textarea>';
	}
?>
</html>