<?php

    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
    Session::validateSession();
    include ('../templates/header.php');
    include ('../Lib/Departments.php');
    
?>

<h2>Welcome to the UC Faculty Document Managment System</h2>

<?php

    $errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {
        header( 'Location: ../Administration/adminHome.php' ) ;           
        echo '<table align="right" border="5">
                <tbody>
                    <tr>
                        <td>';
                                $userTable = new ajaxCRUD("Item", "users", "userID", "../");
                            
                                $userTable->omitPrimaryKey();
                                
                                #the table fields have prefixes; i want to give the heading titles something more meaningful
                                $userTable->displayAs("emailAddress", "User Name");
                                $userTable->displayAs("fname", "First Name");
                                $userTable->displayAs("lname", "Last Name");
                                $userTable->displayAs("userType", "User Type");
                                $userTable->displayAs("isValidated", "Validated?");
                                $userTable->displayAs("emailOptIn", "Email Opt In");
                            
                                #i could omit a field if I wanted
                                #http://ajaxcrud.com/api/index.php?id=omitField
                                $userTable->omitField("emailOptIn");
                                $userTable->omitField("userType");
                                $userTable->omitField("password");
                                $userTable->omitField("tempPassKey");
                                $userTable->omitField("updateDate");
                                $userTable->omitField("createDate");
                            
                            // YOU LEFT OFF HERE
                             // $allowableUserTypeIDValues = array();
                                // $allowableUserTypeIDValues = Departments::getDeptList();
                                // $userTable->defineAllowableValues("userType", $allowableUserTypeIDValues);
                            
                                #i can set certain fields to only allow certain values
                                #http://ajaxcrud.com/api/index.php?id=defineAllowableValues
                                $allowableUserTypeIDValues = array("STANDARD", "ADMIN");
                                $userTable->defineAllowableValues("userType", $allowableUserTypeIDValues);
                            //     
                                $allowableisValidatedValues = array("YES", "NO");
                                $userTable->defineAllowableValues("isValidated", $allowableisValidatedValues);
                                
                                $allowableemailOptInValues = array("YES", "NO");
                                $userTable->defineAllowableValues("emailOptIn", $allowableemailOptInValues);
                                
                                #i could disable fields from being editable
                                $userTable->disallowEdit('emailAddress');
                                
                                #set the number of rows to display (per page)
                                $userTable->setLimit(3);
                            
                                #if really desired, a filter box can be used for all fields
                                //$userTable->addAjaxFilterBoxAllFields();
                            
                                #implement a callback function after updating/editing a field
                                $userTable->onUpdateExecuteCallBackFunction("fname", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
                                
                                #i can order my table by whatever i want
                                $userTable->addOrderBy("ORDER BY emailAddress ASC");
                                
                                #i can disallow adding rows to the table
                                #http://ajaxcrud.com/api/index.php?id=disallowAdd
                                $userTable->disallowAdd();
                                    
                                #actually show the table
                                $userTable->showTable();
                                
                                function myCallBackFunctionForAdd($array){
                                    // echo "THE ADD ROW CALLBACK FUNCTION WAS implemented";
                                    // print_r($array);
                                }
                            
                                function myCallBackFunctionForEdit($array){
                                    // echo "THE EDIT ROW CALLBACK FUNCTION WAS implemented";
                                    // print_r($array);
                                }
                        
                        echo '</td>
                    </tr>
                </tbody>        
        </table>
        ';
        

  if (Users::isAuthorized())
  {
    print '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';
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
    $query = "SELECT subID, docName, GetDeptName(deptID) deptName, GetCourseName(courseID) courseName, createDate, updateDate FROM submissions WHERE userID = ". Session::getLoggedInUserId() ." ORDER BY updateDate DESC";
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