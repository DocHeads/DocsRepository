<?php

    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
    Session::validateSession();
    include ('../templates/header.php');
    include ('../Lib/Departments.php');
    
?>

<?php
$errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {
        print'<div style="height: 350px;">';
        print'<h2>Administration</h2>
<table align="left">
    <tbody>
        <tr>
            <td colspan="2"><h5>User Administration</h5></td>
        </tr>
        
        <tr>
            <td><h6>Manage Users</h6></td>
            <td><h6><a href="userAdministration.php">Manage</a></h6></td>
        </tr>
        <tr>
            <td colspan="2"><h5>Submission Administration</h5></td>
        </tr>
        
        <tr>
            <td><h6>Manage Submissions</h6></td>
            <td><h6><a href="submissionAdministration.php">Manage</a></h6></td>
        </tr>
        
        <tr>
            <td><h6>Create a Department</h6></td>
            <td><h6><a href="createDept.php">Create</a></h6></td>
        </tr>

        <tr>
            <td><h6>Create a Course</h6></td>
            <td><h6><a href="createCourse.php">Create</a></h6></td>
        </tr>
    </tbody>

</table>';

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