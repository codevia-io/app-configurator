<?php

namespace Codevia\AppConfigurator\ConsoleHelper;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface AskForDataInterface
{
    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void;
}