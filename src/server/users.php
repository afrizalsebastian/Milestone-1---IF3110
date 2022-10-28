<?php

class Users
{
    private $user_id;
    private $email;
    private $username;
    private $isAdmin;
    private $isAuthenticated;

    public function __construct(){
        $this->user_id = NULL;
        $this->email = NULL;
        $this->username = NULL;
        $this->isAdmin = NULL;
        $this->isAuthenticated = FALSE;
    }

    public function isPasswordValid(string $pass):bool
    {
        $valid = TRUE;
        $passLen = mb_strlen($pass);
        
        if($passLen < 8)
        {
            $valid = FALSE;
        }

        return $valid;
    }

    function getEmail(): string
    {
        return $this->email;
    }

    function getUserId(): int
    {
        return $this->user_id;
    }

    function getIsAdmin() : int
    {
        return $this->isAdmin;
    }

    public function login(string $username, string $passwd): bool
    {
        global $pdo;

        $username = trim($username);
        $passwd = trim($passwd);

        if (!$this->isPasswordValid($passwd))
        {
            return FALSE;
        }

        $query = 'SELECT * FROM binotify.users WHERE (username = :username)';

        $values = array(':username' => $username);

        try
        {
            $res = $pdo->prepare($query);
            $res->execute($values);
        }
        catch (PDOException $e)
        {
            throw new Exception('Database query error');
        }

        $row = $res->fetch(PDO::FETCH_ASSOC);

        if (is_array($row))
        {
            if (password_verify($passwd, $row['password']))
            {
                $this->user_id = intval($row['user_id'], 10);
                $this->email = $row['email'];
                $this->username = $row['username'];
                $this->isAdmin = $row['isAdmin'];
                $this->isAuthenticated= TRUE;
                return TRUE;
            }
        }

        return FALSE;
    }

    public function register(string $email, string $username, string $passwd):int
    {
        global $pdo;

        $email = trim($email);
        $username = trim($username);
        $passwd = trim($passwd);
        $isAdmin = 0;

        if (!$this->isPasswordValid($passwd))
        {
            throw new Exception('Password Too Short!');
        }
        
        $query = "INSERT INTO binotify.users (email, username, password, isAdmin) VALUES (:email, :username, :passwd, :isAdmin)";
        $hash = password_hash($passwd, PASSWORD_DEFAULT);

        $values = array(':email' => $email, ':username' => $username, ":passwd" => $hash, ":isAdmin" => $isAdmin);

        try
        {
            $res = $pdo->prepare($query);
            $res->execute($values);
        }
        catch (PDOException $e)
        {
            throw new Exception('Database Query Error');
        }

        $this->user_id = $pdo->lastInsertId();
        $this->email = $email;
        $this->username = $username;
        $this->isAdmin = $isAdmin;
        $this->isAuthenticated= TRUE;

        return $pdo->lastInsertId();
    }
}