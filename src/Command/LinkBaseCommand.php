<?php

namespace n1kk0\ComposerLink\Command;

use Composer\Command\BaseCommand;
use n1kk0\ComposerLink\Command\Interpreter\Interpreter;
use n1kk0\ComposerLink\Filesystem\Filesystem;
use n1kk0\ComposerLink\Manager\LinkManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class LinkBaseCommand extends BaseCommand
{

    /**
     * @var LinkManager
     */
    private $linkManager;


    public function __construct(LinkManager $linkManager)
    {
        parent::__construct();

        $this->linkManager = $linkManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strategy = $this->getInterpreter()->interpret($input);

        if ($strategy) {
            return $strategy->execute($this->linkManager, $input, $output);
        }
    }

    private function getInterpreter(): Interpreter
    {
        return new Interpreter(
            ...$this->getCommandStrategies()
        );
    }

    abstract public function getCommandStrategies(): array;
}