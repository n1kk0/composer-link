<?php

namespace n1kk0\ComposerLink\Command\Strategy;

use n1kk0\ComposerLink\Manager\LinkManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandStrategy
{


    public abstract function satisfiedBy(InputInterface $input): bool;

    public abstract function execute(LinkManager $linkManager, InputInterface $input, OutputInterface $output): int;
}