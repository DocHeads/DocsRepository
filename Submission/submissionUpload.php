<?php
include '../Lib/ConfigProperties.php';
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

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $conn = new MySqlConnect();
  $email = $_SESSION['email'];
  $docName = $_POST['docName'];
  $dept = $_POST['department'];
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
  $subID = '';

  $insertSql = "INSERT INTO submissions (emailAddress)
                                 VALUES ('{$email}')";

  $isCommit = $conn -> executeQuery($insertSql);
  $conn -> freeConnection();

  $conn2 = new MySqlConnect();
  $sql = "SELECT subID FROM submissions WHERE emailAddress = '{$email}' ORDER BY updateDate DESC";

  $result = $conn2 -> executeQueryResult($sql);

  if (isset($result))
  {
    // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      $subID = $row['subID'];
    }
  }

  if (Submission::makeDir($subID))
  {
    
    /////////////////////SUBMIT FILES TO CORRESPONDING SUBID////////////////////////////
    
    // validate the submission file upload
    if (!file_exists("{$fileUploadBaseDir}/{$dept}/{$submissionFile}"))
    {
      if (move_uploaded_file($_FILES['submissionfile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$submissionFile}"))
      {
        $errMsg = "Submission: {$docName} File: {$submissionFile} upload success.";
        $submissionFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$submissionFile}\">{$submissionFile}</a>";

        // email the opt in users
        $body .= "Submission Profile:\n";
        $body .= "-----------------------\n\n";
        $body .= "Submission Name: {$docName}\n\n";
        $body .= "Dept: {$dept}\n\n";
        $body .= "Course: {$course}\n\n";
        $body .= "Comments: {$comments}\n\n";

        Users::emailOptInUsers($body);

        if (!empty($gradingFile))
        {
          if (file_exists("{$fileUploadBaseDir}/{$dept}/{$gradingFile}"))
          {
            $errMsgGrade = 'Duplicate file name error: Duplicate Grading file name ' . $gradingFile . ' failed to upload.';
            $gradingFile = null;
          }
          else
          {
            if (move_uploaded_file($_FILES['gradingFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$gradingFile}"))
            {
              $errMsgGrade = 'Grading File: ' . $gradingFile . ' upload success';
              $gradingFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$gradingFile}\">{$gradingFile}</a>";
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

        if (!empty($studentInstFile))
        {
          // student instruction upload file check and process
          if (file_exists("{$fileUploadBaseDir}/{$dept}/{$studentInstFile}"))
          {
            $errMsgStud = 'Duplicate file name error: Instruction file name: ' . $studentInstFile . ' failed to upload.';
            $studentInstFile = null;
          }
          else
          {
            if (move_uploaded_file($_FILES['studentInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\$studentInstFile"))
            {
              $errMsgStud = 'Student Instruction File: ' . $studentInstFile . ' upload success.';
              $studentInstFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$studentInstFile}\">{$studentInstFile}</a>";

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

        if (!empty($instructorInstFile))
        {
          if (file_exists("{$fileUploadBaseDir}/{$dept}/{$instructorInstFile}"))
          {
            $errMsgInst = 'Duplicate file name error: Instructor Instruction File: ' . $instructorInstFile . ' failed to upload.';
            $instructorInstFile = null;
          }
          else
          {
            if (move_uploaded_file($_FILES['instructorInstFile']['tmp_name'], "{$fileUploadBaseDir}\\{$dept}\\{$instructorInstFile}"))
            {
              $errMsgInst = 'Instructor Instruction File: ' . $instructorInstFile . ' upload success.';
              $instructorInstFile = "<a href=\"{$fileUploadBaseDir}/{$dept}/{$instructorInstFile}\">{$instructorInstFile}</a>";
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
        $docName = $conn -> sqlCleanup($docName);
        $submissionFile = $conn -> sqlCleanup($submissionFile);
        $email = $conn -> sqlCleanup($email);
        $gradingFile = $conn -> sqlCleanup($gradingFile);
        $studentInstFile = $conn -> sqlCleanup($studentInstFile);
        $instructorInstFile = $conn -> sqlCleanup($instructorInstFile);
        $comments = $conn -> sqlCleanup($comments);
        $result = array();
        $editButton = '<a href="../Submission/submissionProfile.php?subID=' . $subID . '"><img width="13px" src="../Images/edit.png"></a>';

        $updateSql = "UPDATE submissions
                       SET docName = '{$docName}',
                           submissionFile = '{$submissionFile}', 
                           deptName = '{$dept}', 
                           courseName = '{$course}', 
                           rubricFileName = '{$gradingFile}', 
                           studentInstruction = '{$studentInstFile}',  
                           instructorInstruction = '{$instructorInstFile}',
                           comments = '{$comments}', 
                           willYouGrade = '{$willYouGrade}', 
                           createDate = '{$ts}', 
                           updateDate = '{$ts}',
                           edit = '{$editButton}')";

        // insert the submission record in the database
        $isCommit = $conn -> executeQuery($updateSql);
        $conn -> freeConnection();

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
      $errMsg = "Submission file name: " . $submissionFile . " already exists. Please rename before submitting to repository.";
    }
  }
}
?>

<h2>Submit a document to the UC Document Repository (*=required field)</h2>
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
<form style="border:1px solid #c6bebb;" action="submissionUpload.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<label for="docName"><strong>Document Name <span style="color: red">*</span</strong></label>
<input type="hidden" id="volume" value="1" />
<input type="text" name="docName">
<br />
<br />
<label for="docFile"><strong>Document <span style="color: red">*</span></strong></label> &nbsp
<input type="file" name="submissionfile" size="200" required="required">
<br>
<br />
<label for="comments"><strong>Document Description <span style="color: red">*</span></strong></label>
<textarea	id="comments" name="comments" value="" wrap="virtual"
rows="5em" cols="80em"
valign="top"
align="left"
required="required">
</textarea>
<br>
<br />
<label for="rubricFileName"><strong>Grading Rubric (optional)</strong></label>
<input type="file" name="gradingFile" id="rubricFileName" class="clsFile">

<br>
<br />

<label for="instructionsToTheStudent"><strong>Instructions to the student (optional)</strong></label>
<input type="file" name="studentInstFile" id="instructionsToTheStudent" class="clsFile">

<br>
<br />

<label for="instructionsToTheInstructor"><strong>Instructions to the instructor (optional)</strong></label>
<input type="file" name="instructorInstFile" id="instructionsToTheInstructor" class="clsFile">

<br>
<br />

<label for="willGrade"><strong>Will you grade assignments based on this document? &nbsp </strong></label>
<input type="radio" name="willYouGrade" id="willYouGrade" value="Yes" class="radio-box" checked >
Yes &nbsp
<input type="radio" name="willYouGrade" id="willYouGrade" value="No"  class="radio-box">
No
<p>
<br />
<label for="department"><strong>Department <span style="color: red">*</span></strong></label>
<?php
$department = Departments::getDeptList();
echo '<select name="department">';
echo '<option selected="selected">Select your department...</option>';
foreach ($department as $key => $value)
{
  echo '<option value="' . $value . '">' . $value . '</option>';
}
echo '</select>';

echo '<br><br />';
echo '<label for="course"><strong>Course <span style="color: red">*</span></strong></label>';

$course = Courses::getCourseList();
echo '<select name="course">';
echo '<option selected="selected">Select your course...</option>';
foreach ($course as $key => $value)
{
  echo '<option value="' . $value . '">' . $value . '</option>';
}
echo '</select>';
?>
<br />
<br />
<div class="btn-holder">
<button type="submit">
Submit
</button>
</div>
</form>

<?php
include ('../templates/footer.html');
?>
