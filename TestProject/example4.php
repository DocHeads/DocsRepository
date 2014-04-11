<?php

	require_once('../TestProject/preheader.php'); // <-- this include file MUST go first before any HTML/output

	#the code for the class
	include ('../TestProject/ajaxCRUD.class.php'); // <-- this include file MUST go first before any HTML/output

    #this one line of code is how you implement the class
    ########################################################
    ##

    $tblDemo = new ajaxCRUD("Item", "users", "userID", "../");

    ##
    ########################################################

    ## all that follows is setup configuration for your fields....
    ## full API reference material for all functions can be found here - http://ajaxcrud.com/api/
    ## note: many functions below are commented out (with //). note which ones are and which are not

    #i can define a relationship to another table
    #the 1st field is the fk in the table, the 2nd is the second table, the 3rd is the pk in the second table, the 4th is field i want to retrieve as the dropdown value
    #http://ajaxcrud.com/api/index.php?id=defineRelationship
    //$tblDemo->defineRelationship("fkID", "tblDemoRelationship", "pkID", "fldName", "fldSort DESC"); //use your own table - this table (tblDemoRelationship) not included in the installation script

    #i don't want to visually show the primary key in the table
    $tblDemo->omitPrimaryKey();

    #the table fields have prefixes; i want to give the heading titles something more meaningful
    $tblDemo->displayAs("userType", "User Type");
    $tblDemo->displayAs("fname", "First Name");
    $tblDemo->displayAs("lname", "Last name");



    #implement a callback function after updating/editing a field
    $tblDemo->onUpdateExecuteCallBackFunction("fldField1", "myCallBackFunctionForEdit");

    $tblDemo->onUpdateExecuteCallBackFunction("fldCertainFields", "myCallBackFunctionForEdit");


?>
	<h1>Example Using onUpdateExecuteCallBackFunction</h1>
		<div style="float: left">
			Total Returned Rows: <b><?=$tblDemo->insertRowsReturned();?></b><br />
		</div>

		<div style="clear:both;"></div>

<?php

	#actually show the table
	$tblDemo->showTable();

	#my self-defined functions used for formatFieldWithFunction
	function makeBold($val){
		return "<b>$val</b>";
	}

	function makeBlue($val){
		return "<span style='color: blue;'>$val</span>";
	}

	function myCallBackFunctionForAdd($array){
		echo "THE ADD ROW CALLBACK FUNCTION WAS implemented";
		print_r($array);
	}

	function myCallBackFunctionForEdit($array){
		echo "THE EDIT ROW CALLBACK FUNCTION WAS implemented";
		print_r($array);
	}
?>