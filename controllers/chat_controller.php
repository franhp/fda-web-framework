<?php

include_once '../settings.php';
$settings = new Settings();
$settings->bootstrap();
$chat = new Chat();
$user = new Users();
/**
 * Enviar un msg al echo server
 */
if (isset($_POST['msg_sent'])) {
    echo $chat->sendMessage( $_SESSION['username'], $_POST['to'], $_POST['msg_sent']);
}

/**
 * Enviar un comando al server
 */
if (isset($_POST['command'])) {
    echo $chat->sendCommand($_POST['line'], $_POST['room']);
}

/**
 * Recibir msg del server
 */
if (isset($_POST['msg_req'])) {
    $resultado = $chat->getMessages($_POST['to']);
    if (!empty($resultado)) {
        $xml = @simplexml_load_string($resultado);
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
 * Lista de salas
 */
if (isset($_POST['listRooms'])) {
    $rooms = $chat->listRooms();
    if ($rooms) {
        echo $rooms;
    }

    else
        echo "ERROR";
}

/**
 * Crear una sala
 */
if (isset($_POST['createRoom'])) {
    if ($chat->createRoom($_POST['nameRoom'], $_POST['descriptionRoom'])) {
        $chat->updateServerRooms();
        echo "OK";
    }

    else
        echo "ERROR";
}

/**
 * Actualitza una sala
 */
if (isset($_POST['updateRoom'])) {

    if (empty($_POST['roomid'])) {
        if ($chat->createRoom($_POST['nameRoom'], $_POST['descriptionRoom'])) {
            $chat->updateServerRooms();
            echo "OK";
        }
        else
            echo "ERROR";
    } else {
        if ($chat->updateRoom($_POST['roomid'], $_POST['nameRoom'], $_POST['descriptionRoom'])) {
            $chat->updateServerRooms();
            echo "OK";
        }
        else
            echo "ERROR";
    }
}

/**
 * Borra una sala
 */
if (isset($_POST['deleteRoom'])) {
    if ($chat->deleteRoom($_POST['deleteRoom'])) {
        $chat->updateServerRooms();
        echo "OK";
    }
    else
        echo "ERROR";
}

/**
 * Inserta un usuario en una sala
 */
if (isset($_POST['insertUserRoom'])) {
    if ($chat->insertUserRoom($_POST['roomid'], $_POST['userid'])) {
        $chat->updateServerRooms();
        echo "OK";
    }

    else
        echo "ERROR";
}

/**
 * Borra un usuario de una sala
 */
if (isset($_POST['removeUserRoom'])) {
    if ($chat->removeUserRoom($_POST['roomid'], $_POST['userid'])) {
        $chat->updateServerRooms();
        echo "OK";
    }

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