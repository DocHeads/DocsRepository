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

<h2>Welcome to the UC Faculty Document Managment System</h2>

<?php
if (Users::isAuthorized())
{
    $emailAddress = $_SESSION['email'];
    $errMsg = '';
    $rec_limit = 10;
  if (Session::getLoggedInUserType() == "ADMIN")
  {
    header('Location: ../Administration/adminHome.php');
  }
  else
      {
         echo '<div style="padding: 0px 20px 0px 20px">';
         echo '<table width="800" align="center" border="2">
                <tbody style="display: block; height: 250px;">
                    <tr width="800" height="250">
                        <td width="800" style="vertical-align:top;">';
                        
                        echo '<h2 style="font-size: 14px"><b>My Recent Submissions:</b></h2>';
                        
                        $dbhost = 'localhost';
                        $dbuser = 'root';
                        $dbpass = '';
                        $rec_limit = 5;
                        
                        $conn = mysql_connect($dbhost, $dbuser, $dbpass);
                        
                        if(! $conn )
                        {
                          die('Could not connect: ' . mysql_error());
                        }
                        
                        mysql_select_db('docdatabase');
                        
                        $sql = "SELECT COUNT(subID) FROM submissions ";
                        
                        $retval = mysql_query( $sql, $conn );
                        
                        if(! $retval )
                        {
                          die('Could not get data: ' . mysql_error());
                        }
                        $row = mysql_fetch_array($retval, MYSQL_NUM );
                        
                        $rec_count = $row[0];
                        
                        if( isset($_GET{'page'} ) )
                        {
                           $page = $_GET{'page'} + 1;
                           $offset = $rec_limit * $page ;
                        }
                        else
                        {
                           $page = 0;
                           $offset = 0;
                        }
                        $left_rec = $rec_count - ($page * $rec_limit);
                        
                        $sql = "SELECT * FROM submissions WHERE emailAddress = '$emailAddress' ORDER BY createDate DESC LIMIT $offset, $rec_limit";
                        
                        $retval = mysql_query( $sql, $conn );
                        if(! $retval )
                        {
                            die('Could not get data: ' . mysql_error());
                        }

                            echo "<table class='customTable' width='800' align='center'>
                                <tr>
                                <thead align='center'>
                                <th height='20px'>Submission Name</th>
                                <th height='20px'>File</th>
                                <th height='20px'>Department</th>
                                <th height='20px'>Course</th>                                
                                <th height='20px'>Instructor Instructions</th>
                                <th height='20px'>Student Instructions</th>
                                <th height='20px'><strong>Created On</strong></th>
                                <th height='20px'><strong>Action</strong></th>
                                </thead>
                                </tr>";
                            
                            while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
                              {
                                  echo "<tr>";
                                  echo "<td>" . $row['docName'] . "</td>";
                                  echo "<td>" . $row['submissionFile'] . "</td>";
                                  echo "<td>" . $row['deptName'] . "</td>";
                                  echo "<td>" . $row['courseName'] . "</td>";
                                  echo "<td>" . $row['instructorInstruction'] . "</td>";
                                  echo "<td>" . $row['studentInstruction'] . "</td>";
                                  echo "<td>" . $row['createDate'] . "</td>";
                                  echo "<td><a href=\"../Submission/submissionProfile.php?subID=" . $row['subID'] . "\"><img width='13px' src=\"../Images/edit.png\"></a></td>";
                              echo "</tr>";
                              }
                              
                              if( $page > 0 )
                                {
                                   $last = $page - 2;
                                   echo "<a href=\"$_PHP_SELF?page=$last\">Last 10 Records</a> |";
                                   echo "<a href=\"$_PHP_SELF?page=$page\">Next 10 Records</a>";
                                }
                                else if( $page == 0 )
                                {
                                   echo "<a href=\"$_PHP_SELF?page=$page\">Next 10 Records</a>";
                                }
                                else if( $left_rec < $rec_limit )
                                {
                                   $last = $page - 2;
                                   echo "<a href=\"$_PHP_SELF?page=$last\">Last 10 Records</a>";
                                }
                            echo "</table>";

                            mysql_close($conn);
                            
                            echo '</td>
                                  </tr>
                                  </tbody>        
                                  </table><br style="clear:both;" />';
                        

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
                                                            
                                #i could disable fields from being editable
                                $subTable->disallowEdit('emailAddress');
                                $subTable->disallowEdit('createDate');
                                $subTable->disallowEdit('deptName');
                                $subTable->disallowEdit('courseName');                                
                                $subTable->disallowEdit('submissionFile');
                                $subTable->disallowEdit('instructorInstruction');
                                $subTable->disallowEdit('studentInstruction');                             
                                #set the number of rows to display (per page)
                                $subTable->setLimit(10);
                                
                                #i can order my table by whatever i want
                                $subTable->addOrderBy("ORDER BY emailAddress ASC");
                                
                                #if really desired, a filter box can be used for all fields
                                $subTable->addAjaxFilterBoxAllFields();
                                
                                #i can disallow deleting of rows from the table
                                #http://ajaxcrud.com/api/index.php?id=disallowDelete
                                $subTable->disallowDelete();
                            
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
      }
    
    else
    {
      print '<p>Your account will be verified within 24-48 hours!  <br /><br />Please contact the site administrator for more information.</p>';
    }
?>