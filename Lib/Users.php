<?php
/*
 * Users.php object used to manage all user functions.
 * - CRUD functionality
 * - Password encryption
 * - Password resets
 * - Registering users
 * - Validating users
 */
include ('../Lib/MySqlConnect.php');
class Users
{
  protected $username;
  protected $password;
  protected $firstName;
  protected $lastName;
  protected $email;
  protected $userType;
  protected $emailOptIn;

  /**
   * Method used to encode user passwords. Uses the SHA512 hash algorithm to
   * encode the passwords.
   *
   * @param $password - string value to encode
   * @return $passEncoding - string value representing the hashed encoding
   */
  public static function encodePassword($password)
  {
    // hash the password and return a 128 bit hash
    $passEncoding = hash("sha512", trim($password));

    return $passEncoding;
  }

  /**
   * Method used to return the corresponding primary key value for the passed in
   * user type reference.
   *
   * @param $userType - string value corresponding to the db primary key:
   * 'STANDARD' or 'ADMIN'
   * @return $id - int value of the primary key
   */
  public static function getUserTypeIDValue($userType)
  {
    $conn = new MySqlConnect();
    $id;

    $userType = $conn -> sqlCleanup($userType);
    // query the db for the value comparison

    $result = $conn -> executeQueryResult("SELECT userTypeID FROM userTypes WHERE userTypeName = '{$userType}'");
    // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      // access the password value in the db
      $id = $row['userTypeID'];
    }
    $conn -> freeConnection();
    return $id;
  }

  /**
   * Method used to check the user record isValidated value in the db. Indicates
   * if the registered user is authorized to access the site.
   *
   * @return $isValid - boolean; returns TRUE if the user is validated/authorized
   */
  public static function isAuthorized()
  {
    $conn = new MySqlConnect();
    $isValid = FALSE;

    if (isset($_SESSION['email']))
    {
      $email = $_SESSION['email'];
      // query the db for the value comparison
      $result = $conn -> executeQueryResult("SELECT isValidated FROM users WHERE emailAddress = '{$email}'");
      // get a row count to verify only 1 row is returned
      $count = mysql_num_rows($result);
      if ($count == 1)
      {
        // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result
        // object
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {
          // check the boolean value
          if (trim($row['isValidated']) == 'YES')
          {
            $isValid = TRUE;
          }
        }
      }
      $conn -> freeConnection();
    }
    return $isValid;
  }

  /**
   * Method used to validate the username and password credentials against the
   * values in the database. Method also sets the following Session variables
   * upon validation:
   *
   * $_SESSION['name']
   * $_SESSION['userType']
   * $_SESSION['email']
   *
   * @param $email - string value to check against the email/username in the db
   * @param $password - string value to check against the password in the db
   * @return isValid - boolean value: returns TRUE if user record exists
   */
  public static function validateUser($email, $password)
  {
    $conn = new MySqlConnect();
    $isValid = FALSE;
    $dbHash = null;
    $userId = null;
    $name = null;
    $userType = null;

    // hash the submitted password to to verify against the value in the db
    $hash = Users::encodePassword($password);

    $email = $conn -> sqlCleanup($email);
    // query the db for the value comparison
    $result = $conn -> executeQueryResult("SELECT userId, password, fName, lName, userType FROM users WHERE emailAddress = '{$email}'");

    // get a row count to verify only 1 row is returned
    $count = mysql_num_rows($result);
    if ($count == 1)
    {
      var_dump($result);
      // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        // access the password value in the db
        $userId = trim($row['userId']);
        $dbHash = trim($row['password']);
        $userType = trim($row['userType']);
        $name = "{$row['fName']} {$row['lName']}";
      }

      // compare the input password hash with the db hash, and set as valid if
      // they match
      if ($hash == $dbHash)
      {
        $isValid = TRUE;
        session_start();
        // register the userId, name, and userType in the $_SESSION
        $_SESSION['userId'] = $userId;
        $_SESSION['name'] = $name;
        $_SESSION['userType'] = $userType;
        $_SESSION['email'] = $email;
        $_SESSION['timeout'] = time();
      }
    }
    $conn -> freeConnection();
    return $isValid;
  }

  /**
   * Method used to if a user with the same email already exists in the database.
   *
   * @param $email - string value of the email/username to check in the db
   * @return $isFound - boolean; returns TRUE if the user's email record already
   * exists
   */
  public static function exists($email)
  {
    $conn = new MySqlConnect();
    $isFound = FALSE;

    $email = $conn -> sqlCleanup($email);
    // query the db for the value comparison
    $result = $conn -> executeQueryResult("SELECT userId FROM users WHERE emailAddress = '{$email}'");

    // get a row count to verify only 1 row is returned
    $count = mysql_num_rows($result);
    if ($count > 0)
    {
      $isFound = TRUE;
    }
    $conn -> freeConnection();
    return $isFound;
  }

  /**
   * Method used to request a password reset in the system. Sets a temporary pass
   * key hashed with md5 algorithm, emails the passed in email address the proper
   * URL with email and temp pass key values
   *
   * @param $email - string value for the email address to reset the password
   * @return TRUE if the email is sent to corresponding email address
   */
  public static function requestPasswordReset($email)
  {
    $isReq = FALSE;
    $conn = new MySqlConnect();
    $email = $conn -> sqlCleanup($email);
    $ts = $conn -> getCurrentTs();
    $name = '';

    // generate a random number for tha hash key
    $randNum = rand();
    $hash = hash("md5", $randNum);

    $isReq = $conn -> executeQuery("UPDATE users SET tempPassKey = '{$hash}', updateDate = '{$ts}' WHERE emailAddress = '{$email}'");
    $conn -> freeConnection();
    // get the first and last name of the user
    $conn2 = new MySqlConnect();
    $result = $conn2 -> executeQueryResult("SELECT  fName, lName FROM users WHERE emailAddress = '{$email}'");

    if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      $name = $row['fName'] . ' ' . $row['lName'];
    }
    // send the hash key to the user's email to confirm and follow back to the
    // site
    $from = "Brian Dunavent <b.dunavent@gmail.com>";
    $subject = 'UC Document Repository: Confirm Password Reset';
    $body = "Dear {$name},\n\nPlease follow the URL to confirm your password reset request at the UC Document Repository.\n\n";
    $body .= "https://localhost/DocsRepository/Authentication/resetPassword.php?email={$email}&tempKey={$hash}";

    // send it from the logged in user
    $to = $name . " <" . $email . ">";

    if (sendMail($to, $from, $subject, $body))
    {
      $isReq = TRUE;
    }
    $conn2 -> freeConnection();
    return $isReq;
  }

  /**
   * Method used to confirm that the password request is legitimate by passing in
   * an email and temp pass key generated in an email to the user. These values
   * are returned in the URL for validation before a password reset can occur.
   * Also sets the userId SESSION value.
   *
   * @param $email - string value for the email address to validate against
   * @param $tempKey - string value of the temp pass key from the hashed value in
   * confirmation email
   * @return TRUE if the password and temp key are from the same user record in system
   */
  public static function confirmPasswordReset($email, $tempKey)
  {
    $isConfirmed = FALSE;
    $conn = new MySqlConnect();
    $email = $conn -> sqlCleanup($email);
    $tempKey = $conn -> sqlCleanup($tempKey);
    $dbEmail = null;
    $dbUserID = null;

    $result = $conn -> executeQueryResult("SELECT userID, emailAddress FROM users WHERE tempPassKey = '{$tempKey}' AND emailAddress = '{$email}'");
    if (isset($result))
    {
      // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
      if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        // get the email in the db for the user with the corresponding
        // tempPassKey set
        $dbEmail = $row['emailAddress'];
      }
    }

    // compare the sent email to the email in db
    if ($email == $dbEmail)
    {
      session_start();
      $isConfirmed = TRUE;
      // put the userId in the SESSION variable for retrieval when saving the
      // new password to the db
      $_SESSION['userId'] = $row['userID'];
    }

    $conn -> freeConnection();
    return $isConfirmed;
  }

  /**
   * Method used to reset a user's password. Calls encodePassword to hash the
   * value of the password parameter before it gets updated in the database
   *
   * @param $email - string value of the email used in the database update
   * @param $password - string of the plain text password to hash before updated
   * in the database
   * @return $isCommit - boolean; returns TRUE if update is committed
   */
  public static function resetPassword($email, $password)
  {
    $conn = new MySqlConnect();
    $isCommit = FALSE;

    $email = $conn -> sqlCleanup($email);
    $hash = Users::encodePassword($password);
    $ts = $conn -> getCurrentTs();

    $isCommit = $conn -> executeQuery("UPDATE Users SET password = '{$hash}', tempPassKey = null, updateDate = '{$ts}' WHERE emailAddress = '{$email}'");
    $conn -> freeConnection();
    return $isCommit;
  }

  /**
   * Method used to register/create a new user in the system.
   *
   * @param $username- string value for the username
   * @return $isCommit - boolean; returns TRUE if the user record is committed
   */
  public static function registerUser($password, $firstName, $lastName, $email, $emailOptIn)
  {
    $isCommit = FALSE;
    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();

    $password = $conn -> sqlCleanup($password);
    $firstName = $conn -> sqlCleanup($firstName);
    $lastName = $conn -> sqlCleanup($lastName);
    $email = $conn -> sqlCleanup($email);
    $emailOptIn = $conn -> sqlCleanup($emailOptIn);
    $userType = 'STANDARD';

    // hash the password
    $hash = Users::encodePassword($password);
    $sqlQuery = "INSERT INTO Users (password, fName, lName, emailAddress, userType, emailOptIn, isValidated, createDate, updateDate)";
    $sqlQuery .= "VALUES ('{$hash}', '{$firstName}', '{$lastName}', '{$email}', '{$userType}', '{$emailOptIn}', 'NO', '{$ts}', '{$ts}')";

    $isCommit = $conn -> executeQuery($sqlQuery);
    $conn -> freeConnection();

    return $isCommit;
  }

  /**
   * Method used to delete a user record from the database.
   *
   * @param $username - string value of the username value to delete
   * @return $isCommit - boolean; returns TRUE if delete is committed
   */
  public function deleteUser($username)
  {
    $isCommit = FALSE;
    $conn = new MySqlConnect();

    $username = $conn -> sqlCleanup($username);
    $isCommit = $conn -> executeQuery("DELETE FROM Users WHERE username = '%s'", $username);

    $conn -> freeConnection();
    return $isCommit;
  }

  public function getUserList()
  {
    $result;
    $userList = array();
    $conn = new MySqlConnect();

    $result = $conn -> executeQueryResult("SELECT username FROM Users");
    if (isset($result))
    {
      // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        // access the password value in the db
        $userList = array_push($row[0]);
      }
    }
    $conn -> freeConnection();
    return $userList;
  }

  /**
   * Method used to return an associative array of unauthorized users still in
   * the system
   *
   * Ex.
   *
   *        $userList['email'] = test@test.com
   *
   * @return $userList - associative array of emails/usernames
   */
  public static function getUnauthorizedUserList()
  {
    $result;
    $userList = array();
    $conn = new MySqlConnect();

    $result = $conn -> executeQueryResult("SELECT email FROM Users WHERE isValidated = 0");
    if (isset($result))
    {
      // use mysql_fetch_array($result, MYSQL_ASSOC) to access the result object
      while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
      {
        // access the password value in the db
        $userList = array_push($row['email']);
      }
    }

    $conn -> freeConnection();
    return $userList;
  }

  /**
   * Method used to authorize a user to access the web app. Method updates the
   * corresponding isValidated column value to TRUE (1) in the db for the passed
   * in email/username.
   *
   * @param $email - string value representing the corresponding email/username
   * to authorize
   * @return $isCommit - boolean; returns TRUE if user authorization is committed
   */
  public static function authorizeUser($email)
  {
    $isCommit = FALSE;
    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();

    $updateSql = "UPDATE Users";
    $updateSql .= "   SET isValidated = 'YES'";
    $updateSql .= " WHERE emailAddress = '{$email}'";

    // update existing user record in the database
    $isCommit = $conn -> executeQuery($updateSql);
    $conn -> freeConnection();

    return $isCommit;
  }

  public static function updateUser($email, $fName, $lName, $userType, $emailOptIn, $newPass)
  {
    $isCommit = FALSE;

    $conn = new MySqlConnect();
    $ts = $conn -> getCurrentTs();

    $email = $conn -> sqlCleanup($email);
    $fName = $conn -> sqlCleanup($fName);
    $lName = $conn -> sqlCleanup($lName);
    $emailOptIn = $conn -> sqlCleanup($emailOptIn);

    // start building the UPDATE statement
    $updateSql = "UPDATE Users";
    $updateSql .= "  SET fName = '{$fName}',";
    $updateSql .= "      lName = '{$lName}',";
    $updateSql .= "      userType = '{$userType}',";
    $updateSql .= "      emailOptIn = '{$emailOptIn}',";

    // check for the new password and insert the hash value
    if ($newPass != null)
    {
      $newPass = $conn -> sqlCleanup($newPass);
      $hash = Users::encodePassword($newPass);
      $updateSql .= "      password = '{$hash}',";
    }
    $updateSql .= "      updateDate = '{$ts}'";
    $updateSql .= "WHERE emailAddress = '{$email}'";

    // update existing submission record in the database
    $isCommit = $conn -> executeQuery($updateSql);
    $conn -> freeConnection();

    return $isCommit;
  }

  /**
   * Method used to retrieve user properties for a corresponding email/username.
   *
   * @param $email - string value of the email/username record to retrieve
   * @return $userPropsArray - associative array containing the column names as
   * $key and column values as $value
   */
  public static function getUserProperties($email)
  {
    $userPropsArray = array();

    $conn = new MySqlConnect();

    $sql = "SELECT fName, lName, userType, emailOptIn, isValidated FROM Users WHERE emailAddress = '{$email}'";

    // update existing submission record in the database
    $result = $conn -> executeQueryResult($sql);
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
      foreach ($row as $key => $value)
      {
        // access the password value in the db
        $userPropsArray[$key] = $value;
      }
    }

    $conn -> freeConnection();
    return $userPropsArray;
  }

  /**
   * Method used to bring retrieve a multi-dimensional array of associative
   * arrays of the user type IDs ($key) and user type names ($value). First
   * dimension key is an integer index, second dimension key is column name
   *
   * @return $userTypesArray - multi-dimensional array of associative arrays of
   * userTypeID values as the key
   * and userTypeName values as the value
   */
  public static function getUserTypesArray()
  {
    $userTypesArray = array();

    $conn = new MySqlConnect();

    $sql = "SELECT userTypeName FROM userTypes";

    // update existing submission record in the database
    $result = $conn -> executeQueryResult($sql);
    while ($row = mysql_fetch_array($result, MYSQLI_ASSOC))
    {
      // assign the primary key value to the name
      array_push($userTypesArray, $row);
    }

    $conn -> freeConnection();
    return $userTypesArray;
  }

}
?>