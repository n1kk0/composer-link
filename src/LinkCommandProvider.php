<?php


namespace n1kk0\ComposerLink;

use Composer\Command\BaseCommand;
use Composer\Plugin\Capability\CommandProvider;
use n1kk0\ComposerLink\Command\LinkCommand;
use n1kk0\ComposerLink\Command\UnlinkCommand;
use n1kk0\ComposerLink\Manager\LinkManager;

class LinkCommandProvider implements CommandProvider
{


    private $linkManager;


    public function __construct($linkManager)
    {
        $this->linkManager = $linkManager['plugin']->getLinkmanager();
    }

    /**
     * @return array|BaseCommand[]
     */
    public function getCommands()
    {
        return [
            new LinkCommand($this->linkManager),
            new UnlinkCommand($this->linkManager)
        ];
    }
}