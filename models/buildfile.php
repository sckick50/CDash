<?php
/*=========================================================================

  Program:   CDash - Cross-Platform Dashboard System
  Module:    $Id$
  Language:  PHP
  Date:      $Date$
  Version:   $Revision$

  Copyright (c) 2002 Kitware, Inc.  All rights reserved.
  See Copyright.txt or http://www.cmake.org/HTML/Copyright.html for details.

     This software is distributed WITHOUT ANY WARRANTY; without even
     the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
     PURPOSE.  See the above copyright notices for more information.

=========================================================================*/
/** BuildError */
class BuildFile
{
  var $Type;
  var $Filename;
  var $md5;
  var $BuildId;

  // Insert in the database (no update possible)
  function Insert()
    {
    if(!$this->BuildId)
      {
      echo "BuildFile::Insert(): BuildId not set<br>";
      return false;
      }
      
    if(!$this->Type)
      {
      echo "BuildFile::Insert(): Type not set<br>";
      return false;
      }
    
    if(!$this->md5)
      {
      echo "BuildFile::Insert(): md5 not set<br>";
      return false;
      }
      
    if(!$this->Filename)
      {
      echo "BuildFile::Insert(): Filename not set<br>";
      return false;
      }

    $filename = pdo_real_escape_string($this->Filename);
    $type = pdo_real_escape_string($this->Type);
    $md5 = pdo_real_escape_string($this->md5);
    
    // Check if we already have a row
    $query = "SELECT buildid FROM buildfile WHERE buildid=".qnum($this->BuildId)." AND md5='".$md5."'";
    $query_result = pdo_query($query);
    if(!$query_result)
      {
      add_last_sql_error("BuildFile Insert",0,$this->BuildId);
      return false;
      }
    
    if(pdo_num_rows($query_result)>0)
      {
      return false;
      }
    
    $query = "INSERT INTO buildfile (buildid,type,filename,md5)
              VALUES (".qnum($this->BuildId).",'".$type."','".$filename."','".$md5."')";
    if(!pdo_query($query))
      {
      add_last_sql_error("BuildFile Insert",0,$this->BuildId);
      return false;
      }
      
    return true;
    } // end insert
    
  function MD5Exists()
    {
    $md5 = pdo_real_escape_string($this->md5);
    
    $query = "SELECT buildid FROM buildfile WHERE md5='".$md5."'";
    $query_result = pdo_query($query);
    if(!$query_result)
      {
      add_last_sql_error("BuildFile MD5Exists",0,$md5);
      return false;
      }
    
    if(pdo_num_rows($query_result)==0)
      {
      return false;
      }
    return true;
    } // end MD5Exists
    
}
?>
