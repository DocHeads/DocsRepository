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
    
    $errMsg = '';
    if(Session::getLoggedInUserType()== "ADMIN") {
    #the code for the class
    
    #this one line of code is how you implement the class
    ########################################################
    ##

    $subTable = new ajaxCRUD("Item", "submissions", "subID", "../");

    ##
    ########################################################

    #i don't want to visually show the primary key in the table
    $subTable->omitPrimaryKey();
    
    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $subTable->displayAs("emailAddress", "User Name");
    $subTable->displayAs("docName", "Submission");
    $subTable->displayAs("deptName", "Department");
    $subTable->displayAs("courseName", "Course");
    $subTable->displayAs("comments", "Comments");
    $subTable->displayAs("rubricFileName", "Rubric");
    $subTable->displayAs("willYouGrade", "Will You Grade?");
    $subTable->displayAs("createDate", "Created On");
    $subTable->displayAs("submissionFile", "File Name");
    $subTable->displayAs("instructorInstruction", "Instructor Inst");
    $subTable->displayAs("studentInstruction", "Student Inst");

    #i could omit a field if I wanted
    #http://ajaxcrud.com/api/index.php?id=omitField
    $subTable->omitField("updateDate");
    $subTable->omitField("willYouGrade");
    $subTable->omitField("comments");
    $subTable->omitField("edit");
    
    $allowableUserTypeIDValues = Departments::getDeptList();
    $subTable->defineAllowableValues("deptName", $allowableUserTypeIDValues);

    $allowableUserTypeIDValues = Courses::getCourseList();
    $subTable->defineAllowableValues("courseName", $allowableUserTypeIDValues);
    
    $subTable->addButtonToRow('Edit', '../Submission/submissionProfile.php', 'subID');
    
    #i could disable fields from being editable
    $subTable->disallowEdit('emailAddress');
    $subTable->disallowEdit('submissionFile');
    $subTable->disallowEdit('rubricFileName');
    $subTable->disallowEdit('studentInstruction');
    $subTable->disallowEdit('instructorInstruction');
    $subTable->disallowEdit('docName');
    $subTable->disallowEdit('deptName');
    
    #set the number of rows to display (per page)
    $subTable->setLimit(5);

    #if really desired, a filter box can be used for all fields
    $subTable->addAjaxFilterBoxAllFields();

    #implement a callback function after updating/editing a field
    $subTable->onUpdateExecuteCallBackFunction("courseName", "myCallBackFunctionForEdit");
    $subTable->onUpdateExecuteCallBackFunction("deptName", "myCallBackFunctionForEdit");
    
    #i can order my table by whatever i want
    $subTable->addOrderBy("ORDER BY emailAddress ASC");
    
    #i can disallow adding rows to the table
    #http://ajaxcrud.com/api/index.php?id=disallowAdd
    $subTable->disallowAdd();
    
    echo '<h2>Submission Administration</h2>
            <div style="float: left">
                <p style="font-size: 12px;">Total Returned Rows: <b>'; 
             
             ?>
                <?=$subTable->insertRowsReturned();?>
             
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
