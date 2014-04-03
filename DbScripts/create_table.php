<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Create a Table</title>
</head>
<body>
<?php // create_table.php 
/* This script connects to the MySQL server, selects the database, and creates a table. */

// Connect and select:
if ($dbc = @mysql_connect('localhost', 'root', '')) {
	
	// Handle the error if the database couldn't be selected:
	if (!@mysql_select_db('docdatabase', $dbc)) {
		print '<p style="color: red;">Could not select the database because:<br />' . mysql_error($dbc) . '.</p>';
		mysql_close($dbc);
		$dbc = FALSE;
	}

} else { // Connection failure.
	print '<p style="color: red;">Could not connect to MySQL:<br />' . mysql_error() . '.</p>';
}

if ($dbc) {

	// Define the recordings table query:
	$query = "CREATE TABLE IF NOT EXISTS `recordings` (
  `recordingID` int(10) NOT NULL AUTO_INCREMENT,
  `recordingTitle` varchar(50) NOT NULL,
  `recordingArtist` varchar(50) NOT NULL,
  `musicCategory` varchar(50) NOT NULL,
  `notes` longtext,
  `recordingCompany` varchar(50) DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `numOfTracks` int(2) DEFAULT NULL,
  `releaseYr` int(4) DEFAULT NULL,
  `purchaseDate` date DEFAULT NULL,
  `purchasePrice` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`recordingID`))";

	// Execute the query:
	if (@mysql_query($query, $dbc)) {
		print '<p>The table has been created!</p>';
	} else {
		print '<p style="color: red;">Could not create the table because:<br />' . mysql_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
	}
		
	mysql_close($dbc); // Close the connection.

}
?>
</body>
</html>