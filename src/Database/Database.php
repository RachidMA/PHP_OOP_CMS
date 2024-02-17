<?php

namespace  MyApp\Classes\Database;

class Database extends \PDO
{


    public function __construct(string $dsn, string $db_user, string $db_password, array $option = [])
    {
        $default_option[\PDO::ATTR_DEFAULT_FETCH_MODE] = \PDO::FETCH_ASSOC;
        $default_option[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
        $default_option[\PDO::ATTR_EMULATE_PREPARES] = false;

        $option = array_replace($default_option, $option);
        parent::__construct($dsn, $db_user, $db_password, $option);
    }

    public function runSQL(string $sql, array $argument = [])
    {
        if (!$argument) {
            return $this->query($sql);
        }
        $statement = $this->prepare($sql);
        $statement->execute($argument);
        // var_dump('FROM DATABAASE');
        return $statement;
    }
}
