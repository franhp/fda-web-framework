<?php

/**
 * Chat Class para el chat de Xinxat
 * 
 * Esta clase permite gestionar todo lo relacionado <br>
 * con el chat, salas, usuarios, etc.
 * @author Hector Costa
 * @version 1.0
 */
class Chat {

    var $db;
    var $settings;

    public function __construct() {
        $this->db = &$GLOBALS['db'];
        $this->settings = new Settings();
    }

    /**
     * Lista las rooms de la bd
     * @return stdObject
     */
    public function listRooms() {
        $this->db->query("select * from rooms");
        return $this->db->obj();
    }

    /**
     * Lista los usuarios de la bd
     * @return stdObject
     */
    public function listUsers() {
        $this->db->query("select id, username from users order by username");
        return $this->db->obj();
    }

    /**
     * Lista las salas id+nombre donde accede un usuario
     * @return stdObject
     */
    public function listUserRooms($id) {
        if (is_numeric($id)) {
            $this->db->query("select rooms.roomid as roomid, rooms.name as roomname FROM rooms
                                       LEFT JOIN access ON access.roomid=rooms.roomid
                                       WHERE access.userid = " . $id);
            return $this->db->obj();
        }
    }

    /**
     * Lista las salas id+nombre donde no accede un usuario
     * @return stdObject
     */
    public function listUserNoRooms($id) {
        if (is_numeric($id)) {
            $this->db->query("select rooms.roomid as roomid, rooms.name as roomname from rooms where roomid not in (select roomid from access
                                        where userid = " . $id . ")");
            return $this->db->obj();
        }
    }

    /**
     * Crea una room en la bd
     * @param $name
     * @param $description
     * @return boolean
     */
    public function createRoom($name, $description) {
        if ($name != "" && $description != "") {
            $name = $this->db->clean($name);
            $description = $this->db->clean($description);
            $this->db->query("insert into rooms (name, description) values ('" . $name . "', '" . $description . "')");

            if ($this->getRoomId($name)) {
                return true;
            }
            else
                return false;
        }else
            return false;
    }

    /**
     * Actualitza una room de la bd
     * @param $id
     * @return boolean
     */
    public function updateRoom($id, $name, $description) {
        if ($name != "" && $description != "") {
            $id = $this->db->clean($id);
            $name = $this->db->clean($name);
            $description = $this->db->clean($description);

            if (is_numeric($id)) {
                $this->db->query("update rooms set name='" . $name . "', description ='" . $description . "' where roomid = $id");

                if ($this->getRoomId($name)) {
                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }else
            return false;
    }

    /**
     * Inserta un usuario en una sala
     * @param $roomid
     * @param $userid
     * @return boolean
     */
    public function insertUserRoom($roomid, $userid) {

        if (is_numeric($roomid) && is_numeric($userid)) {

            if (!$this->getAccess($roomid, $userid)) {

                $this->db->query("insert into access (roomid, userid, state, role) values (" . $roomid . ", " . $userid . ", 1, 1)");

                if ($this->getAccess($roomid, $userid)) {
                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }
        else
            return false;
    }

    /**
     * Borra un usuario de una sala
     * @param $roomid
     * @param $userid
     * @return boolean
     */
    public function removeUserRoom($roomid, $userid) {

        if (is_numeric($roomid) && is_numeric($userid)) {

            if ($this->getAccess($roomid, $userid)) {

                $this->db->query("delete from access where roomid = " . $roomid . " AND userid = " . $userid);

                if (!$this->getAccess($roomid, $userid)) {
                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }
        else
            return false;
    }

    /**
     * Borra una room de la bd
     * @param $id
     * @return boolean
     */
    public function deleteRoom($id) {
        $id = $this->db->clean($id);

        if (is_numeric($id)) {
            $this->db->query("delete from rooms where roomid = $id");

            if (!$this->getRoomId($name)) {
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    /**
     * Retorna la id de una room
     * @param $name
     * @return roomid
     */
    public function getRoomId($name) {
        $this->db->query('select roomid from rooms where name=\'' . $name . '\'');

        $result = $this->db->obj();
        foreach ($result as $room)
            $roomid = $room->roomid;
        if (empty($roomid))
            return false;
        else
            return $roomid;
    }

    /**
     * Retorna la id de un access
     * @param $roomid
     * @param $userid
     * @return $accessid
     */
    public function getAccess($roomid, $userid) {
        $this->db->query('select accessid from access where roomid = ' . $roomid . ' AND userid=' . $userid . '');

        $result = $this->db->obj();
        foreach ($result as $access)
            $accessid = $access->accessid;
        if (empty($accessid))
            return false;
        else
            return $accessid;
    }

    /**
     * Actualiza la bd del server
     */
    public function updateServerRooms() {
        $url = 'http://projecte-xinxat.appspot.com/updateRooms';

        // abrimos la conexion
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, false);

        curl_exec($handler);
        curl_close($handler);
    }

    /**
     * Actualiza la bd del server
     */
    public function updateDB() {
        $url = 'http://projecte-xinxat.appspot.com/updateDB';

        // abrimos la conexion
        $handler = curl_init();

        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, false);

        curl_exec($handler);
        curl_close($handler);
    }

    /*
     * Lista los usuarios del roster / o un canal
     */

    public function roster($room="") {
        $url = 'http://projecte-xinxat.appspot.com/roster?room=' . $room;
        // abrimos la conexion
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_HEADER, false);
        $result = curl_exec($handler);
        $xml = @simplexml_load_string($result);
        curl_close($handler);
        echo '<select style="width: 100%; height: 277px;" id="username" multiple="multiple" size="1">';
        if (trim($result) != "") {
            if ($xml) {
                foreach ($xml as $presence) {
                    $name = $this->xml_attribute($presence, 'from');
                    $users[] = array('name' => $name, 'show' => (string) $presence->show, 'status' => (string) $presence->status,);
                    sort($users);
                }
            }
            foreach ($users as $user) {
                if ($user['show'] == "online") {
                    if ($user['name'] == $_SESSION['username'])
                        echo '<option value="' . $user['name'] . '" style="color: green;" selected>' . $user['name'] . '</option>';
                    else
                        echo '<option value="' . $user['name'] . '" style="color: green;">' . $user['name'] . '</option>';
                }

                else
                    echo '<option value="' . $user['name'] . '" style="color: grey;">' . $user['name'] . '</option>';
            }
        }
        echo '</select>';
    }

    /*
     * Enviar msg al server
     */

    public function sendMessage($from, $target, $message) {

        $resultado = null;

        if (isset($target)) {
            $type = "chat";

            // rooms empiezan por @
            if ($target[0] == '@') {
                $type = "groupchat";
            }

            $url = 'http://projecte-xinxat.appspot.com/messages';
            $xmlMessage = '<message to="' . $target . '" from="' . $from . '" type="' . $type . '"><body>' . htmlspecialchars($message, ENT_QUOTES) . '</body></message>';

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
            $resultado = curl_exec($handler);

            // cerramos la conexion
            curl_close($handler);
        }

        return $resultado;
    }

    /*
     * Enviar comando al server
     */

    public function sendCommand($command, $target) {

        $resultado = null;

        if (isset($command)) {

            $user = new Users();

            if (strrpos($command, "leave")) {
                $userid = $user->getUserId($_SESSION['username']);
                $roomid = $this->getRoomId($target);
                if ($this->getAccess($roomid, $userid)) {
                    return('You cant leave this room');
                }
            }
            if (strrpos($command, "leave") || strrpos($command, "ban") || strrpos($command, "kick") || strrpos($command, "invite"))
                $to = $target;
            else
                $to = $_SESSION['username'];

            $url = 'http://projecte-xinxat.appspot.com/messages';
            $xmlMessage = '<message to="' . $to . '" from="' . $_SESSION['username'] . '" type="system"><body>' . htmlspecialchars($command, ENT_QUOTES) . '</body></message>';
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
        }

        return $resultado;
    }

    /*
     * Leer la pila del usuario en el server
     */

    public function getMessages($target) {

        $resultado = null;

        if (isset($target)) {

            if (!isset($_SESSION['online'])) {
                $url = 'http://projecte-xinxat.appspot.com/messages?to=' . $target . "&token=" . $_SESSION['token'] . "&show=online&status=chating";
                $_SESSION['online'] = true;
            } else {
                $url = 'http://projecte-xinxat.appspot.com/messages?to=' . $target . "&token=" . $_SESSION['token'];
                if (date('s') > 40)
                    $url.= "&show=online&status=chating";
            }

            // abrimos la conexion
            $handler = curl_init();

            //configuramos la url, el numero de parametros POST y los datos POST respectivos
            curl_setopt($handler, CURLOPT_URL, $url);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handler, CURLOPT_HEADER, false);
            //echo $url;
            $resultado = curl_exec($handler);

            curl_close($handler);
        }

        return $resultado;
    }

    /**
     * Retorna un atributo de un xml object
     */
    public function xml_attribute($object, $attribute) {
        if (isset($object[$attribute]))
            return (string) $object[$attribute];
    }

}
