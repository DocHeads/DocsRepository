<?php
include '../Lib/Session.php';
Session::validateSession();
include ('../templates/header.php');
?>

<h2>Welcome to the (Department Name) Submission Database!</h2>

<?php
  if (Users::isAuthorized() == TRUE)
  {
    print '<h1>Records Database</h1>';
    print '<span style="color: #b11117"><b>' . $errMsg . '</b></span>';
    print '<table id="recordTable" class="drop-shadow">';
    print '<thead>';
    print '<tr>';
    print '<th align="left"><a title="Create New Submission" href="../Submissions/submissionUpload.php"><img src="images\greenPlus.png" /></a></th><th>Recording Title</th> <th>Name</th> <th>Department</th> <th>Couse Assigned</th> <th>Created On</th> <th>Last Update</th> <th># of Tracks</th><th>Action</th>';
    print '</tr>';
    print '</thead>';
    print '<tbody>';
    // create rows based on records in the db
    // loop through the existing records and display them in the table
    $result = Submission::getUserSubmissions(Session::getLoggedInUserEmail());

    while ($row = $result -> fetch_array(MYSQLI_NUM))
    {
      print "<form action=\"index.php\" method=\"post\">";
      print "<tr>";
      // hidden recordingId value
      printf("<td>%s</td>", $row[0]);
      // recordingTitle value
      printf("<td>%s</td>", $row[1]);
      // recordingArtist value
      printf("<td>%s</td>", $row[2]);
      // musicCategory value
      printf("<td><td>%s</td>", $row[3]);
      // notes
      printf("<td>%s</td>", $row[4]);
      // recordingCompany value
      printf("<td><td>%s</td></td>", $row[5]);

      print "<td><input title=\"Update/Save record entry\" type=\"image\" src=\"images\\save.png\" height=\"24\" width=\"24\" name=\"update\" value=\"Update\" />";
      print "<input title=\"Delete record entry\" type=\"image\" src=\"images\\redX.png\" name=\"delete\" value=\"Delete\" /></td>";
      print "</tr>";
      print "</form>";
    }

    print '</tbody>';
    print '<tfoot>';
    print '<tr>';
    print '<td colspan="12">&nbsp;</td>';
    print '</tr>';
    print '</tfoot>';
    print '</table>';
  }

  else
  {
    print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator to light a fire in his ass and get it done sooner.</p>';
  }
?>

<?php
include ('../templates/footer.html');
?>