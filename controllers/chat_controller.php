<?php

/**
 * Enviar un msg al echo server
 */
include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$chat = new Chat();

if (isset($_POST['msg_sent'])) {

    include_once '../includes/Array2XML.class.php';

    //$var = htmlspecialchars($_POST['msg_sent'], ENT_QUOTES);
    // empezamos creando un array con todas las variables GET

    if (isset($_POST['to'])) {
        $url = 'http://projecte-xinxat.appspot.com/messages';
        $xmlMessage = array(
            '@attributes' => array(
                'to' => $_POST['to'],
                'from' => $_SESSION['username'])
            , 'body' => htmlspecialchars($_POST['msg_sent'], ENT_QUOTES)
        );

        $xml = @Array2XML::createXML('message', $xmlMessage);

        $fields = array(
            'msg' => $xml->saveXML(),
            'token' => $_SESSION['token']
        );
    }

    // luego creamos nuestra string con los parametros separados con &
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    // abrimos la conexion
    $handler = curl_init();

    //configuramos la url, el numero de parametros POST y los datos POST respectivos
    curl_setopt($handler, CURLOPT_URL, $url);
    curl_setopt($handler, CURLOPT_POST, count($fields));
    curl_setopt($handler, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handler, CURLOPT_HEADER, false);

    // ejecutamos curl
    $resultado = curl_exec($handler);

    // cerramos la conexion
    curl_close($handler);

    echo $resultado;
}

/**
 * Recibir msg del echo server 
 */
if (isset($_POST['msg_req'])) {

    if (isset($_POST['to'])) {
        $url = 'http://projecte-xinxat.appspot.com/messages?to=' . $_POST['to'];
    }

    // abrimos la conexion
    $handler = curl_init();

    //configuramos la url, el numero de parametros POST y los datos POST respectivos
    curl_setopt($handler, CURLOPT_URL, $url);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handler, CURLOPT_HEADER, false);

    // ejecutamos curl
    $resultado = curl_exec($handler);

    // cerramos la conexion
    $xml = @simplexml_load_string($resultado);
    if ($xml->show == 'chat')
        echo "nullchat";
    else {
        echo $xml->body;
    }

    curl_close($handler);
    /* if (!similar_text($resultado, "EMPTY"))
      echo $resultado; */
}

/**
 * Lista de salas
 */
if (isset($_POST['listRooms'])) {
    $rooms = $chat->listRooms();
    if ($rooms)
        echo $rooms;
    else
        echo "ERROR";
}

/**
 * Crear una sala
 */
if (isset($_POST['createRoom'])) {
    if ($chat->createRoom($_POST['nameRoom'], $_POST['descriptionRoom']))
        echo "OK";
    else
        echo "ERROR";
}

/**
 * Actualitza una sala
 */
if (isset($_POST['updateRoom'])) {

    if (empty($_POST['roomid'])) {
        if ($chat->createRoom($_POST['nameRoom'], $_POST['descriptionRoom']))
            echo "OK";
        else
            echo "ERROR";
    } else {
        if ($chat->updateRoom($_POST['roomid'], $_POST['nameRoom'], $_POST['descriptionRoom']))
            echo "OK";
        else
            echo "ERROR";
    }
}

/**
 * Borrar una sala
 */
if (isset($_POST['deleteRoom'])) {
    if ($chat->deleteRoom($_POST['deleteRoom']))
        echo "OK";
    else
        echo "ERROR";
}

