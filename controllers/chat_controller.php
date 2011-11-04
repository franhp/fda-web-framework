<?php

include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$chat = new Chat();
/**
 * Enviar un msg al echo server
 */
if (isset($_POST['msg_sent'])) {

    if (isset($_POST['to'])) {
        $type = "chat";

        // rooms empiezan por @
        if ($_POST['to'][0] == '@') {
            $type = "groupchat";
        }

        $to = $_POST['to'];
        $from = $_SESSION['username'];
        $url = 'http://projecte-xinxat.appspot.com/messages';
        $xmlMessage = '<message to="' . $_POST['to'] . '" from="' . $from . '" type="' . $type . '"><body>' . htmlspecialchars($_POST['msg_sent'], ENT_QUOTES) . '</body></message>';

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
        $url = 'http://projecte-xinxat.appspot.com/messages?to=' . $_POST['to'] . "&token=" . $_SESSION['token'] . "&show=online&status=chating";

        // abrimos la conexion
        $handler = curl_init();

        //configuramos la url, el numero de parametros POST y los datos POST respectivos
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, false);
        //echo $url;
        $resultado = curl_exec($handler);
        $xml = @simplexml_load_string($resultado);
        curl_close($handler);
        if (trim($resultado) != "WRONG") {
            if (trim($resultado) == "NOEXISTS") {
                echo "NOEXISTS";
            } else {
                if ($xml->show == 'null' || $xml->show == 'chat' || $xml->show == 'dnd' || $xml->show == 'away' || $xml->show == 'online') {
                    $arr[] = array('state' => "NULL");
                    header('Content-type: application/json');
                    echo json_encode($arr);
                } else {
                    foreach ($xml as $message) {
                        $from = xml_attribute($message, 'from');
                        $to = xml_attribute($message, 'to');
                        $type = xml_attribute($message, 'type');
                        $msg = (string) $message->body;

                        if ($from != $_SESSION['username'] || $type != "groupchat") {
                            $arr[] = array('from' => $from, 'to' => $to, 'type' => $type, 'msg' => $msg);
                        }
                        //echo "<p><b>&lt;" . $from . "&gt;</b> " . $message->body . "</p>";
                    }
                    if (!empty($arr)) {
                        header('Content-type: application/json');
                        echo json_encode($arr);
                    }
                }
            }
        } else if (trim($resultado) == "CANT") {
            $arr[] = array('state' => "CANT");
            header('Content-type: application/json');
            echo json_encode($arr);
        } else {
            $arr[] = array('state' => "WRONG");
            header('Content-type: application/json');
            echo json_encode($arr);
        }
    } else {
        $arr[] = array('state' => "WRONG");
        header('Content-type: application/json');
        echo json_encode($arr);
    }
}

/**
 * Enviar un comando al server
 */
if (isset($_POST['command'])) {
    
    if (isset($_POST['line'])) {
        
        if (strrpos($_POST['line'], "leave") || strrpos($_POST['line'], "ban") || strrpos($_POST['line'], "kick") || strrpos($_POST['line'], "invite") )
            $to = $_POST['room'];
        else
            $to = $_SESSION['username'];

        $url = 'http://projecte-xinxat.appspot.com/messages';
        $xmlMessage = '<message to="' . $to . '" from="' . $_SESSION['username'] . '" type="system"><body>' . htmlspecialchars($_POST['line'], ENT_QUOTES) . '</body></message>';
        $fields = array(
            'msg' => $xmlMessage,
            'token' => $_SESSION['token']
        );
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
        //var_dump($fields_string);
        $resultado = curl_exec($handler);

        // cerramos la conexion
        curl_close($handler);
        //echo $xmlMessage." ";
        echo $resultado;
       
        
    }
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

/* * *
 * Roster 
 */
if (isset($_POST['roster'])) {
    $room = htmlspecialchars($_POST['room'], ENT_QUOTES);
    echo $chat->roster($room);
}

/**
 * Retorna un atributo de un xml object
 */
function xml_attribute($object, $attribute) {
    if (isset($object[$attribute]))
        return (string) $object[$attribute];
}