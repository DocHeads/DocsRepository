<?php

    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
//    Session::validateSession();
    include ('../templates/header.php');
    include ('../Lib/Departments.php');
    include ('../Lib/Courses.php');
    
?>

<?php
    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $subTable = new ajaxCRUD("Item", "submissions", "subID", "../");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....
    ## full API reference material for all functions can be found here - http://ajaxcrud.com/api/
    ## note: many functions below are commented out (with //). note which ones are and which are not

    #i can define a relationship to another table
    #the 1st field is the fk in the table, the 2nd is the second table, the 3rd is the pk in the second table, the 4th is field i want to retrieve as the dropdown value
    #http://ajaxcrud.com/api/index.php?id=defineRelationship
    //$subTable->defineRelationship("fkID", "subTableRelationship", "pkID", "fldName", "fldSort DESC"); //use your own table - this table (subTableRelationship) not included in the installation script

    #i don't want to visually show the primary key in the table
    $subTable->omitPrimaryKey();
    
    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $subTable->displayAs("emailAddress", "User Name");
    $subTable->displayAs("deptName", "Department");
    $subTable->displayAs("courseName", "Course");
    $subTable->displayAs("docName", "Document Name");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $subTable->omitField("updateDate");
    $subTable->omitField("createDate");
    $subTable->omitField("willYouGrade");
    $subTable->omitField("rubricFileName");
    $subTable->omitField("studentInstruction");
    $subTable->omitField("instructorInstruction");
    $subTable->omitField("comments");


    $allowableUserTypeIDValues = Departments::getDeptList();
    $subTable->defineAllowableValues("deptName", $allowableUserTypeIDValues);
    
    $allowableUserTypeIDValues = Courses::getCourseList();
    $subTable->defineAllowableValues("courseName", $allowableUserTypeIDValues);

    #i can set certain fields to only allow certain values
    #http://ajaxcrud.com/api/index.php?id=defineAllowableValues
    // $allowableUserTypeIDValues = array("STANDARD", "ADMIN");
    // $subTable->defineAllowableValues("userType", $allowableUserTypeIDValues);
  
    // $allowableisValidatedValues = array("YES", "NO");
    // $subTable->defineAllowableValues("isValidated", $allowableisValidatedValues);
   
    // $allowableemailOptInValues = array("YES", "NO");
    // $subTable->defineAllowableValues("emailOptIn", $allowableemailOptInValues);
    
    #i could disable fields from being editable
    $subTable->disallowEdit('emailAddress');

    #set the number of rows to display (per page)
    $subTable->setLimit(5);

    #if really desired, a filter box can be used for all fields
    $subTable->addAjaxFilterBoxAllFields();

    #implement a callback function after updating/editing a field
    $subTable->onUpdateExecuteCallBackFunction("deptName", "myCallBackFunctionForEdit");
    // $subTable->onUpdateExecuteCallBackFunction("lname", "myCallBackFunctionForEdit");
    // $subTable->onUpdateExecuteCallBackFunction("isValidated", "myCallBackFunctionForEdit");
    // $subTable->onUpdateExecuteCallBackFunction("emailOptIn", "myCallBackFunctionForEdit");
    // $subTable->onUpdateExecuteCallBackFunction("User Type", "myCallBackFunctionForEdit");
    
    #i can order my table by whatever i want
    $subTable->addOrderBy("ORDER BY emailAddress ASC");
    
    #i can disallow adding rows to the table
    #http://ajaxcrud.com/api/index.php?id=disallowAdd
    $subTable->disallowAdd();

?>
    <h2>Submission Administration</h2>
        <div style="float: left">
            <p style="font-size: 12px;">Total Returned Rows: <b><?=$subTable->insertRowsReturned();?></b></p>
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!  <a href="../Videos/UserAdminScreenRecord.avi">View Tutorial</a></h5>
        </div>

        <div style="clear:both;"></div>

<?php

    #actually show the table
    $subTable->showTable();


    function myCallBackFunctionForAdd($array){
        // echo "THE ADD ROW CALLBACK FUNCTION WAS implemented";
        // print_r($array);
    }

    function myCallBackFunctionForEdit($array){
        // echo "THE EDIT ROW CALLBACK FUNCTION WAS implemented";
        // print_r($array);
    }
?>