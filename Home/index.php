<?php

include_once ('../templates/preheader.php');
// <-- this include file MUST go first before any HTML/output
include ('../ajaxCRUD.class.php');
// <-- this include file MUST go first before any HTML/output
include ('../Lib/Session.php');
Session::validateSession();
include ('../templates/header.php');
include ('../Lib/Departments.php');
?>

<h2>Welcome to the UC Faculty Document Managment System</h2>

<?php
if (Users::isAuthorized())
{
  $errMsg = '';
  if (Session::getLoggedInUserType() == "ADMIN")
  {
    header('Location: ../Administration/adminHome.php');
  }
  else
      {
        print '<h1>User Submissions</h1>';
        print '<span style="color: #b11117"><b>' . $errMsg . '</b></span>';
        print '<table id="recordTable" class="drop-shadow">';
        print '<thead>';
        print '<tr>';
        print '<th align="left"><a title="Create New Submission" href="../Submission/submissionUpload.php"><img src="..\Images\greenPlus.png" /></a><th>Name</th><th>Department</th><th>Course Assigned</th><th>Created On</th><th>Last Update</th><th>Action</th>';
        print '</tr>';
        print '</thead>';
        print '<tbody>';
        // create rows based on records in the db
        // loop through the existing records and display them in the table
        $conn = new mysqli('localhost', 'root', '', 'docdatabase');
        $query = "SELECT subID, docName, GetDeptName(deptID) deptName, GetCourseName(courseID) courseName, createDate, updateDate FROM submissions WHERE userID = " . Session::getLoggedInUserId() . " ORDER BY updateDate DESC";
        $result = $conn -> query($query);
    
        while ($row = $result -> fetch_array(MYSQLI_ASSOC))
        {
          //print "<form action=\"index.php\" method=\"post\">";
          print "<tr>";
          // hidden recordingId value
          printf("<td>%s</td>", $row['subID']);
          // recordingTitle value
          printf("<td>%s</td>", $row['docName']);
          // recordingArtist value
          printf("<td>%s</td>", $row['deptName']);
          // musicCategory value
          printf("<td><td>%s</td>", $row['courseName']);
          // notes
          printf("<td>%s</td>", $row['createDate']);
          // recordingCompany value
          printf("<td>%s</td>", $row['updateDate']);
    
          print "<td><input title=\"Update/Save record entry\" type=\"image\" src=\"..\\Images\\save.png\" height=\"24\" width=\"24\" name=\"update\" value=\"Update\" />";
          print "<input title=\"Delete record entry\" type=\"image\" src=\"..\\Images\\redX.png\" name=\"delete\" value=\"Delete\" /></td>";
          print "</tr>";
          //print "</form>";
        }
    
        print '</tbody>';
        print '<tfoot>';
        print '<tr>';
        print '<td colspan="7">&nbsp;</td>';
        print '</tr>';
        print '</tfoot>';
        print '</table>';
      }
    }
    else
    {
      print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator for more information.</p>';
    }
?>

<?php
include ('../templates/footer.html');
?>