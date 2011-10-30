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
                
                $this->db->query("delete from access where roomid = " . $roomid . " AND userid = " . $userid );

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

}
