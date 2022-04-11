<?php

namespace Codevia\AppConfigurator\Command;

use Codevia\AppConfigurator\Config\Application;
use Codevia\AppConfigurator\Config\Config;
use Codevia\AppConfigurator\Config\Database\Database;
use Codevia\AppConfigurator\ConsoleHelper\AskForChoiceTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForNotEmptyValueTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends Command
{
    use AskForChoiceTrait;
    use AskForNotEmptyValueTrait;

    static protected $defaultName = 'edit';

    protected function configure()
    {
        $this->setHelp('Edit the configuration file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write(sprintf("\033c"));

        // Looking for a configuration file
        $output->writeln('Looking for a configuration file...');

        $config = null;
        $parentDirectory = dirname(getcwd());
        $theoricalPath = $parentDirectory . '/config.json';

        if (is_file($theoricalPath)) {
            $output->writeln('Found the configuration file at ' . $theoricalPath);
        } else {
            $output->writeln('Not configuration file found. Creating a new one...');
            $handler = fopen($theoricalPath, 'w');
            fwrite($handler, json_encode(['applications' => []]));
            fclose($handler);
        }

        $config = new Config(file_get_contents($theoricalPath));
        $config->askForData($input, $output, $this->getHelper('question'));

        // Save the configuration file
        $output->writeln('Saving the configuration file...');
        $handler = fopen($theoricalPath, 'w');
        fwrite($handler, json_encode($config, JSON_PRETTY_PRINT));
        fclose($handler);
        $output->writeln('Configuration file saved at ' . $theoricalPath);

        return Command::SUCCESS;
    }
}