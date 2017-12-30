<?php

  function db_connect()
  {
      static $connection;
      if (!isset($connection)) {
        $host = 'localhost';
          $username = '';
          $password = '';
          $dbname = '';
          $connection = mysqli_connect($host, $username, $password, $dbname);
      }
      if ($connection === false) {
          return mysqli_connect_error();
      }

      return $connection;
  }


function db_query($query)
{
    $connection = db_connect();
    $result = mysqli_query($connection, $query);

    return $result;
}




function db_affected()
{
    $connection = db_connect();
    $affected = mysqli_affected_rows($connection);

    return $affected;
}


function db_close()
{
    $connection = db_connect();
    mysqli_close($connection);
}



function db_error()
{
    $connection = db_connect();

    return mysqli_error($connection);
}



function db_quote($value)
{
    $connection = db_connect();

    return "'".mysqli_real_escape_string($connection, $value)."'";
}
