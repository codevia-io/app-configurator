<?php

namespace Codevia\AppConfigurator\ConsoleHelper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

trait AskForConfirmationTrait
{
    protected function askForConfirmation(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        string $questionText,
    ): bool {
        $question = new ConfirmationQuestion($questionText, false);

        return $questionHelper->ask($input, $output, $question);
    }
}
