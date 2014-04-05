<?php
    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
    Session::validateSession();
    include ('../templates/header.php');
?>

<?php
    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $deptTable = new ajaxCRUD("Item", "departments", "deptID", "../");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....
    ## full API reference material for all functions can be found here - http://ajaxcrud.com/api/
    ## note: many functions below are commented out (with //). note which ones are and which are not

    #i can define a relationship to another table
    #the 1st field is the fk in the table, the 2nd is the second table, the 3rd is the pk in the second table, the 4th is field i want to retrieve as the dropdown value
    #http://ajaxcrud.com/api/index.php?id=defineRelationship
    //$deptTable->defineRelationship("fkID", "deptTableRelationship", "pkID", "fldName", "fldSort DESC"); //use your own table - this table (deptTableRelationship) not included in the installation script

    #i don't want to visually show the primary key in the table
    $deptTable->omitPrimaryKey();

    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $deptTable->displayAs("deptName", "Department Name");
    $deptTable->displayAs("createDate", "Date Created");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $deptTable->omitField("updateDate");
    
    #i could omit a field from being on the add form if I wanted
    $deptTable->omitAddField("updateDate");
    $deptTable->omitAddField("createDate");
    
    $deptTable->disallowEdit('createDate');
    $deptTable->disallowEdit('deptName');

    #set the number of rows to display (per page)
    $deptTable->setLimit(10);

    #i can order my table by whatever i want
    $deptTable->addOrderBy("ORDER BY createDate DESC");

    #if really desired, a filter box can be used for all fields
    $deptTable->addAjaxFilterBoxAllFields();


?>
    <h2>Create a Department</h2>
        <div style="float: left">
            <p style="font-size: 12px;">Total Returned Rows: <b><?=$deptTable->insertRowsReturned();?></b></p>
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!  <a href="../Videos/UserAdminScreenRecord.avi">View Tutorial</a></h5>
        </div>

        <div style="clear:both;"></div>

<?php
    #actually show the table
    $deptTable->showTable();

    #create folder for added department
    function makeDir($val){
        if (!file_exists('../uploads/'. $val)) {
            mkdir('../uploads/'. $val, 0777, true);
        }
    }
?>