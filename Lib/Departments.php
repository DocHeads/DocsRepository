<?php
class Departments
{
  protected $departmentId;
  protected $departmentName;
  protected $createdTs;
  protected $updatedTs;

  public static function getDeptList()
  {
    $deptList = array();

    $conn = new MySqlConnect();
    $result = $conn -> executeQuery("SELECT deptName FROM departments ORDER BY deptName");

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      array_push($deptList, $row['deptName']);
    }
    $conn -> freeConnection();
    return $deptList;
  }

  public function createDept($deptName)
  {
    $conn = new MySqlConnect();
    $isCreated = FALSE;
    $currentTs = $conn -> getCurrentTs();

    $deptName = $conn -> sqlCleanup($deptName);
    $isCreated = $conn -> executeQuery("INSERT INTO departments(departmentName, createTs, updatedTs) VALUES ('%s', '%s', '%s')", $deptName, $currentTs, $currentTs);
    $conn -> freeConnection();
    return $isCreated;
  }

  public function deleteDept($deptName)
  {
    $conn = new MySqlConnect();
    $isDeleted = FALSE;
    $currentTs = $conn -> getCurrentTs();

    $deptName = $conn -> sqlCleanup($deptName);
    $isDeleted = $conn -> executeQuery("DELETE FROM departments WHERE departmentName = '%s'", $deptName);
    $conn -> freeConnection();
    return $isDeleted;
  }
}
?>