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

<?php
    $errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {
    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $userTable = new ajaxCRUD("Item", "users", "userID", "../");

    ##
    ########################################################

    #i don't want to visually show the primary key in the table
    $userTable->omitPrimaryKey();
    
    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $userTable->displayAs("emailAddress", "User Name");
    $userTable->displayAs("fname", "First Name");
    $userTable->displayAs("lname", "Last Name");
    $userTable->displayAs("userType", "User Type");
    $userTable->displayAs("isValidated", "Validated?");
    $userTable->displayAs("emailOptIn", "Email Opt In");
    $userTable->displayAs("createDate", "Created On");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $userTable->omitField("password");
    $userTable->omitField("tempPassKey");
    $userTable->omitField("updateDate");

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
    $userTable->disallowEdit('createDate');
    
    #set the number of rows to display (per page)
    $userTable->setLimit(5);

    #if really desired, a filter box can be used for all fields
    $userTable->addAjaxFilterBoxAllFields();

    #implement a callback function after updating/editing a field
    $userTable->onUpdateExecuteCallBackFunction("fname", "myCallBackFunctionForEdit");
    $userTable->onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
    $userTable->onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
    $userTable->onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
    $userTable->onUpdateExecuteCallBackFunction("userType", "myCallBackFunctionForEdit");
    
    
    #i can order my table by whatever i want
    $userTable->addOrderBy("ORDER BY emailAddress ASC");
    
    #i can disallow adding rows to the table
    #http://ajaxcrud.com/api/index.php?id=disallowAdd
    $userTable->disallowAdd();

    echo '<h2>User Administration</h2>
            <div style="float: left">
                <p style="font-size: 12px;">Total Returned Rows: <b>'; 
             
             ?>
                <?=$userTable -> insertRowsReturned(); ?>
             
             <?php

              echo '</b></p>
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!  <a href="../Videos/UserAdminScreenRecord.avi">View Tutorial</a></h5>
        </div>

        <div style="clear:both;"></div>';

              }
              else {

              $errMsg = 'Redirecting to the login page in <span id="countdown">5</span>.<br /><br />';
              print '<br /><p><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>';
              print '<div align="center"><img width="350" src="../Images/bearcat.jpg"></div>';
              header( "refresh:5;url=../Authentication/login.php" );
              }
            ?>

<?php
#actually show the table
$userTable -> showTable();

function myCallBackFunctionForAdd($array)
{
  // echo "THE ADD ROW CALLBACK FUNCTION WAS implemented";
  // print_r($array);
}

function myCallBackFunctionForEdit($array)
{
  // echo "THE EDIT ROW CALLBACK FUNCTION WAS implemented";
  // print_r($array);
}
?>