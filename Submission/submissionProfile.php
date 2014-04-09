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

// just do the get subID from the URL
$subID = $_GET['subID'];
// insert the submission record
$conn = new MySqlConnect();
$result = array();

$sql = "SELECT * FROM submissions WHERE subID={$subID}";
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
  print '<br/>';
  var_dump($_POST);
  print '<br/>';

  print '<br/>';
  var_dump($_FILES);
  print '<br/>';

  $subID = $_POST['subID'];

  if (isset($_POST['delete']))
  {
    $conn = new MySqlConnect();
    $deleteSql = "DELETE FROM submissions WHERE subID = '{$subID}'";

    // insert the submission record in the database
    if ($conn -> executeQuery($deleteSql))
    {
      $errMsg = 'Submission record delete success.';
    }
    $conn -> freeConnection();
  }
  else
  {
    $conn = new MySqlConnect();
    $email = $_SESSION['email'];
    $dept = $_POST['dept'];
    $docName = $_POST['docName'];
    $course = $_POST['course'];
    $comments = $_POST['comments'];
    $willYouGrade = $_POST['willYouGrade'];
    $fileUploadBaseDir = ConfigProperties::$BaseUploadDirectory;
    $submissionFile = "{$_FILES['submissionfile']['name']}";
    $gradingFile = "{$_FILES['gradingFile']['name']}";
    $studentInstFile = "{$_FILES['studentInstFile']['name']}";
    $instructorInstFile = "{$_FILES['instructorInstFile']['name']}";
    $deptID = NULL;
    $body = '';

    if (!empty($submissionFile))
    {
      $updateSubmissionFile = $submissionFile;
    }
    if (!empty($gradingFile))
    {
      $updateGradingFile = $gradingFile;
    }
    if (!empty($studentInstFile))
    {
      $updateStudentFile = $studentInstFile;
    }
    if (!empty($instructorInstFile))
    {
      $updateInstFile = $instructorInstFile;
    }

    print '<br/>';
    var_dump("{$fileUploadBaseDir}/{$dept}/{$updateSubmissionFile}");
    // validate the submission file upload
    if (!empty($updateSubmissionFile))
    {
      if (!file_exists("{$fileUploadBaseDir}/{$dept}/{$updateSubmissionFile}"))
      {
        if (move_uploaded_file($_FILES['submissionfile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$updateSubmissionFile}"))
        {
          $errMsg = "Submission: {$docName} File: {$updateSubmissionFile} upload success.";
          $updateSubmissionFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$updateSubmissionFile}\">{$updateSubmissionFile}</a>";
        }
        else
        {
          // Problem! Set $errMsg value based upon the error:
          switch ($_FILES['submissionfile']['error'])
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
      if (file_exists("{$fileUploadBaseDir}/{$dept}/{$updateGradingFile}"))
      {
        $errMsgGrade = 'Duplicate file name error: Duplicate Grading file name ' . $updateGradingFile . ' failed to upload.';
        $updateGradingFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['gradingFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$updateGradingFile}"))
        {
          $errMsgGrade = 'Grading File: ' . $gradingFile . ' upload success';
          $gradingFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$updateGradingFile}\">{$updateGradingFile}</a>";
        }
        else
        {
          $updateGradingFile = null;
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

    if (!empty($updateStudentInstFile))
    {
      // student instruction upload file check and process
      if (file_exists("{$fileUploadBaseDir}/{$dept}/{$updateStudentInstFile}"))
      {
        $errMsgStud = 'Duplicate file name error: Instruction file name: ' . $updateStudentInstFile . ' failed to upload.';
        $updateStudentInstFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['studentInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\$updateStudentInstFile"))
        {
          $errMsgStud = 'Student Instruction File: ' . $updateStudentInstFile . ' upload success.';
          $updateStudentInstFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$updateInstFile}\">{$updateStudentInstFile}</a>";

        }
        else
        {
          $updateStudentInstFile = null;
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
      if (file_exists("{$fileUploadBaseDir}/{$dept}/{$updateInstFile}"))
      {
        $errMsgInst = 'Duplicate file name error: Instructor Instruction File: ' . $updateInstFile . ' failed to upload.';
        $updateInstFile = null;
      }
      else
      {
        if (move_uploaded_file($_FILES['instructorInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$updateInstFile}"))
        {
          $errMsgInst = 'Instructor Instruction File: ' . $updateInstFile . ' upload success.';
          $instructorInstFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$updateInstFile}\">{$updateInstFile}</a>";
        }
        else
        {
          $updateInstFile = null;
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
    $docName = $conn -> sqlCleanup($docName);
    $updateSubmissionFile = $conn -> sqlCleanup($updateSubmissionFile);
    $email = $conn -> sqlCleanup($email);
    $updateGradingFile = $conn -> sqlCleanup($updateGradingFile);
    $updateInstFile = $conn -> sqlCleanup($updateInstFile);
    $updateInstFile = $conn -> sqlCleanup($updateInstFile);
    $comments = $conn -> sqlCleanup($comments);
    $result = array();

    $updateSql = "UPDATE submissions
                       SET docName = '{$docName}',
                           courseName = '{$course}',
                           comments = '{$comments}',";
    if ($updateSubmissionFile != null)
    {
      $updateSql .= "submissionFile = '{$updateSubmissionFile}',";
      $submissionFile = $updateSubmissionFile;
    }
    if ($updateGradingFile != null)
    {
      $updateSql .= "rubricFileName = '{$updateGradingFile}',";
      $gradingFile = $updateGradingFile;
    }
    if ($updateInstFile != null)
    {
      $updateSql .= "studentInstruction = '{$updateInstFile}',";
      $studentInstFile = $updateInstFile;
    }
    if ($updateInstFile != null)
    {
      $updateSql .= "instructorInstruction = '{$updateInstFile}',";
      $instructorInstFile = $updateInstFile;
    }
    $updateSql .= "willYouGrade = '{$willYouGrade}'";

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

<h2>Edit Submission Profile (*=required field)</h2>
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
<?php print '<input type="hidden" name="subID" value="' . $subID . '"/>'; ?>

<label for="docName">Document Name *</label>
<input type="hidden" id="volume" value="1" />
<?php print '<input type="text" name="docName" value="' . $docName . '"/>'; ?>
<br /><br />
<label for="submissionFile">Document File: </label>  <?php print '&nbsp;&nbsp;' . $submissionFile
?>
<br /><br />
&nbsp;&nbsp;&bull; Change File: <input type="file" name="submissionfile" size="200"/>
<br/><br />

<label for="comments">Document Description: </label>
<?php print '<textarea id="comments" name="comments" wrap="virtual"
rows="5em" cols="80em" valign="top" align="left">' . $comments . '</textarea>';
?>
<br/><br />

<label for="rubricFileName">Grading Rubric: </label> &nbsp; <?php print $gradingFile; ?>
<br /><br />
&nbsp;&nbsp;&bull; Change File: <input type="file" name="gradingFile" id="rubricFileName" class="clsFile">
<br /><br />

<label for="instructionsToTheStudent">Instructions to the student: </label>  &nbsp; <?php print $studentInstFile; ?>
<br /><br />
&nbsp;&nbsp;&bull; Change File: <input type="file" name="studentInstFile" id="instructionsToTheStudent" class="clsFile">
<br /><br />

<label for="instructionsToTheInstructor">Instructions to the instructor: </label>  &nbsp; <?php print $instructorInstFile; ?>
<br /><br />
&nbsp;&nbsp;&bull; Change File: <input type="file" name="instructorInstFile" id="instructionsToTheInstructor" class="clsFile">
<br /><br />

<label for="willGrade">Will you grade assignments based on this document? &nbsp </label>
<?php
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
?>
<br />
<br />
<p>
<label for="department">Department: </label>&nbsp;
<?php print $dept . '<input type="hidden" name="dept" value="' . $dept . '"/>';

  echo '<br>';
  echo '<br>';
  echo '<label for="course">Course: </label>';

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
?>
</p>
<br />
<div class="btn-holder">
<button name="save" type="submit">
Submit
</button>
<button name="delete" type="submit">
Delete
</button>
</div>
</form>

<?php
include ('../templates/footer.html');
?>
