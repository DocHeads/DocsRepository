<?php
include_once ('../templates/preheader.php');
// <-- this include file MUST go first before any HTML/output
include ('../ajaxCRUD.class.php');
// <-- this include file MUST go first before any HTML/output
include ('../Lib/Session.php');
Session::validateSession();
include ('../templates/header.php');
include ('../Lib/Departments.php');
include ('../Lib/Courses.php');
?>

<h2>Welcome to the UC Faculty Document Managment System</h2>

<?php
if (Users::isAuthorized())
{
    $emailAddress = $_SESSION['email'];
    $errMsg = '';
  if (Session::getLoggedInUserType() == "ADMIN")
  {
    header('Location: ../Administration/adminHome.php');
  }
  else
      {
     
 echo '<div style="padding: 0px 20px 20px 20px">';
 
echo '<table width="800" align="center">
                <tbody style="display: block; height: 300px;">
                    <tr height="300">
                        <td style="vertical-align:top;">';

                        echo "<h2 style='font-size: 14px'><b>". Session::getLoggedInName() . "'s Submissions:</b></h2>";
                                                
echo "<table width='920' class='customTable' align='center'>
                        <tr>
                        <thead align='left'>
                        <th height='20px'>Submission Name</th>
                        <th height='20px'>Department</th>
                        <th height='20px'>Course</th>
                        <th height='20px'>Instructor Inst</th>
                        <th height='20px'>Student Inst</th>    
                        <th height='20px'>Rubric</th>                        
                        <th height='20px'><strong>Created On</strong></th>
                        <th height='20px'>Download</th>
                        <th height='20px'><strong>Action</strong></th>
                        </thead>
                        </tr>";
                        
                        mysql_connect(ConfigProperties::$DatabaseServerName,ConfigProperties::$DatabaseUsername,ConfigProperties::$DatabasePassword) or die (mysql_error());
                        mysql_select_db(ConfigProperties::$DatabaseName) or die (mysql_error());

                        $sql = mysql_query("SELECT subID, submissionFile, rubricFileName, deptName, courseName, instructorInstruction, studentInstruction, docName, createDate FROM submissions ORDER BY createDate DESC");
                        
                        
                        
                        $nr = mysql_num_rows($sql); // Get total of Num rows from the database query
if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
    $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    //$pn = ereg_replace("[^0-9]", "", $_GET['pn']); // filter everything but numbers for security(deprecated)
} else { // If the pn URL variable is not present force it to be value of page number 1
    $pn = 1;
}
//This is where we set how many database items to show on each page
$itemsPerPage = 5;
// Get the value of the last page in the pagination result set
$lastPage = ceil($nr / $itemsPerPage);
// Be sure URL variable $pn(page number) is no lower than page 1 and no higher than $lastpage
if ($pn < 1) { // If it is less than 1
    $pn = 1; // force if to be 1
} else if ($pn > $lastPage) { // if it is greater than $lastpage
    $pn = $lastPage; // force it to be $lastpage's value
}
// This creates the numbers to click in between the next and back buttons
// This section is explained well in the video that accompanies this script
$centerPages = "";
$sub1 = $pn - 1;
$sub2 = $pn - 2;
$add1 = $pn + 1;
$add2 = $pn + 2;
if ($pn == 1) {
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
} else if ($pn == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
} else if ($pn > 2 && $pn < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add2 . '">' . $add2 . '</a> &nbsp;';
} else if ($pn > 1 && $pn < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}
// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query
$limit = 'LIMIT ' .($pn - 1) * $itemsPerPage .',' .$itemsPerPage;
// Now we are going to run the same query as above but this time add $limit onto the end of the SQL syntax
// $sql2 is what we will use to fuel our while loop statement below

$sql2 = mysql_query("SELECT subID, submissionFile, deptName, rubricFileName, courseName, instructorInstruction, studentInstruction, docName, createDate FROM submissions WHERE emailAddress='$emailAddress' ORDER BY subID ASC $limit");

$paginationDisplay = ""; // Initialize the pagination output variable
// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
if ($lastPage != "1"){
    // This shows the user what page they are on, and the total number of pages
    //$paginationDisplay .= '<strong>' . $pn . '</strong> ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
    // If we are not on page 1 we can place the Back button
    if ($pn != 1) {
        $previous = $pn - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '"> <<</a> ';
    }
    // Lay in the clickable numbers display here between the Back and Next links
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    // If we are not on the very last page we can place the Next button
    if ($pn != $lastPage) {
        $nextPage = $pn + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $nextPage . '"> >></a> ';
    }
}

$outputList = '';

while($row = mysql_fetch_array($sql2)){

    $subID = $row["subID"];
    $docName = $row["docName"];
    $fileName = $row['submissionFile'];
    $deptName = $row['deptName'];
    $courseName = $row['courseName'];
    $instInst = $row['instructorInstruction'];
    $studInst = $row['studentInstruction'];
    $createDate = $row['createDate'];
    $rubricName = $row['rubricFileName'];
    

    $outputList .= '<tr>'
                    . '<td height="30px">' . $docName . '</td>
                       <td height="30px">' . $fileName . '</td>
                       <td height="30px">' . $deptName . '</td>
                       <td height="30px">' . $courseName . '</td>
                       <td height="30px">' . $instInst . '</td>
                       <td height="30px">' . $studInst . '</td>
                       <td height="30px">' . $rubricName . '</td>
                       <td height="30px">' . $createDate . '</td>
                       
                       <td height="30px"><a href="../Submission/submissionProfile.php?subID=' . $subID . '"
                       <td height="30px"><img width="13px" src="../Images/edit.png"></td>
                       </tr>';

}
            
?>
         <?php print "$outputList"; ?>
         <tr><td bgcolor="#e7e2e0">&nbsp;</td></tr>
         <tr><td bgcolor="#e7e2e0" colspan="8" align="center"><?php echo $paginationDisplay; ?></td></tr>
         <tr><td bgcolor="#e7e2e0">&nbsp;</td></tr>
         
   
<?php
echo "</table>";
echo '</td>
                    </tr>
                </tbody>        
            </table>';
echo '</div>';
echo '<div style="padding: 0px 10px 0px 10px">';
echo '<table width="860" align="center">
                <tbody>
                    <tr>
                        <td>';
$subTable = new ajaxCRUD("Item", "submissions", "subID", "../");
$subTable -> omitPrimaryKey();
#the table fields have prefixes; i want to give the heading titles something more
# meaningful
$subTable -> displayAs("emailAddress", "User Name");
$subTable -> displayAs("docName", "Submission");
$subTable -> displayAs("submissionFile", "Download");
$subTable -> displayAs("deptName", "Department");
$subTable -> displayAs("courseName", "Course");
$subTable -> displayAs("comments", "Comments");
$subTable -> displayAs("rubricFileName", "Grading Rubric");
$subTable -> displayAs("willYouGrade", "Grade?");
$subTable -> displayAs("createDate", "Created On");
$subTable -> displayAs("instructorInstruction", "Instructor Inst");
$subTable -> displayAs("studentInstruction", "Student Inst");
#i could omit a field if I wanted
#http://ajaxcrud.com/api/index.php?id=omitField
$subTable -> omitField("willYouGrade");
$subTable -> omitField("updateDate");
$subTable -> omitField("comments");
$subTable->omitField("edit");
#i could disable fields from being editable
$subTable -> disallowEdit('emailAddress');
$subTable -> disallowEdit('createDate');
$subTable -> disallowEdit('deptName');
$subTable -> disallowEdit('courseName');
$subTable -> disallowEdit('submissionFile');
$subTable -> disallowEdit('instructorInstruction');
$subTable -> disallowEdit('studentInstruction');
$subTable->addButtonToRow('View', '../Submission/submissionProfile.php', 'subID');

$allowableUserTypeIDValues = Departments::getDeptList();
$subTable -> defineAllowableValues("deptName", $allowableUserTypeIDValues);
$allowableUserTypeIDValues = Courses::getCourseList();
$subTable -> defineAllowableValues("courseName", $allowableUserTypeIDValues);

#set the number of rows to display (per page)
$subTable -> setLimit(10);
#i can order my table by whatever i want
$subTable -> addOrderBy("ORDER BY emailAddress ASC");
#if really desired, a filter box can be used for all fields
$subTable -> addAjaxFilterBoxAllFields();
#i can disallow deleting of rows from the table
#http://ajaxcrud.com/api/index.php?id=disallowDelete
$subTable -> disallowDelete();
#i can disallow adding rows to the table
#http://ajaxcrud.com/api/index.php?id=disallowAdd
$subTable -> disallowAdd();
echo '<h2 style="font-size: 14px;"><b>All User Submissions:</b></h2>';
#actually show the table
$subTable -> showTable();
echo '</td>
                    </tr>
                </tbody>        
            </table></div><br style="clear:both;" />';
}
}
else
{
print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator for more information.</p>';
}
?>