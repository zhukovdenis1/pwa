<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOStatement;

final class Db extends PDO
{
    private array $debugData = array();
    private static ?Db $instance = null;

    private function __construct(string $dsn, string $username, string $password, array $options = array())
    {
        try {
            parent::__construct($dsn, $username, $password, $options);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (\Exception $exception) {
            die('No database connection');
        }
    }

    public static function getInstance(): Db
    {
        if (!static::$instance) {
            $config = getConfig();
            $dsn = 'mysql:dbname=' . $config->get('db.default.name') . ';host=' . $config->get('db.default.host');
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $config->get('db.default.charset') . "'");
            static::$instance = new self(
                $dsn,
                $config->get('db.default.user'),
                $config->get('db.default.password'),
                $options
            );
        }

        return static::$instance;
    }

    public function getDebugData(): array
    {
        return $this->debugData;
    }

    public function query(string $statement, ?int $fetchMode = null, mixed ...$fetch_mode): PDOStatement|false
    {
        $timeStart =  microtime(true);
        $result = parent::query($statement);

        $time = microtime(true) - $timeStart;
        $this->debugData[] = array(
            'sql' => $statement,
            'data' => [],
            'time' => $time,
            'error' => $result === false ? ['','','ERROR IN: ' . $statement ] : ['','',''],
        );

        return $result;
    }

    public function prepare(string $statement, array $driver_options = array()): PDOStatement|false
    {

        return parent::prepare($statement, $driver_options);
    }

    public function execute(PDOStatement $sth, array $data = array()): bool
    {
        $timeStart =  microtime(true);
        $result = $sth->execute($data);
        $time = microtime(true) - $timeStart;
        $this->debugData[] = array(
            'sql' => $sth->queryString,
            'data' => $data,
            'time' => $time,
            'error' => $sth->errorInfo()
        );

        return $result;
    }

    private function prExecute(string $statement, array $data = array()): false|PDOStatement
    {
        $sth = $this->prepare($statement);
        $this->execute($sth, $data);
        return $sth;
    }


    public function getOne(string $query, array $data = array()): array|false
    {
        /*$sth = $this->prepare($query);
        $sth->execute($data);*/
        $sth = $this->prExecute($query, $data);
        $data = $sth->fetch();
        $sth->closeCursor();
        return $data;
    }

    public function getAll(string $query, array $data = array()): array|false
    {
        /*$sth = $this->prepare($query);
        $sth->execute($data);*/
        $sth = $this->prExecute($query, $data);
        $data = $sth->fetchAll();
        $sth->closeCursor();
        return $data;
    }

    /**
     * @param $table
     * @param $data
     * @return array
     */
    public function save(string $table, array $data): bool
    {
        $keys = array();
        $values = array();

        foreach ($data as $k => $v) {
            if ($v == 'NOW()') {
                array_push($keys, '`' . $k . '`');
                array_push($values, "NOW()");
            } else {
                array_push($keys, '`' . $k . '`');
                array_push($values, "?");
            }
        }

        $keyStr = '(' . implode(',', $keys) . ')';
        $valStr = '(' . implode(',', $values) . ')';

        $query = 'INSERT INTO `' . $table . '` ' . $keyStr . ' VALUES ' . $valStr;
        $sth = $this->prepare($query);
        $result = $this->execute($sth, array_values($data));
        $sth->closeCursor();
        return $result;
    }

    /**
     * @param $table
     * @param $data
     * @param $tail
     * @return array
     */
    public function edit(string $table, array $data, string $tail): int
    {
        $set = 'SET';
        $sep = ' ';

        foreach ($data as $k => $v) {
            if ($v == 'NOW()') {
                $set .= $sep . '`' . $k . '`= NOW()';
                unset($data[$k]);
            } else {
                $set .= $sep . '`' . $k . '`= ?';
            }

            $sep = ', ';
        }

        $query = 'UPDATE `' . $table . '` ' . $set . ' ' . $tail;

        $sth = $this->prepare($query);
        $this->execute($sth, array_values($data));
        $count = $sth->rowCount();
        $sth->closeCursor();
        return $count;
    }
}
