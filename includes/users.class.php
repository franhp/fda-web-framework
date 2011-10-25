<?php

/**
 * Users Class para FilaDeAtras Framework
 * 
 * Permite gestionar los usuarios
 * 
 * @author Fran Hermoso <franhp@franstelecom.com>
 * @version 1.0
 *
 */
class Users {

    var $username;
    var $role;
    var $id;

    public function __construct() {
        $db = &$GLOBALS['db'];

        if (isset($_SESSION['userid']) && !empty($_SESSION['userid'])) {
            $db->query('select id,username,role
						 from users 
						 where id=' . $_SESSION['userid']);
            $result = $db->obj();
        } else if (isset($_COOKIE['remember'])) {
            $db->query('select id,username,role
                         from users 
                         where username=\'' . $db->clean($_COOKIE['username']) . '\'
                         and
                         password=\'' . $db->clean($_COOKIE['password']) . '\'');
            $result = $db->obj();
        }
        if (!empty($result)) {
            foreach ($result as $user) {
                $this->username = $user->username;
                $this->role = $user->role;
                $this->id = $user->id;
            }
        }
    }

    /**
     * Comprueba si el usuario existe y si no lo crea
     * 
     * @param  $nickname
     * @param  $password
     * @param  $email
     * 
     * @return
     */
    public function createUser($nickname, $password, $email) {
        $db = &$GLOBALS['db'];
        $db->query("insert into users (username,password, IP, email, role) values (
                              '" . $db->clean(strtolower($nickname)) . "', 
                              '" . $db->clean($db->clean(md5($password . 'V1V4fDA'))) . "',
                              '" . $_SERVER['REMOTE_ADDR'] . "', 	
                              '" . $db->clean(strtolower($email)) . "',
                                   1 )");
        if ($this->getUserId($nickname))
            return true;
        else
            return false;
    }

    /**
     * Actualiza el perfil de un usuario
     * @param  $name
     * @param  $lastname
     * @param  $birthdate
     * @param  $location
     * 
     * @return
     */
    public function updateUser($name, $lastname, $birthdate, $location) {
        $db = &$GLOBALS['db'];

        $db->query('UPDATE users
                        SET name = \'' . $db->clean($name) . '\', 
                            lastname = \'' . $db->clean($lastname) . '\', 
                            birthdate = \'' . $db->clean($birthdate) . '\', 
                            location = \'' . $db->clean($location) . '\'
                        WHERE 
                        id= '. $_SESSION['userid'] );
        
        return true;
    }

    /**
     * Lista todos los usuarios
     */
    public function listUsers() {
        $db = &$GLOBALS['db'];

        $db->query('select id,username,password,name,lastname,IP,location,birthdate,email from users');
        echo '<br>Number of users = ' . $db->num_rows();
        $db->debug('result');
    }

    public function delUser($idUser) {
        
    }

    /**
     * Retorna la id del usuario
     * @param $username 
     * @return userid
     */
    public function getUserId($username) {
        $db = &$GLOBALS['db'];
        $db->query('select id from users where username=\'' . $username . '\'');
        $result = $db->obj();
        foreach ($result as $user)
            $userid = $user->id;
        if (empty($userid))
            return false;
        else
            return $userid;
    }

    /**
     * Retorna los permisos del usuario
     * @param $username
     * @return role
     */
    public function getUserRole() {
        return $this->role;
    }
    
    /**
     * Retorna la info del usuario
     * @return userid
     */
    public function getUserInfo($userid) {
        $db = &$GLOBALS['db'];
        $db->query('select name, lastname, location, birthdate from users where id= '.$userid );
        return $db->getArray();
    }
}
?>