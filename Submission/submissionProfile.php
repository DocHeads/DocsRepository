<?php
include_once ('../templates/preheader.php');
include ('../Lib/Session.php');
Session::validateSession();
include ('../templates/header.php');
include ('../Lib/Submissions.php');
include ('../Lib/Departments.php');
include ('../Lib/Courses.php');

$errMsg = '';
$errMsgGrade = '';
$errMsgStud = '';
$errMsgInst = '';
$updateSubmissionFile = null;
$updateGradingFile = null;
$updateStudentFile = null;
$updateInstFile = null;
$dept = null;
$course = null;

$emailAddress = $_SESSION['email'];

// just do the get subID from the URL
if (isset($_POST['subID']) ? $subID = $_POST['subID'] : $subID = $_GET['subID'])
  ;
// insert the submission record
$conn = new MySqlConnect();
$result = array();

$sql = "SELECT * FROM submissions WHERE subID='{$subID}'";

$result = $conn -> executeQueryResult($sql);

if (isset($result))
{
  // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
  if ($row = mysql_fetch_array($result, MYSQL_NUM))
  {
    // access the password value in the db
    //$userList = array_push($row['0']);

    // Assign the values in this iteration to variables to use down in the form
    // refer to the profile.php page to pre-populate fields
    $email = $row[1];
    $dept = $row[2];
    $course = $row[3];
    $docName = $row[4];
    $comments = $row[5];
    $submissionFile = $row[6];
    $instructorInstFile = $row[7];
    $studentInstFile = $row[8];
    $gradingFile = $row[9];
    $willYouGrade = $row[10];
    $createDate = $row[11];
    $updateDate = $row[12];
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $subID = $_POST['subID'];

  if (isset($_POST['delete']))
  {
    $conn = new MySqlConnect();
    $deleteSql = "DELETE FROM submissions WHERE subID = '{$subID}'";

    // insert the submission record in the database
    if ($conn -> executeQuery($deleteSql))
    {
      Submission::deleteSubDir($subID);
      $dept = null;
      $course = null;
      $docName = null;
      $comments = null;
      $submissionFile = null;
      $instructorInstFile = null;
      $studentInstFile = null;
      $gradingFile = null;
      $errMsg = 'Submission record delete success.';
    }
    $conn -> freeConnection();
  }
  else
  {
    $conn = new MySqlConnect();
    $email = $_SESSION['email'];
    if (isset($_POST['dept']))
    {
      $dept = $_POST['dept'];
    }
    if (isset($_POST['docName']))
    {
      $docName = $_POST['docName'];
    }
    if (isset($_POST['course']))
    {
      $course = $_POST['course'];
    }
    if (isset($_POST['comments']))
    {
      $comments = $_POST['comments'];
    }
    if (isset($_POST['willYouGrade']))
    {
      $willYouGrade = $_POST['willYouGrade'];
    }
    // get the new files uploaded
    $fileUploadBaseDir = ConfigProperties::$BaseUploadDirectory;
    $updateSubmissionFile = "{$_FILES['submissionFile']['name']}";
    $updateGradingFile = "{$_FILES['gradingFile']['name']}";
    $updateStudentFile = "{$_FILES['studentInstFile']['name']}";
    $updateInstFile = "{$_FILES['instructorInstFile']['name']}";

    if (!empty($updateSubmissionFile))
    {
      $submissionFile = $updateSubmissionFile;
    }
    if (!empty($updateGradingFile))
    {
      $gradingFile = $updateGradingFile;
    }
    if (!empty($updateStudentFile))
    {
      $studentInstFile = $updateStudentFile;
    }
    if (!empty($updateInstFile))
    {
      $instructorInstFile = $updateInstFile;
    }

    print '<br/>';
    var_dump("{$fileUploadBaseDir}/{$subID}/{$updateSubmissionFile}");
    // validate the submission file upload
    if (!empty($updateSubmissionFile))
    {
      if (!file_exists("{$fileUploadBaseDir}/{$subID}/{$updateSubmissionFile}"))
      {
        if (move_uploaded_file($_FILES['submissionFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$updateSubmissionFile}"))
        {
          $errMsg = "Submission: {$docName} updated. File: {$updateSubmissionFile} upload success.";
          $submissionFile = "<a href=\"{$fileUploadBaseDir}/{$subID}/{$updateSubmissionFile}\">{$updateSubmissionFile}</a>";
          var_dump($submissionFile);
          print '<br />';
        }
        else
        {
          // Problem! Set $errMsg value based upon the error:
          switch ($_FILES['submissionFile']['error'])
          {
            case 1 :
              $errMsg = 'Submission file exceeds the upload_max_filesize setting in php.ini';
              break;
            case 2 :
              $errMsg = 'Submission file exceeds the MAX_FILE_SIZE setting in the HTML form';
              break;
            case 3 :
              $errMsg = 'Submission file was only partially uploaded';
              break;
            case 4 :
              $errMsg = 'Submission file was uploaded';
              break;
            case 6 :
              $errMsg = 'Submission temporary folder does not exist.';
              break;
            default :
              $errMsg = 'Error uploading Submission file.';
              break;
          }
        }
      }
      else
      {
        $errMsg = "Submission file name: " . $updateSubmissionFile . " already exists. Please rename before submitting to repository.";
      }
    }

    if (!empty($updateGradingFile))
    {
      var_dump("{$fileUploadBaseDir}/{$subID}/{$updateGradingFile}");
      if (file_exists("{$fileUploadBaseDir}/{$subID}/{$updateGradingFile}"))
      {
        $errMsgGrade = 'Duplicate file name error: Duplicate Grading file name ' . $updateGradingFile . ' failed to upload.';
        $gradingFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['gradingFile']['tmp_name'], "{$fileUploadBaseDir}\\{$subID}\\{$updateGradingFile}"))
        {
          $errMsgGrade = 'Submission: ' . $docName . ' updated. Grading File: ' . $gradingFile . ' upload success';
          $gradingFile = "<a href=\"{$fileUploadBaseDir}/{$subID}/{$updateGradingFile}\">{$updateGradingFile}</a>";
          var_dump($gradingFile);
          print '<br />';
        }
        else
        {
          $gradingFile = null;
          // Problem! Set $errMsg value based upon the error:
          switch ($_FILES['gradingFile']['error'])
          {
            case 1 :
              $errMsg = 'Grading file exceeds the upload_max_filesize setting in php.ini';
              break;
            case 2 :
              $errMsg = 'Grading file exceeds the MAX_FILE_SIZE setting in the HTML form';
              break;
            case 3 :
              $errMsg = 'Grading file was only partially uploaded';
              break;
            case 4 :
              $errMsg = 'Grading file was uploaded';
              break;
            case 6 :
              $errMsg = 'Grading temporary folder does not exist.';
              break;
            default :
              $errMsg = 'Error uploading Grading file.';
              break;
          }
        }
      }
    }

    if (!empty($updateStudentFile))
    {
      var_dump("{$fileUploadBaseDir}/{$subID}/{$updateStudentFile}");
      // student instruction upload file check and process
      if (file_exists("{$fileUploadBaseDir}/{$subID}/{$updateStudentFile}"))
      {
        $errMsgStud = 'Duplicate file name error: Instruction file name: ' . $updateStudentFile . ' failed to upload.';
        $studentInstFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['studentInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$subID}\\$updateStudentFile"))
        {
          $errMsgStud = 'Student Instruction File: ' . $updateStudentFile . ' upload success.';
          $studentInstFile = "<a href=\"{$fileUploadBaseDir}/{$subID}/{$updateStudentFile}\">{$updateStudentFile}</a>";
          var_dump($studentInstFile);
          print '<br />';
        }
        else
        {
          $studentInstFile = null;
          // Problem! Set $errMsg value based upon the error:
          switch ($_FILES['studentInstFile']['error'])
          {
            case 1 :
              $errMsg = 'Student Instruction file exceeds the upload_max_filesize setting in php.ini';
              break;
            case 2 :
              $errMsg = 'Student Instruction file exceeds the MAX_FILE_SIZE setting in the HTML form';
              break;
            case 3 :
              $errMsg = 'Student Instruction file was only partially uploaded';
              break;
            case 4 :
              $errMsg = 'Student Instruction file was uploaded';
              break;
            case 6 :
              $errMsg = 'Student Instruction temporary folder does not exist.';
              break;
            default :
              $errMsg = 'Error uploading Student Instruction file.';
              break;
          }
        }
      }
    }

    if (!empty($updateInstFile))
    {
      var_dump("{$fileUploadBaseDir}/{$subID}/{$updateInstFile}");
      if (file_exists("{$fileUploadBaseDir}/{$subID}/{$updateInstFile}"))
      {
        $errMsgInst = 'Duplicate file name error: Instructor Instruction File: ' . $updateInstFile . ' failed to upload.';
        $instructorInstFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['instructorInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$subID}\\{$updateInstFile}"))
        {
          $errMsgInst = 'Instructor Instruction File: ' . $updateInstFile . ' upload success.';
          $instructorInstFile = "<a href=\"{$fileUploadBaseDir}/{$subID}/{$updateInstFile}\">{$updateInstFile}</a>";
          var_dump($instructorInstFile);
          print '<br />';
        }
        else
        {
          $instructorInstFile = null;
          // Problem! Set $errMsg value based upon the error:
          switch ($_FILES['instructorInstFile']['error'])
          {
            case 1 :
              $errMsg = 'Instructor Instruction file exceeds the upload_max_filesize setting in php.ini';
              break;
            case 2 :
              $errMsg = 'Instructor Instruction file exceeds the MAX_FILE_SIZE setting in the HTML form';
              break;
            case 3 :
              $errMsg = 'Instructor Instruction file was only partially uploaded';
              break;
            case 4 :
              $errMsg = 'Instructor Instruction file was uploaded';
              break;
            case 6 :
              $errMsg = 'Instructor Instruction temporary folder does not exist.';
              break;
            default :
              $errMsg = 'Error uploading Instructor Instruction file.';
              break;
          }
        }
      }
    }

    // insert the submission record
    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();
    $result = array();

    $updateSql = "UPDATE submissions
                       SET docName = '{$docName}',
                           courseName = '{$course}',
                           comments = '{$comments}',";
    if ($submissionFile != null)
    {
      $updateSql .= "submissionFile = '{$submissionFile}',";
    }
    if ($gradingFile != null)
    {
      $updateSql .= "rubricFileName = '{$gradingFile}',";
    }
    if ($studentInstFile != null)
    {
      $updateSql .= "studentInstruction = '{$studentInstFile}',";
    }
    if ($instructorInstFile != null)
    {
      $updateSql .= "instructorInstruction = '{$instructorInstFile}',";
    }
    $updateSql .= "willYouGrade = '{$willYouGrade}'";
    $updateSql .= "WHERE subID = '{$subID}'";

    // insert the submission record in the database
    $isCommit = $conn -> executeQuery($updateSql);

    // email the opt in users
    $body .= "Submission Profile Update:\n";
    $body .= "----------------------------\n\n";
    $body .= "Submission Name: {$docName}\n\n";
    $body .= "Dept: {$dept}\n\n";
    $body .= "Course: {$course}\n\n";
    $body .= "Comments: {$comments}\n\n";

    Users::emailOptInUsers($body);
  }
}
?>

<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<h2>Edit Submission Profile for ' . $docName . ' (*=required field)</h2>';
}
else
{
  echo '<h2>Submission Profile for ' . $docName .'</h2>';
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  print '<p><span style="color: #b11117"><b>UPLOAD STATUS:</b></span></p>';
  print '<p><span style="color: #b11117"><b>&bull; ' . $errMsg . '</b></span></p>';

  if (!empty($errMsgGrade))
  {
    print '<p><span style="color: #b11117"><b>&bull; ' . $errMsgGrade . '</b></span></p>';
  }
  if (!empty($errMsgStud))
  {
    print '<p><span style="color: #b11117"><b>&bull; ' . $errMsgStud . '</b></span></p>';
  }
  if (!empty($errMsgInst))
  {
    print '<p><span style="color: #b11117"><b>&bull; ' . $errMsgInst . '</b></span></p>';
  }
}
?>
<br />
<form style="border:1px solid #c6bebb;" action="submissionProfile.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">

<?php
print '<input type="hidden" name="subID" value="' . $subID . '"/>';
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<label for="docName"><strong>Document Name: <span style="color: red">*</span>&nbsp;&nbsp;</strong> </label>';
  echo '<input type="hidden" id="volume" value="1" />';
  echo '<input type="text" name="docName" value="' . $docName . '"/>';
}
else
{
  print '<label for="docName"><strong>Document Name:&nbsp;&nbsp;</strong> </label>';
  print $docName . '<input type="hidden" name="dept" value="' . $docName . '"/><br />';
}
?>
<br /><br />

<label for="submissionFile"><strong>Document File:</strong> </label>  <?php print '&nbsp;&nbsp;' . $submissionFile
?>

<?php
if (!($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN'))
{
  echo '<br /><br /><br />';
}
?>

<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<br /><br />&nbsp;&nbsp;&bull; Change File: <input type="file" name="submissionFile" size="200"/><br /><br />';
}
?>

<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<label for="comments"><strong>Document Description: <span style="color: red">*</span>&nbsp;&nbsp;</strong></label>';
  print '<textarea id="comments" name="comments" wrap="virtual" rows="5em" cols="80em" valign="top" align="left">' . $comments . '</textarea>';
}
else
{
  echo '<label for="comments"><strong>Document Description: </strong></label>&nbsp;';
  print $comments . '<input type="hidden" name="dept" value="' . $comments . '"/><br />';
}
?>

<br/><br />
<label for="rubricFileName"><strong>Grading Rubric: </strong></label> &nbsp; <?php print $gradingFile; ?>
<br /><br />
<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '&nbsp;&nbsp;&bull; Change File: <input type="file" name="gradingFile" id="rubricFileName" class="clsFile"><br />';
}
?>
<br />
<label for="instructionsToTheStudent"><strong>Instructions to the student: </strong></label>  &nbsp; <?php print $studentInstFile; ?>

<br /><br />
<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '&nbsp;&nbsp;&bull; Change File: <input type="file" name="studentInstFile" id="instructionsToTheStudent" class="clsFile"><br />';
}
?>
<br />
<label for="instructionsToTheInstructor"><strong>Instructions to the instructor: </strong></label>  &nbsp; <?php print $instructorInstFile; ?>

<br /><br />
<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '&nbsp;&nbsp;&bull; Change File: <input type="file" name="instructorInstFile" id="instructionsToTheInstructor" class="clsFile"><br /><br />';
}
?>
<br />
<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<label for="willGrade"><strong>Will you grade assignments based on this document? &nbsp </strong></label>';

  if (($willYouGrade == 'YES') || ($willYouGrade == 'Yes'))
  {
    print '<input type="radio" name="willYouGrade" id="willYouGrade" value="Yes" class="radio-box" checked >Yes &nbsp
<input type="radio" name="willYouGrade" id="willYouGrade" value="No"  class="radio-box">No';
  }
  else
  {
    print '<input type="radio" name="willYouGrade" id="willYouGrade" value="Yes" class="radio-box">Yes &nbsp
<input type="radio" name="willYouGrade" id="willYouGrade" value="No"  class="radio-box" checked>No';
  }
}
?>

<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '<br>';
  echo '<br>';
  echo '<label for="course"><strong>Department: </strong></label>';

  $deptNm = Departments::getDeptList();
  echo '<select name="dept">';
  foreach ($deptNm as $key => $value)
  {
    if ($value == $dept)
    {
      echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
    }
    else
    {
      echo '<option value="' . $value . '">' . $value . '</option>';
    }
  }
  echo '</select>';
  echo '<br>';
  echo '<br>';
  echo '<label for="course"><strong>Course: </strong></label>';

  $courseNm = Courses::getCourseList();
  echo '<select name="course">';
  foreach ($courseNm as $key => $value)
  {
    if ($value == $course)
    {
      echo '<option value="' . $value . '" selected="selected">' . $value . '</option>';
    }
    else
    {
      echo '<option value="' . $value . '">' . $value . '</option>';
    }
  }
  echo '</select>';
}

?>

<?php
if ($emailAddress == $email || Session::getLoggedInUserType() == 'ADMIN')
{
  echo '
<br /><br />
<div class="btn-holder">
<button name="save" type="submit">
Submit
</button>
<button name="delete" type="submit">
Delete
</button>
</div>
</form>
';
}
?>

<?php
include ('../templates/footer.html');
?>
