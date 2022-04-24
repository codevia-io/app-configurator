<?php

namespace Codevia\AppConfigurator\Config\Database\DBMS;

use Codevia\AppConfigurator\ConsoleHelper\AskForDataInterface;
use Codevia\AppConfigurator\ConsoleHelper\AskForHiddenValueTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForNotEmptyValueTrait;
use JsonSerializable;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MySQL implements AskForDataInterface, DBMSInterface, JsonSerializable
{
    use AskForNotEmptyValueTrait;
    use AskForHiddenValueTrait;
    
    const TYPE = 'mysql';

    private string $host = '';
    private string $user = '';
    private string $password = '';
    private string $database = '';
    private string $alias = '';
    
    public function __construct(array $data = []) {
        $this->host = $data['host'] ?? '';
        $this->user = $data['user'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->database = $data['database'] ?? '';
        $this->alias = $data['alias'] ?? '';
    }

    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void {
        $this->host = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the hostname of the MySQL server?',
            $this->host,
        );
        $this->user = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the username to connect to the MySQL server?',
            $this->user,
        );
        $this->password = $this->askForHiddenValue(
            $input,
            $output,
            $questionHelper,
            'What is the password to connect to the MySQL server?',
            $this->password,
        );
        $this->database = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the name of the database to use?',
            $this->database,
        );
        $this->alias = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the alias you want to give to this connection?',
            $this->alias,
        );
    }

    public function jsonSerialize(): mixed {
        return [
            'type' => self::TYPE,
            'host' => $this->host,
            'user' => $this->user,
            'password' => $this->password,
            'database' => $this->database,
            'alias' => $this->alias,
            'dsn' => $this->getDsn(),
        ];
    }

    public function getDsn(): string
    {
        return "mysql:host={$this->host};dbname={$this->database}";
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function setDatabase(string $database): self
    {
        $this->database = $database;
        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }
}
