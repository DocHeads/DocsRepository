<?php
class Submission
{

  

  public static function updateSubmission($subID, $email, $submissionUrl, $deptName, $courseName, $gradingUrl, $studentInstUrl, $instructorInstUrl, $comments, $willYouGrade)
  {
    $isCommit = FALSE;
    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();

    $updateSql = "UPDATE Submissions
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
    $conn = new mysqli('localhost', 'root', '', 'docdatabase');
    $query = "SELECT subID, docName, GetDeptName(deptID) deptName, GetCourseName(courseID) courseName, createDate, updateDate FROM submissions WHERE userID = {$userId} ORDER BY updateDate DESC";
    $result = $conn -> query($query);
    $conn -> close();

    // $conn = new MySqlConnect();
    // $userId = $conn -> sqlCleanup($userId);
    // $query = "SELECT subID, docName, GetDeptName(deptID) deptName,
    // GetCourseName(courseID) courseName, createDate, updateDate FROM
    // submissions WHERE userID = {$userId} ORDER BY updateDate DESC";
    // $result = $conn -> executeQueryResult($query);
    //
    //$conn -> freeConnection();
    return $result;
  }

}
?>
