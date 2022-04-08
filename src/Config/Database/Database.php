<?php

namespace Codevia\AppConfigurator\Config\Database;

use Codevia\AppConfigurator\Config\Database\DBSM\DBMSInterface;
use Codevia\AppConfigurator\Config\Database\DBSM\MySQL;
use Codevia\AppConfigurator\Config\Database\DBSM\SQLite;
use Codevia\AppConfigurator\ConsoleHelper\AskForChoiceTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForConfirmationTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForDataInterface;
use JsonSerializable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class Database implements AskForDataInterface, JsonSerializable
{
    use AskForChoiceTrait;
    use AskForConfirmationTrait;

    private array $dbms = [];

    public function __construct(array $dbms = [])
    {
        foreach($dbms as $key => $connection) {
            if ($connection['type'] === 'mysql') {
                $this->dbms[$key] = new MySQL($connection);
            }

            if ($connection['type'] === 'sqlite') {
                $this->dbms[$key] = new SQLite($connection);
            }
        }
    }

    public function jsonSerialize(): mixed {
        return $this->dbms;
    }

    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void {
        $action = null;

        do {
            $action = $this->askForChoice(
                $input,
                $output,
                $questionHelper,
                'What do you want to do?',
                [
                    1 => 'Add a new connection',
                    2 => 'Remove a connection',
                    3 => 'Edit a connection',
                    4 => 'Show all connections',
                    99 => 'Exit',
                ],
            );
        } while ($action === null);

        // Add a new connection
        if ($action === 1) {
            $type = $this->askForChoice(
                $input,
                $output,
                $questionHelper,
                'What type of database do you want to add?',
                [
                    1 => 'MySQL',
                    2 => 'SQLite',
                ],
            );

            /** @var DBMSInterface */
            $dbms = match ($type) {
                1 => new MySQL(),
                2 => new SQLite(),
            };

            $dbms->askForData($input, $output, $questionHelper);
            $this->dbms[$dbms->getAlias()] = $dbms;
        }

        // Remove a connection
        if ($action === 2) {
            $alias = $this->askForChoice(
                $input,
                $output,
                $questionHelper,
                'Which connection do you want to remove?',
                array_keys($this->dbms),
            );

            $confirm = $this->askForConfirmation(
                $input,
                $output,
                $questionHelper,
                sprintf('Are you sure you want to remove the connection "%s"?', $alias),
            );

            if ($confirm) {
                unset($this->dbms[$alias]);
            }
        }

        if ($action === 4) {
            foreach ($this->dbms as $dbms) {
                $output->writeln(' - ' . $dbms->getAlias());
            }
        }
    }
}
