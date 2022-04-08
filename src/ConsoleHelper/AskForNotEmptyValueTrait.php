<?php

namespace Codevia\AppConfigurator\ConsoleHelper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Vincent Bathelier <vincent.bathelier@protonmail.com>
 */
trait AskForNotEmptyValueTrait
{
    /**
     * Ask for a not empty value and retry if empty.
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $questionHelper
     * @param string          $question
     */
    protected function askForNotEmptyValue(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper,
        string $question,
    ): string {
        $question = new Question($question . ' ');
        $response = (string) $helper->ask($input, $output, $question);

        // If the response is empty, ask again.
        if (strlen($response) < 1) {
            do {
                $output->writeln('<error>Value cannot be empty.</error>');
                $response = (string) $helper->ask($input, $output, $question);
            } while (strlen($response) < 1);
        }

        return $response;
    }
}
