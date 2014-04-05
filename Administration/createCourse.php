<?php
    include_once('../templates/preheader.php'); // <-- this include file MUST go first before any HTML/output
    include ('../ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output
    include ('../Lib/Session.php');
    Session::validateSession();
    include ('../templates/header.php');   
?>

<?php
$errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {

    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $courseTable = new ajaxCRUD("Item", "courses", "courseID", "../");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....
    ## full API reference material for all functions can be found here - http://ajaxcrud.com/api/
    ## note: many functions below are commented out (with //). note which ones are and which are not

    #i can define a relationship to another table
    #the 1st field is the fk in the table, the 2nd is the second table, the 3rd is the pk in the second table, the 4th is field i want to retrieve as the dropdown value
    #http://ajaxcrud.com/api/index.php?id=defineRelationship
    //$courseTable->defineRelationship("fkID", "courseTableRelationship", "pkID", "fldName", "fldSort DESC"); //use your own table - this table (courseTableRelationship) not included in the installation script

    #i don't want to visually show the primary key in the table
    $courseTable->omitPrimaryKey();

    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $courseTable->displayAs("courseName", "Course Name");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $courseTable->omitField("updateDate");
    $courseTable->omitField("createDate");

    #i could omit a field from being on the add form if I wanted
    $courseTable->omitAddField("updateDate");
    $courseTable->omitAddField("createDate");
    
    #i could disable fields from being editable
    $courseTable->disallowEdit('createDate');
    $courseTable->disallowEdit('courseName');

    #set the number of rows to display (per page)
    $courseTable->setLimit(10);

    #i can order my table by whatever i want
    $courseTable->addOrderBy("ORDER BY courseName ASC");
    
    #if really desired, a filter box can be used for all fields
    $courseTable->addAjaxFilterBoxAllFields();
    
    echo'<h2>Create a Course</h2>
        <div style="float: left">
            <p style="font-size: 12px;">Total Returned Rows: <b><?=$courseTable->insertRowsReturned();?></b></p>
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!  <a href="../Videos/UserAdminScreenRecord.avi">View Tutorial</a></h5>
        </div>

        <div style="clear:both;"></div>';
     
     #Show the table
     $courseTable->showTable();
    }
    else {
            
        $errMsg = 'Redirecting to the login page in <span id="countdown">5</span>.<br /><br />';
        print '<br /><p><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>';
        print '<div align="center"><img width="350" src="../Images/bearcat.jpg"></div>';
        header( "refresh:5;url=../Authentication/login.php" );          
    }

?>