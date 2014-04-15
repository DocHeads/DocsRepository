<?php

include_once ('../templates/preheader.php');
// <-- this include file MUST go first before any HTML/output
include ('../ajaxCRUD.class.php');
// <-- this include file MUST go first before any HTML/output
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
    $deptTable = new ajaxCRUD("Item", "departments", "deptID", "../");

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
    
    $dt = new DateTime();
    $date = $dt->format('Y-m-d H:i:s');
    
    $deptTable->addValueOnInsert("createDate", "$date");
    $deptTable->addValueOnInsert("updateDate", "$date");
    
    $deptTable->onUpdateExecuteCallBackFunction("deptName", "myCallBackFunctionForEdit");
    
    //$deptTable->onAddExecuteCallBackFunction("myCallBackFunctionForAdd"); //uncomment this to try out an ADD ROW callback function
    
    #set the number of rows to display (per page)
    $deptTable->setLimit(10);

    #i can order my table by whatever i want
    $deptTable->addOrderBy("ORDER BY createDate DESC");

    #if really desired, a filter box can be used for all fields
    $deptTable->addAjaxFilterBoxAllFields();
    
    echo '<h2>Department Administration</h2>
            <div style="float: left">
                <p style="font-size: 12px;">Total Returned Rows: <b>'; 
             
             ?>
                <?=$deptTable -> insertRowsReturned(); ?>
             
             <?php

              echo '</b></p>
            <h5 style="font-size: 12px; color:red;">Use the dropdowns or text fields below to search the database!  <a href="../Videos/UserAdminScreenRecord.avi">View Tutorial</a></h5>
        </div>

        <div style="clear:both;"></div>';

              }
              else {

              $errMsg = 'Redirecting to the login page in <span id="countdown">5</span>.<br /><br />';
              print '<br /><p><span style="color: #b11117"><b>' . $errMsg . '</b></span></p>';
              print '<div align="center"><img width="350" src="../Images/bearcat.png"></div>';
              header( "refresh:5;url=../Authentication/login.php" );
              }
            ?>

<?php
#actually show the table
$deptTable -> showTable();

function myCallBackFunctionForAdd($array)
{
  echo "THE ADD ROW CALLBACK FUNCTION WAS implemented";
  print_r($array);
}

function myCallBackFunctionForEdit($array)
{
  // echo "THE EDIT ROW CALLBACK FUNCTION WAS implemented";
  // print_r($array);
}
?>
