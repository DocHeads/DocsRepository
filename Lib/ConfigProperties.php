<?php
/**
 * Class set up for static references to configurable 
 * Config properties:
 * - Domain Name
 * - Db server
 * - Db name
 * - Db username
 * - Db password
 * - email server
 * - email username
 * - email password
 * - Timeout value
 * - Base uploads directory
 * 
 * Just use the static property declaration to access the property value, from other PHP scripts. Ex:
 * 
 *    $domain = Config::DomainName;
 */
 class ConfigProperties
{
  // ----------------------------
  //  BASIC APPLICATION SETTINGS
  // ----------------------------
  
  /**
   * URL domain name for the application
   */
  public static $DomainName = "localhost";
  
  /**
   * Base upload directory for file submissions;
   */
  public static $BaseUploadDirectory = "../uploads";
  
  /**
   * sets the application login session expiration time out value (INT) measured in minutes
   */
  public static $LoginTimeout = 15;
  
  // ----------------------------
  //      DATABASE SETTINGS
  // ----------------------------
  
  /**
   * MySQL database connenction servername
   */ 
  public static $DatabaseServerName = "localhost";
  
  /**
   * MySQL database connenction name
   */ 
  public static $DatabaseName = "docdatabase";
  
  /**
   * MySQL database connenction username
   */  
  public static $DatabaseUsername = "root";
  
  /**
   * MySQL database connenction password
   */ 
  public static $DatabasePassword = "";
  
  // ----------------------------
  //    EMAIL SERVER SETTINGS
  // ----------------------------
  
  /**
   * Email server used to send application emails
   */  
  public static $EmailServer = "mail1206.opentransfer.com";
  
  /**
   * Email server username credential
   */ 
  public static $EmailServerUsername = "briandunavent@qcsoftware.com";
  
  /**
   * Email password credential
   */
  public static $EmailServerPassword = "M4nt1c0re";
  
  /**
   * Help/Contact Us Form email recipient
   */
  public static $ContactUsFormRecipient = "b.dunavent@gmail.com";
  
}
?>