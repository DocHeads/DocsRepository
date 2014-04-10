<?php
class Submission
{

  public static function updateSubmission($subID, $email, $submissionUrl, $deptName, $courseName, $gradingUrl, $studentInstUrl, $instructorInstUrl, $comments, $willYouGrade)
  {
    $isCommit = FALSE;
    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();

    $updateSql = "UPDATE submissions
                     SET docName = '{$submissionUrl}',
                     deptName = '{$deptName}',
                     courseName = '{$courseName}',
                     emailAddress = '{$email}',
                     rubricFileName = '{$gradingUrl}',
                     studentInstructions = '{$studentInstUrl}',
                     instructorInstructions = '{$instructorInstUrl}',
                     rubricFileName = '{$gradingUrl}',
                     comments = '{$comments}',
                     willYouGrade = '{$willYouGrade}',
                     updateDate = '{$ts}'
               WHERE subID = '{$subID}'";

    // update existing submission record in the database
    $isCommit = $conn -> executeQuery($updateSql);
    $conn -> freeConnection();

    return $errMsg;

  }

  /**
   * Method used to return the result set of submission records associated to a
   * user.
   * Returns subID, docName, deptID, courseID, createDate, updateDate
   *
   * @param $userId - string value of the user to query by
   * @return mysqli resultset
   */
  public static function getUserSubmissions($userId)
  {
    $conn = new mysqli(ConfigProperties::$DatabaseServerName, ConfigProperties::$DatabaseUsername, ConfigProperties::$DatabasePassword, ConfigProperties::$DatabaseName);
    $query = "SELECT subID, docName, GetDeptName(deptID) deptName, GetCourseName(courseID) courseName, createDate, updateDate FROM submissions WHERE userID = {$userId} ORDER BY updateDate DESC";
    $result = $conn -> query($query);
    $conn -> close();

    return $result;
  }

  /**
   * Makes a file directory in the 'uploads' directory of the passed in parameter
   *
   * @param $val - string value of the directory name to be made
   * @return TRUE if directory was made in uploads
   */
  public static function makeDir($val)
  {
    $isMade = FALSE;
    if (!file_exists('../uploads/' . $val))
    {
      mkdir('../uploads/' . $val, 0777, true);
      $isMade = TRUE;
    }
  }

}
?>
