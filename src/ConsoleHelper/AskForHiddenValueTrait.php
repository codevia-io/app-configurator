<?php

namespace Codevia\AppConfigurator\ConsoleHelper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Vincent Bathelier <vincent.bathelier@protonmail.com>
 */
trait AskForHiddenValueTrait
{
    protected function askForHiddenValue(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper,
        string $questionText,
        string $default = null
    ): string {
        $suffix = $default ? " [<comment>****</comment>] " : ' ';
        $question = new Question($questionText . $suffix, $default);
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        return $questionHelper->ask($input, $output, $question) ?? '';
    }
}
