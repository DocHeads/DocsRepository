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

<?php 
$emailAddress=$_SESSION['email'];
$errMsg='';
// email users upon becoming a valid user by the admin
$id = ($_POST['id']);
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if(isset($_POST['action']))
      {
        $update = "{$_POST['action']}";
        if ($update == "update")
        {
         Users::emailValidatedUsers($id);
        }
      }
    }

if(Session::getLoggedInUserType()=="ADMIN") {
print '<h2>Administration</h2>';
echo '<div style="padding: 0px 50px 0px 50px">';
echo '<table width="375" align="left">
                                 <tbody style="display: block; height: 320px;">
                    <tr height="320">

                        <td width="375" style="vertical-align:top;">';
echo "<h2 style='font-size: 14px'><b>".Session::getLoggedInName()."'s Submissions:</b><a title='Create New Submission Upload' href='../Submission/submissionUpload.php'><img style='padding-top: 6px; padding-right: 7px;' height='16px' width='16px' align='right' src='../Images/greenPlus.png'></a></h2>";

echo "<table class='customTable' width='350' align='center'>
                      <tr>
                         <thead align='left'>
                            <th height='16px'>Submission</th>
                            <th height='16px'><strong>Updated On</strong></th>
                            <th height='16px'><strong>Action</strong></th>
                          </thead>
                      </tr>";

                        mysql_connect(ConfigProperties::$DatabaseServerName,ConfigProperties::$DatabaseUsername,ConfigProperties::$DatabasePassword) or die (mysql_error());
                        mysql_select_db(ConfigProperties::$DatabaseName) or die (mysql_error());

                        $sql = mysql_query("SELECT subID, docName, updateDate FROM submissions WHERE emailAddress='$emailAddress' ORDER BY updateDate DESC");

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

$sql2 = mysql_query("SELECT subID, docName, updateDate FROM submissions WHERE emailAddress='$emailAddress' ORDER BY updateDate DESC $limit");


$paginationDisplay = ""; // Initialize the pagination output variable
// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
if ($lastPage != "1"){
    // This shows the user what page they are on, and the total number of pages
    $paginationDisplay .= '<strong>' . $pn . '</strong> ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
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
    $updateDate = $row["updateDate"];

    $outputList .= '<tr>'
                    . '<td height="26px">' . $docName . '</td>
                       <td height="26px">' . $updateDate . '</td>
                       <td height="26px"><a href="../Submission/submissionProfile.php?subID=' . $subID . '"
                       <td height="26px"><img width="13px" src="../Images/edit.png"></td>
                       </tr>';
                          
}

            
?>
            <?php print "$outputList"; ?>
            <tr><td bgcolor="#e7e2e0">&nbsp;</td></tr>
            <tr><td bgcolor="#e7e2e0">&nbsp;</td></tr>
            <tr><td bgcolor="#e7e2e0" colspan="3" align="center"><?php echo $paginationDisplay; ?></td></tr>
   

<?php
echo "</table>";
echo '</td>
                       </tr>
                   </tbody>        
               </table>';
echo '<table style="margin-bottom: 20px;" width="396" align="right">
                <tbody style="display: block; height: 300px;">
                     <tr height="300">
                       <td width="420" style="vertical-align:top;">';
$userTable = new ajaxCRUD("Item", "users", "userID", "../");
$userTable -> omitPrimaryKey();
#the table fields have prefixes; i want to give the heading titles something more
# meaningful
$userTable -> displayAs("emailAddress", "User Name");
$userTable -> displayAs("fname", "First Name");
$userTable -> displayAs("lname", "Last Name");
$userTable -> displayAs("userType", "User Type");
$userTable -> displayAs("isValidated", "Validated?");
$userTable -> displayAs("emailOptIn", "Email Opt-In");
#i could omit a field if I wanted
#http://ajaxcrud.com/api/index.php?id=omitField
$userTable -> omitField("emailOptIn");
$userTable -> omitField("userType");
$userTable -> omitField("password");
$userTable -> omitField("tempPassKey");
$userTable -> omitField("updateDate");
$userTable -> omitField("createDate");
#i can set certain fields to only allow certain values
#http://ajaxcrud.com/api/index.php?id=defineAllowableValues
$allowableUserTypeIDValues = array(
  "STANDARD",
  "ADMIN"
);
$userTable -> defineAllowableValues("userType", $allowableUserTypeIDValues);
$allowableisValidatedValues = array(
  "YES",
  "NO"
);
$userTable -> defineAllowableValues("isValidated", $allowableisValidatedValues);
$allowableemailOptInValues = array(
  "YES",
  "NO"
);
$userTable -> defineAllowableValues("emailOptIn", $allowableemailOptInValues);
#i could disable fields from being editable
$userTable -> disallowEdit('emailAddress');
$userTable -> disallowEdit('fname');
$userTable -> disallowEdit('lname');


#set the number of rows to display (per page)
$userTable -> setLimit(5);
#implement a callback function after updating/editing a field
$userTable -> onUpdateExecuteCallBackFunction("fname", "myCallBackFunctionForEdit");
$userTable -> onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
$userTable -> onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
$userTable -> onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
#i can order my table by whatever i want
$userTable -> addOrderBy("ORDER BY emailAddress ASC");
#i can use a where field to better-filter my table
$userTable -> addWhereClause("WHERE isValidated = 'NO'");
#i can disallow adding rows to the table
#http://ajaxcrud.com/api/index.php?id=disallowAdd
$userTable -> disallowAdd();
echo '<h2 style="font-size: 14px;"><b>Users to be Validated:</b><a href="../Administration/userAdministration.php"><img style="padding-top: 3px; padding-right: 5px;" height="23px" align="right" src="../Images/edit3.png"></a></h2>';
#actually show the table
$userTable -> showTable();
echo '</td>
                       </tr>
                   </tbody>        
               </table>';
echo '</div>';
echo '<table style="top-margin: 20px;" align="center">
                  <tbody>
                       <tr>
                         <td>';
$subTable = new ajaxCRUD("Item", "submissions", "subID", "../");
$subTable -> omitPrimaryKey();
#the table fields have prefixes; i want to give the heading titles something more
# meaningful
$subTable -> displayAs("emailAddress", "User");
$subTable -> displayAs("docName", "Submission");
$subTable -> displayAs("deptName", "Department");
$subTable -> displayAs("courseName", "Course");
$subTable -> displayAs("comments", "Comments");
$subTable -> displayAs("rubricFileName", "Rubric");
$subTable -> displayAs("willYouGrade", "Grade?");
$subTable -> displayAs("createDate", "Created On");
$subTable -> displayAs("submissionFile", "File Name");
$subTable -> displayAs("instructorInstruction", "Instructor Inst");
$subTable -> displayAs("studentInstruction", "Student Inst");
//$subTable -> displayAs("edit", "Edit");
#i could omit a field if I wanted
#http://ajaxcrud.com/api/index.php?id=omitField
$subTable -> omitField("willYouGrade");
$subTable -> omitField("updateDate");
$subTable -> omitField("comments");
$subTable -> omitField("edit");

$subTable -> disallowEdit('submissionFile');
$subTable -> disallowEdit('instructorInstruction');
$subTable -> disallowEdit('studentInstruction');
$subTable -> disallowEdit('rubricFileName');
$allowableUserTypeIDValues = Departments::getDeptList();
$subTable -> defineAllowableValues("deptName", $allowableUserTypeIDValues);
$allowableUserTypeIDValues = Courses::getCourseList();
$subTable -> defineAllowableValues("courseName", $allowableUserTypeIDValues);
#i could disable fields from being editable
$subTable -> disallowEdit('emailAddress');
$subTable -> disallowEdit('createDate');
$subTable -> disallowEdit('submissionFile');
$subTable -> disallowEdit('deptName');
#set the number of rows to display (per page)
$subTable -> setLimit(10);
#implement a callback function after updating/editing a field
$subTable -> onUpdateExecuteCallBackFunction("docName", "myCallBackFunctionForEdit");
$subTable -> onUpdateExecuteCallBackFunction("courseName", "myCallBackFunctionForEdit");
$subTable->addButtonToRow('Edit', '../Submission/submissionProfile.php', 'subID');
#i can order my table by whatever i want
$subTable -> addOrderBy("ORDER BY createDate DESC");
#if really desired, a filter box can be used for all fields
$subTable -> addAjaxFilterBoxAllFields();
#i can disallow adding rows to the table
$subTable -> disallowAdd();
$subTable -> disallowDelete();
echo '<h2 style="font-size: 14px;"><b>All User Submissions:</b><a href="../Administration/submissionAdministration.php"><img style="padding-top: 3px; padding-right: 5px;" height="23px" align="right" src="../Images/edit3.png"></a></h2>';
#actually show the table
$subTable -> showTable();
echo '</td>
                      </tr>
                  </tbody>        
               </table><br style="clear:both;" />';
} else {
$errMsg='Redirecting to the login page in <span id="countdown">5</span>.<br /><br />';
print '<br /><p><span style="color: #b11117"><b>'.$errMsg.'</b></span></p>';
print '<div align="center"><img width="350" src="../Images/bearcat.jpg"></div>';
header("refresh:5;url=../Authentication/login.php");
}
?>

<?php
function myCallBackFunctionForAdd($array)
{
}

function myCallBackFunctionForEdit($array)
{
}
?>

<?php
include ('../templates/footer.html');
?>

</table>