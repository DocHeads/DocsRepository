<?php
include '../Lib/Session.php';
Session::validateSession();
include ('../templates/header.php');
$errMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $recordingId = trim($_POST['recordingId']);
  $recordingTitle = trim($_POST['recordingTitle']);
  $recordingArtist = trim($_POST['recordingArtist']);
  $musicCategory = trim($_POST['musicCategory']);
  $notes = trim($_POST['notes']);
  $recordingCompany = trim($_POST['recordingCompany']);
  $format = trim($_POST['format']);
  $numOfTracks = trim($_POST['numOfTracks']);
  $releaseYr = trim($_POST['releaseYr']);
  $purchaseDate = trim($_POST['purchaseDate']);
  $purchasePrice = trim($_POST['purchasePrice']);

  if (isset($_POST['update'])) {
    // check title
    if (!empty($recordingTitle)) {
      // chech artist
      if (!empty($recordingArtist)) {
        // check category
        if (!empty($musicCategory)) {
          $query = "UPDATE recordings";
          $query .= "  SET recordingTitle = '{$recordingTitle}',";
          $query .= "      recordingArtist = '{$recordingArtist}',";
          $query .= "      musicCategory = '{$musicCategory}',";
          $query .= "      notes = '{$notes}',";
          $query .= "      recordingCompany = '{$recordingCompany}',";
          $query .= "      format = '{$format}',";
          $query .= "      numOfTracks = '{$numOfTracks}',";
          $query .= "      releaseYr = '{$releaseYr}',";
          $query .= "      purchaseDate = '{$purchaseDate}',";
          $query .= "      purchasePrice = '{$purchasePrice}'";
          $query .= "WHERE recordingID = '{$recordingId}'";

          // execute update
          if ($conn -> query($query)) {
            $errMsg = 'Record updated successfully';
            //header("refresh:2;url=index.php");
          }
        } else {
          $errMsg = '**Recording category required';
        }
      } else {
        $errMsg = '**Recording artist required';
      }
    } else {
      $errMsg = '**Recording title required';
    }
  } elseif (isset($_POST['delete'])) {

    $query = "DELETE FROM recordings";
    $query .= " WHERE recordingId = '{$recordingId}'";

    // execute delete
    if ($conn -> query($query)) {
      //header("refresh:2;url=index.php");
      $errMsg = 'Record deleted successfully';

    }
  }
}
?>
<h1>Records Database</h1>
<?php print '<span style="color: #b11117"><b>' . $errMsg . '</b></span>'; ?>
<table id="recordTable" class="drop-shadow">
  <thead>
    <tr>
      <th align="left"><a title="Create New Recording" href="createRecording.php"><img src="images\greenPlus.png" /></a></td><th>Recording Title</td> <th>Recording Artist</td> <th>Music Category</td> <th>Notes</td> <th>Record Label</td> <th>Format</td> <th># of Tracks</td> <th>Release Year</td> <th>Purchase Date</td> <th>Purchase Price</td> <th>Action</td>
    </tr>
  </thead>
  <tbody>
    <!-- create rows based on records in the db -->
    <?php
    // loop through the existing records and display them in the table
    $result = $conn -> query("SELECT * FROM recordings", MYSQLI_USE_RESULT);

    while ($row = $result -> fetch_array(MYSQLI_NUM)) {
      print "<form action=\"index.php\" method=\"post\">";
      print "<tr>";
      // hidden recordingId value
      printf("<td><input type=\"hidden\" name=\"recordingId\" value=\" %s\"/></td>", $row[0]);
      // recordingTitle value
      printf("<td><input size=\"25\" type=\"text\" name=\"recordingTitle\" value=\" %s\"/></td>", $row[1]);
      // recordingArtist value
      printf("<td><input size=\"25\" type=\"text\" name=\"recordingArtist\" value=\" %s\"/></td>", $row[2]);
      // musicCategory value
      printf("<td><input size=\"13\" type=\"text\" name=\"musicCategory\" value=\" %s\"/></td>", $row[3]);
      // notes
      printf("<td><textarea cols=\"20\" rows=\"3\" name=\"notes\">%s</textarea></td>", $row[4]);
      // recordingCompany value
      printf("<td><input size=\"23\" type=\"text\" name=\"recordingCompany\" value=\" %s\"/></td>", $row[5]);
      // format value
      printf("<td><input size=\"1\" type=\"text\" name=\"format\" value=\" %s\"/></td>", $row[6]);
      // numOfTracks value
      printf("<td><input size=\"1\" type=\"text\" name=\"numOfTracks\" value=\" %s\"/></td>", $row[7]);
      // releaseYr value
      printf("<td><input size=\"1\" type=\"text\" name=\"releaseYr\" value=\" %s\"/></td>", $row[8]);
      // purchaseDate value
      printf("<td><input size=\"7\" type=\"text\" name=\"purchaseDate\" value=\" %s\"/></td>", $row[9]);
      // purchasePrice value
      printf("<td><input size=\"1\" type=\"text\" name=\"purchasePrice\" value=\" %s\"/></td>", $row[10]);

      print "<td><input title=\"Update/Save record entry\" type=\"image\" src=\"images\\save.png\" height=\"24\" width=\"24\" name=\"update\" value=\"Update\" />";
      print "<input title=\"Delete record entry\" type=\"image\" src=\"images\\redX.png\" name=\"delete\" value=\"Delete\" /></td>";
      print "</tr>";
      print "</form>";
    }
    
    ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="12">&nbsp;</td>
    </tr>
  </tfoot>
</table>

</form>

<?php
include ('../templates/footer.html');
?>