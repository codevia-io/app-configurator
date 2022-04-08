<?php

namespace Codevia\AppConfigurator\Config\Database\DBSM;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface DBMSInterface
{
    public function askForData(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $questionHelper
    ): void;
    public function getDsn(): string;
    public function setAlias(string $alias): self;
    public function getAlias(): string;
}