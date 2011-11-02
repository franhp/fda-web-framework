<?php

include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$chat = new Chat();
/**
 * Enviar un msg al echo server
 */ 
if(isset($_POST['msg_sent'])) {

    //$var = htmlspecialchars($_POST['msg_sent'], ENT_QUOTES);
    // empezamos creando un array con todas las variables GET

    if (isset($_POST['to'])) {
        $url = 'http://projecte-xinxat.appspot.com/messages';
        $xmlMessage = '<message to="' . $_POST['to'] . '" from="' . $_SESSION['username'] . '" type="chat">
                            <body>' . htmlspecialchars($_POST['msg_sent'], ENT_QUOTES) . '</body>
                        </message>';

        $fields = array(
            'msg' => $xmlMessage,
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
 * Recibir msg del server
 */
if (isset($_POST['msg_req'])) {

    if (isset($_POST['to'])) {
        $url = 'http://projecte-xinxat.appspot.com/messages?to=' . $_POST['to'] . "&token=" . $_SESSION['token']."&status=online&show=chat";

        // abrimos la conexion
        $handler = curl_init();

        //configuramos la url, el numero de parametros POST y los datos POST respectivos
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, false);
        //echo $url;
        $resultado = curl_exec($handler);
        $xml = @simplexml_load_string($resultado);
        if (trim($resultado) != "WRONG") {
            if (trim($resultado) == "NOEXISTS") {
                echo "NOEXISTS";
            } else {
                if ($xml->show == 'null' || $xml->show == 'chat' || $xml->show == 'dnd' || $xml->show == 'away')
                    echo "NULL";
                else {
                    foreach ($xml as $message) {
                        $from = xml_attribute($message, 'from');
                        echo "<p><b>&lt;" . $from . "&gt;</b> " . $message->body . "</p>";
                    }
                }
            }
        } else {
            echo trim($resultado);
        }
        curl_close($handler);
    }
    else
        echo trim($resultado);
}

/**
 * Enviar un comando al server
 */
if (isset($_POST['command'])) {

    if (isset($_POST['line'])) {
        $line = substr($_POST['line'], 0, strlen($_POST['line'])-1);
        $url = 'http://projecte-xinxat.appspot.com/messages';
        $xmlMessage = '<message to="' . $_SESSION['username'] . '" from="' . $_SESSION['username'] . '" type="system">
                            <body>/' . htmlspecialchars($line, ENT_QUOTES) . '</body>
                        </message>';
        $fields = array(
            'msg' => $xmlMessage,
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
 * Borra una sala
 */
if (isset($_POST['deleteRoom'])) {
    if ($chat->deleteRoom($_POST['deleteRoom']))
        echo "OK";
    else
        echo "ERROR";
}

/**
 * Inserta un usuario en una sala
 */
if (isset($_POST['insertUserRoom'])) {
    if ($chat->insertUserRoom($_POST['roomid'], $_POST['userid']))
        echo "OK";
    else
        echo "ERROR";
}

/**
 * Borra un usuario de una sala
 */
if (isset($_POST['removeUserRoom'])) {
    if ($chat->removeUserRoom($_POST['roomid'], $_POST['userid']))
        echo "OK";
    else
        echo "ERROR";
}

/***
 * Roster 
 */
if (isset($_POST['roster'])){
    $roster = htmlspecialchars($_POST['roster'], ENT_QUOTES);
    echo $chat->roster($roster);
}

/**
 * Retorna un atributo de un xml object
 */
function xml_attribute($object, $attribute) {
    if (isset($object[$attribute]))
        return (string) $object[$attribute];
}