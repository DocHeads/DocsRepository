<?php

    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
    Session::validateSession();
    include ('../templates/header.php');
    include ('../Lib/Departments.php');
    include ('../Lib/Courses.php');
    
?>

<?php
$emailAddress = $_SESSION['email'];
$errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {
        print'<h2>Administration</h2>';
echo '<div style="padding: 0px 20px 0px 20px">';
 echo '<table width="420" align="left" border="2">
                <tbody style="display: block; height: 300px;">
                    <tr height="300">
                        <td width="420" style="vertical-align:top;">';
                        
                        echo '<h2 style="font-size: 14px"><b>My Recent Submissions:</b></h2>';
                        
                        $con=mysqli_connect("localhost","root","","docdatabase");
                        // Check connection
                        if (mysqli_connect_errno())
                          {
                          echo "Failed to connect to MySQL: " . mysqli_connect_error();
                          }
                        
                        $result = mysqli_query($con,"SELECT * FROM submissions WHERE emailAddress = '$emailAddress' ORDER BY createDate DESC LIMIT 0,5");

                        echo "<table class='customTable' width='350' align='center'>
                        <tr>
                        <thead align='left'>
                        <th height='20px'>Submission</th>
                        <th height='20px'>File</th>
                        <th height='20px'><strong>Created On</strong></th>
                        <th height='20px'><strong>Action</strong></th>
                        </thead>
                        </tr>";
                        
                        while($row = mysqli_fetch_array($result))
                          {
                          echo "<tr>";
                          echo "<td>" . $row['docName'] . "</td>";
                          echo "<td>" . $row['submissionFile'] . "</td>";
                          echo "<td>" . $row['createDate'] . "</td>";
                          echo "<td><a href=\"../Submission/submissionProfile.php?subID=" . $row['subID'] . "\"><img width='13px' src=\"../Images/edit.png\"></a></td>";
                          echo "</tr>";
                          }
                        echo "</table>";
                        
                        mysqli_close($con);
                        
                        echo '</td>
                    </tr>
                </tbody>        
            </table>';

echo '<table style="margin-bottom: 20px;" width="396" align="right" border="2">
                <tbody style="display: block; height: 300px;">
                    <tr height="300">
                        <td width="420" style="vertical-align:top;">';
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
                            
                                #i can set certain fields to only allow certain values
                                #http://ajaxcrud.com/api/index.php?id=defineAllowableValues
                                $allowableUserTypeIDValues = array("STANDARD", "ADMIN");
                                $userTable->defineAllowableValues("userType", $allowableUserTypeIDValues);
                                    
                                $allowableisValidatedValues = array("YES", "NO");
                                $userTable->defineAllowableValues("isValidated", $allowableisValidatedValues);

                                $allowableemailOptInValues = array("YES", "NO");
                                $userTable->defineAllowableValues("emailOptIn", $allowableemailOptInValues);
                                
                                #i could disable fields from being editable
                                $userTable->disallowEdit('emailAddress');
                                $userTable->disallowEdit('fname');
                                $userTable->disallowEdit('lname');
                                
                                
                                #set the number of rows to display (per page)
                                $userTable->setLimit(3);
                            
                                #implement a callback function after updating/editing a field
                                $userTable->onUpdateExecuteCallBackFunction("fname", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
                                $userTable->onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
                                
                                #i can order my table by whatever i want
                                $userTable->addOrderBy("ORDER BY emailAddress ASC");
                                
                                #i can use a where field to better-filter my table
                                $userTable->addWhereClause("WHERE isValidated = 'NO'");
                                
                                #i can disallow adding rows to the table
                                #http://ajaxcrud.com/api/index.php?id=disallowAdd
                                $userTable->disallowAdd();
                                echo '<h2 style="font-size: 14px;"><b>Users to be Validated:</b></h2>';
                                #actually show the table
                                $userTable->showTable();
                        
                        echo '</td>
                    </tr>
                </tbody>        
            </table>';
    echo '</div>';
            
            echo '<table style="top-margin: 20px;" align="center" border="2">
                <tbody>
                    <tr>
                        <td>';
                                $subTable = new ajaxCRUD("Item", "submissions", "subID", "../");
                            
                                $subTable->omitPrimaryKey();
                                
                                #the table fields have prefixes; i want to give the heading titles something more meaningful
                                $subTable->displayAs("emailAddress", "User Name");
                                $subTable->displayAs("docName", "Submission");
                                $subTable->displayAs("deptName", "Department");
                                $subTable->displayAs("courseName", "Course");
                                $subTable->displayAs("comments", "Comments");
                                $subTable->displayAs("rubricFileName", "Grading Rubric");
                                $subTable->displayAs("willYouGrade", "Grade?");
                                $subTable->displayAs("createDate", "Created On");                                
                                $subTable->displayAs("submissionFile", "File Name"); 
                                $subTable->displayAs("instructorInstruction", "Instructor Inst");
                                $subTable->displayAs("studentInstruction", "Student Inst");

                                #i could omit a field if I wanted
                                #http://ajaxcrud.com/api/index.php?id=omitField
                                $subTable->omitField("willYouGrade");
                                $subTable->omitField("updateDate");
                                $subTable->omitField("comments");   
                                $subTable->omitField("rubricFileName");
                                $subTable->omitField("instructorInstruction");
                                $subTable->omitField("studentInstruction");
                                

                                
                                $allowableUserTypeIDValues = Departments::getDeptList();
                                $subTable->defineAllowableValues("deptName", $allowableUserTypeIDValues);
    
                                $allowableUserTypeIDValues = Courses::getCourseList();
                                $subTable->defineAllowableValues("courseName", $allowableUserTypeIDValues);
                                                            
                                #i could disable fields from being editable
                                $subTable->disallowEdit('emailAddress');
                                $subTable->disallowEdit('createDate');
                                $subTable->disallowEdit('submissionFile');
                                
                                
                                #set the number of rows to display (per page)
                                $subTable->setLimit(10);
                            
                                #implement a callback function after updating/editing a field
                                $subTable->onUpdateExecuteCallBackFunction("docName", "myCallBackFunctionForEdit");
                                
                                #i can order my table by whatever i want
                                $subTable->addOrderBy("ORDER BY emailAddress ASC");
                                
                                #if really desired, a filter box can be used for all fields
                                $subTable->addAjaxFilterBoxAllFields();
                            
                                #i can disallow adding rows to the table
                                #http://ajaxcrud.com/api/index.php?id=disallowAdd
                                $subTable->disallowAdd();
                                echo '<h2 style="font-size: 14px;"><b>User Submissions:</b></h2>';
                                #actually show the table
                                $subTable->showTable();
                        
                        echo '</td>
                    </tr>
                </tbody>        
            </table><br style="clear:both;" />';
    }
    else {
            
        $errMsg = 'Redirecting to the login page in <span id="countdown">5</span>.<br /><br />';
        print '<br /><p><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>';
        print '<div align="center"><img width="350" src="../Images/bearcat.jpg"></div>';
        header( "refresh:5;url=../Authentication/login.php" );          
    }
?>

<?php

    function myCallBackFunctionForAdd($array){
                                        
    }
                                
    function myCallBackFunctionForEdit($array){
    
    }
                                
?>

<?php
include ('../templates/footer.html');
?>

</table>'; -->