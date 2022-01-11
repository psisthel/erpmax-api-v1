<?php

  //Abrir conexion a la base de datos
  function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']}", $db['username'], $db['password']);

          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }


 //Obtener parametros para updates
 function getParams($input)
 {
    $filterParams = [];
    foreach($input as $param => $value)
    {
            $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
	}

  //Asociar todos los parametros a un sql
	function bindAllValues($statement, $params)
  {
		foreach($params as $param => $value)
    {
				$statement->bindValue(':'.$param, $value);
		}
		return $statement;
   }

  function getToken() {

    echo "Sha1 " . sha1("Afrt$567!kl") . "<br>";
    echo "Unique " . sha1(uniqid(rand(),true));

    // guardar cookie
    setcookie("usuario","Percy",time()+(60*60*24*31),"/");

    // ler cookie
    echo "valor del cookie: " . $_COOKIE['usuario'];

    // limpar cookie
    setcookie("usuario","",time()-1,"/");

  }

 ?>