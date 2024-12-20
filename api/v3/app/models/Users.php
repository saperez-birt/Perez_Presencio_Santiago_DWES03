<?php

class Users {
    private $dataFile = '../data/users.json';
    private $name;
    private $email;

    public function __construct($name = '', $email = '') {
        $this->name = $name;
        $this->email = $email;
    }

    public function getAllUsers() {
        $users = file_get_contents($this->dataFile);
        return json_decode($users, true);
    }

    public function getUserById($id) {
        $users = $this->getAllUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }

    public function registerUser() {
        $users = $this->getAllUsers();
        $user = [
            'id' => count($users) + 1,
            'name' => $this->name,
            'email' => $this->email
        ];
        $users[] = $user;
        return file_put_contents($this->dataFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    // Elimina todos los usuarios de la base de datos. Accion disponible solo como administrador.
    public function deleteAllUsers() {
        return file_put_contents($this->dataFile, json_encode([], JSON_PRETTY_PRINT));
    }

    public function deleteUserById($id) {
        $users = $this->getAllUsers();

        foreach ($users as $key=>$user) {
            if ($user['id'] == $id) {
                unset($users[$key]);
                return file_put_contents($this->dataFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
            }
        }
        return false;
    }

    public function updateUserById($id, $updatedData) {
        $users = $this->getAllUsers();

        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                $users[$key] = array_merge($user, $updatedData);
                return file_put_contents($this->dataFile, json_encode($users, JSON_PRETTY_PRINT));
            }
        }
        return false;
    }

    public function updateAllIds() {
        $users = $this->getAllUsers();
        //pasamos la referencia de cada usuario para poder modificar su id
        foreach ($users as $key => &$user) {
            $user['id'] = $key + 1;
        }
        return file_put_contents($this->dataFile, json_encode($users, JSON_PRETTY_PRINT));
    }  
}