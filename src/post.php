<?php
include "config.php";
include "utils.php";


$dbConn =  connect($db);

/*
  listar todos los posts o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
      // Mostrar un mensaje específico
      $sql = $dbConn->prepare("SELECT * FROM mensajes WHERE id=:id");
      $sql->bindValue(':id', $_GET['id']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
      exit();
  } else {
      // Mostrar todos los mensajes
      $sql = $dbConn->prepare("SELECT * FROM mensajes");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode($sql->fetchAll());
      exit();
  }
}


// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = $_POST;
  /**aca no mande el tiempo porque use timestamp que pone el tiempo auto cuando se
   *  envia a al base de datos, pero tambien lo actuliza al momento de modificarlo eso me toca con put ver si lo dejo o no*/
  $sql = "INSERT INTO mensajes (contenido, idRemitente, idDestinatario, estado)
          VALUES (:contenido, :idRemitente, :idDestinatario, :estado)";
  $statement = $dbConn->prepare($sql);
  bindAllValues($statement, $input);
  $statement->execute();
  $mensajeId = $dbConn->lastInsertId();
  if ($mensajeId) {
      $input['id'] = $mensajeId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      echo'mensaje creado con exito';
      exit();
  }
}

//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $_GET['id'];
    $statement = $dbConn->prepare("DELETE FROM mensajes WHERE id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    echo 'mensaje eliminado',
    exit();
}


//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = $_GET;

    $mensajeId = $input['id'];
    $fields = getParams($input);

    $sql = "
        UPDATE mensajes
        SET $fields
        WHERE id=:id";
    $statement = $dbConn->prepare($sql);
    $input['id'] = $mensajeId;
    bindAllValues($statement, $input);
    $statement->execute();
    header("HTTP/1.1 200 OK");
    echo'modificacion oki';
    exit();
}



//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>