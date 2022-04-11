<?php

namespace Codevia\AppConfigurator\Config;

use Codevia\AppConfigurator\Config\Database\Database;
use Codevia\AppConfigurator\ConsoleHelper\AskForChoiceTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForDataInterface;
use Codevia\AppConfigurator\ConsoleHelper\AskForNotEmptyValueTrait;
use JsonSerializable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class Application implements AskForDataInterface, JsonSerializable
{
    use AskForChoiceTrait;
    use AskForNotEmptyValueTrait;

    protected Database $database;

    public function __construct(array $data, $name = '') {
        $this->database = new Database($data['database'] ?? []);
        $this->setName(strlen($name) > 0 ? $name : $data['name']);
    }

    public function jsonSerialize(): mixed {
        return [
            'name' => $this->getName(),
            'database' => $this->database,
        ];
    }

    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void {
        do {
            do {
                $action = $this->askForChoice(
                    $input,
                    $output,
                    $questionHelper,
                    'What do you want to do?',
                    [
                        1 => 'Edit application name',
                        2 => 'Edit database configuration',
                        99 => 'Exit',
                    ],
                );
            } while ($action === null);

            // Edit application name
            if ($action === 1) {
                $this->setName($this->askForNotEmptyValue(
                    $input,
                    $output,
                    $questionHelper,
                    'What is the new name of the application?',
                    $this->getName(),
                ));
            }

            if ($action === 2) {
                $this->database->askForData($input, $output, $questionHelper);
            }
        } while ($action !== 99);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the database storing all connections
     * @return Database 
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }
}
