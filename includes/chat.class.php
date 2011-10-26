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

                if (!$this->getRoomId($name)) {
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

}
