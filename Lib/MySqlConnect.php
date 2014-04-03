<?php
/**
 * Class used to establish a data connection to a MySql database, execute sql
 * queries, get result sets, and close the db connection.
 */

class MySqlConnect
{
  protected $hostname = "localhost";
  protected $mysqlUsername = "root";
  protected $mysqlPassword = "";
  protected $databaseName = "docdatabase";
  protected $sqlQuery;
  protected $result;
  protected $conn;

  /**
   * Method called just before execution of a sql query. Use this to prevent sql
   * injection on each where clause parameter - escapes special string
   * characters, and un-quotes a quoted statement.
   *
   * @param $queryValue - string value used for the WHERE clause to clean up
   * @return $cleanQueryValue - the proper value format to use as a value in
   * query string
   */
  public function sqlCleanup($queryValue)
  {
    $cleanQueryValue = stripslashes($queryValue);
    $cleanQueryValue = mysql_real_escape_string($cleanQueryValue);

    return $cleanQueryValue;
  }

  /**
   * Method used to execute a query that doesn't expect a result set - CREATE,
   * UPDATE, DELETE
   *
   * @param $sqlQuery - string value representing the SQL query
   * @return $isCommit - boolean: returns true if query is committed
   */
  public function executeQuery($sqlQuery)
  {
    $isCommit = FALSE;
    $this -> $conn = new mysqli($this -> hostname, $this -> mysqlUsername, $this -> mysqlPassword, $this -> databaseName);
    $isCommit = $this -> $conn -> query($sqlQuery);

    return $isCommit;
  }

  /**
   * Method used to execute a query when you expect a result set object in
   * return. Use mysql_fetch_array($result, MYSQL_ASSOC) on the returned object
   * to iterate through each row as an array and access the values via index
   * number or column name
   *
   * @param $sqlQuery - string value representing the SQL query
   * @return $result - MySQL result object as the query result set
   */
  public function executeQueryResult($sqlQuery)
  {
    $this -> $conn = new mysqli($this -> hostname, $this -> mysqlUsername, $this -> mysqlPassword, $this -> databaseName) or die('Could not connect: ' . mysql_error());
    $this -> result = $this -> $conn -> query($sqlQuery);
    
    return $this -> result;
  }

  public function freeConnection()
  {
    /* free result set */
    $this -> $result -> free();

    /* close connection */
    $this -> $conn -> close();
  }

  public function getCurrentTs()
  {
    date_default_timezone_set('America/New_York');
    $currentTs = date('Y-m-d H:i:s');

    return $currentTs;
  }

}
?>