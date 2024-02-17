<?php

namespace MyApp\Classes\Session;

class Session
{
    public $id;
    public $userName;
    public $role;
    public $message;
    public $email;

    public function __construct()
    {
        session_start();
        $this->id = $_SESSION['id'] ?? 0;
        $this->userName = $_SESSION['username'] ?? '';
        $this->role = $_SESSION['role'] ?? 'member';
        $this->message = $_SESSION['message'] ?? '';
        $this->email = $_SESSION['email'] ?? '';
    }

    public function createMember(array $member)
    {
        session_regenerate_id(true);
        $_SESSION['id'] = $member['id'];
        $_SESSION['username'] = $member['name'];
        $_SESSION['role'] = $member['role'];
    }

    //SET SESSION MESSAGE
    public function setMessage(string $message)
    {
        $_SESSION['message'] = $message;
    }

    //session clear message
    public function clearMessage()
    {
        unset($_SESSION['message']);
    }
    public function update($member)
    {
        $this->createMember($member);
    }

    //SET NEW REGISTERED MEMBER EMAIL IN THE SESSION
    public function setEmail(string $email)
    {
        $_SESSION['email'] = $email;
    }
    public function clearEmail()
    {
        $_SESSION['email'] = '';
    }
    public function delete()
    {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 2400,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
        session_destroy();
    }
}
