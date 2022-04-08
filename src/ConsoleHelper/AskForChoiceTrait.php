<?php

namespace Codevia\AppConfigurator\ConsoleHelper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * @author Vincent Bathelier <vincent.bathelier@protonmail.com>
 */
trait AskForChoiceTrait
{
    protected function askForChoice(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        string $question,
        array $choices,
    ) {
        $question = new ChoiceQuestion($question, $choices, null);
        $question->setErrorMessage('%s is invalid.');

        return array_search($questionHelper->ask($input, $output, $question), $choices);
    }
}
