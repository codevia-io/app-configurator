<?php

namespace Codevia\AppConfigurator\Config;

use Codevia\AppConfigurator\ConsoleHelper\AskForChoiceTrait;
use Codevia\AppConfigurator\ConsoleHelper\AskForDataInterface;
use Codevia\AppConfigurator\ConsoleHelper\AskForNotEmptyValueTrait;
use JsonSerializable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;

class Config implements JsonSerializable, AskForDataInterface
{
    use AskForChoiceTrait;
    use AskForNotEmptyValueTrait;

    /** @var Application[] */
    protected array $applications = [];

    public function __construct(string $data)
    {
        $data = json_decode($data, true);
        if (isset($data['applications'])) {
            foreach ($data['applications'] as $key => $application) {
                $this->applications[$key] = new Application($application);
            }
        }
    }

    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void {
        $action = null;
        do {
            do {
                $action = $this->askForChoice(
                    $input,
                    $output,
                    $questionHelper,
                    'What do you want to do?',
                    [
                        1 => 'Add a new application',
                        2 => 'Edit an existing application',
                        3 => 'Remove an existing application',
                        4 => 'List existing applications',
                        99 => 'Exit',
                    ],
                );
            } while ($action === null);

            // Add a new application
            if ($action === 1) {
                $output->writeln('Adding a new application...');
                $application = new Application(
                    ['database' => []],
                    $this->AskForNotEmptyValue(
                        $input,
                        $output,
                        $questionHelper,
                        'What is the name of the application?',
                    ),
                );
                $application->askForData($input, $output, $questionHelper);
                $this->applications[$application->getName()] = $application;
            }

            // Edit an existing application
            if ($action === 2) {
                $output->writeln('Editing an existing application...');
                $app = $this->askForChoice(
                    $input,
                    $output,
                    $questionHelper,
                    'Which application do you want to edit?',
                    array_map(function (Application $application) {
                        return $application->getName();
                    }, $this->applications),
                );

                $application = $this->applications[$app];
                unset($this->applications[$app]);

                $application->askForData($input, $output, $questionHelper);
                $this->applications[$application->getName()] = $application;
            }

            // Remove an existing application
            if ($action === 3) {
                $output->writeln('Removing an existing application...');
                $app = $this->askForChoice(
                    $input,
                    $output,
                    $questionHelper,
                    'Which application do you want to remove?',
                    array_map(function ($application) {
                        return $application->getName();
                    }, $this->applications),
                );

                unset($this->applications[$app]);
            }

            // List existing applications
            if ($action === 4) {
                $output->writeln('Listing existing applications...');

                if (count($this->applications) === 0) {
                    $output->writeln('No applications found.');
                }
                
                foreach ($this->applications as $application) {
                    $output->writeln('  - Application <info>' . $application->getName() . '</info>');
                }
            }
        } while ($action !== 99);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'applications' => $this->applications,
        ];
    }
}