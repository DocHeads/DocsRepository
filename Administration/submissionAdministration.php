<?php

    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
//    Session::validateSession();
    include ('../templates/header.php');
    include ('../Lib/Departments.php');
    
?>

<?php
    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $userTable = new ajaxCRUD("Item", "submissions", "subID", "../");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....
    ## full API reference material for all functions can be found here - http://ajaxcrud.com/api/
    ## note: many functions below are commented out (with //). note which ones are and which are not

    #i can define a relationship to another table
    #the 1st field is the fk in the table, the 2nd is the second table, the 3rd is the pk in the second table, the 4th is field i want to retrieve as the dropdown value
    #http://ajaxcrud.com/api/index.php?id=defineRelationship
    //$userTable->defineRelationship("fkID", "userTableRelationship", "pkID", "fldName", "fldSort DESC"); //use your own table - this table (userTableRelationship) not included in the installation script

    #i don't want to visually show the primary key in the table
    $userTable->omitPrimaryKey();
    
    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $userTable->displayAs("emailAddress", "User Name");
    $userTable->displayAs("deptName", "Department");
    $userTable->displayAs("courseName", "Course");
    $userTable->displayAs("docName", "Document Name");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $userTable->omitField("updateDate");
    $userTable->omitField("createDate");
    $userTable->omitField("willYouGrade");
    $userTable->omitField("rubricFileName");
    $userTable->omitField("studentInstruction");
    $userTable->omitField("instructorInstruction");
    $userTable->omitField("comments");

// YOU LEFT OFF HERE
 // $allowableUserTypeIDValues = array();
    $allowableUserTypeIDValues = Departments::getDeptList();
    $userTable->defineAllowableValues("deptName", $allowableUserTypeIDValues);

    #i can set certain fields to only allow certain values
    #http://ajaxcrud.com/api/index.php?id=defineAllowableValues
    // $allowableUserTypeIDValues = array("STANDARD", "ADMIN");
    // $userTable->defineAllowableValues("userType", $allowableUserTypeIDValues);
// //     
    // $allowableisValidatedValues = array("YES", "NO");
    // $userTable->defineAllowableValues("isValidated", $allowableisValidatedValues);
//     
    // $allowableemailOptInValues = array("YES", "NO");
    // $userTable->defineAllowableValues("emailOptIn", $allowableemailOptInValues);
    
    #i could disable fields from being editable
    $userTable->disallowEdit('emailAddress');

    #set the number of rows to display (per page)
    $userTable->setLimit(5);

    #if really desired, a filter box can be used for all fields
    $userTable->addAjaxFilterBoxAllFields();

    #implement a callback function after updating/editing a field
    $userTable->onUpdateExecuteCallBackFunction("deptName", "myCallBackFunctionForEdit");
    // $userTable->onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
    // $userTable->onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
    // $userTable->onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
    // $userTable->onUpdateExecuteCallBackFunction("User Type", "myCallBackFunctionForEdit");
    
    #i can order my table by whatever i want
    $userTable->addOrderBy("ORDER BY emailAddress ASC");
    
    #i can disallow adding rows to the table
    #http://ajaxcrud.com/api/index.php?id=disallowAdd
    $userTable->disallowAdd();

?>
    <h2>Submission Administration</h2>
        <div style="float: left">
            Total Returned Rows: <b><?=$userTable->insertRowsReturned();?></b>
            <br />
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!</h5>
        </div>

        <div style="clear:both;"></div>

<?php

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
?>