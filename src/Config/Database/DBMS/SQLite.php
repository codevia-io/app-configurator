<?php

namespace Codevia\AppConfigurator\Config\Database\DBMS;

use Codevia\AppConfigurator\ConsoleHelper\AskForDataInterface;
use Codevia\AppConfigurator\ConsoleHelper\AskForNotEmptyValueTrait;
use JsonSerializable;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SQLite implements AskForDataInterface, DBMSInterface, JsonSerializable
{
    use AskForNotEmptyValueTrait;

    const TYPE = 'sqlite';

    private string $database = '';
    private string $alias = '';
    
    public function __construct(array $data = []) {
        $this->database = $data['database'] ?? '';
        $this->alias = $data['alias'] ?? '';
    }

    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void {
        $this->database = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the path to the SQLite database file you wish to use?',
            $this->database,
        );
        $this->alias = $this->askForNotEmptyValue(
            $input,
            $output,
            $questionHelper,
            'What is the alias you wish to use for this database?',
            $this->alias,
        );
    }

    public function jsonSerialize(): mixed {
        return [
            'type' => self::TYPE,
            'database' => $this->database,
            'alias' => $this->alias,
            'dsn' => $this->getDsn(),
        ];
    }

    public function getDsn(): string
    {
        return "sqlite:$this->database";
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