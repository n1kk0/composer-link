<?php


namespace n1kk0\ComposerLink;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Exception;
use n1kk0\ComposerLink\Filesystem\Filesystem;
use n1kk0\ComposerLink\Manager\ComposerManager;
use n1kk0\ComposerLink\Manager\ConfigManager;
use n1kk0\ComposerLink\Manager\LinkManager;

class LinkPlugin implements PluginInterface, Capable, EventSubscriberInterface
{

    /**
     * @var LinkManager
     */
    private static $linkManager;

    public function activate(Composer $composer, IOInterface $io)
    {
        if(static::isNoTemporaryClass()) {
            return;
        }
        $filesystem = new Filesystem();
        $composerManager = new ComposerManager($composer, $io);

        self::$linkManager = new LinkManager(
            $filesystem,
            new ConfigManager($filesystem, $composerManager->getExtra('linked', './linked.json')),
            $composerManager
        );
    }

    public function getLinkManager() {
        return self::$linkManager;
    }

    public function getCapabilities()
    {
        if(static::isNoTemporaryClass()) {
            return [];
        }
        return [
            CommandProvider::class => LinkCommandProvider::class,
        ];
    }

    public static function getSubscribedEvents()
    {
        if(static::isNoTemporaryClass()) {
            return [];
        }
        
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP => ['preAutoloadDump']
        ];
    }

    public function preAutoloadDump()
    {
        self::$linkManager->linkAllFromConfig();
    }

    /**
     * Composer 1 seems to load this plugin class three times. dunno why, but this ugly workaround should prevent it.
     * @return bool
     */
    public static function isNoTemporaryClass(): bool
    {
        return get_class() !== 'n1kk0\\ComposerLink\\LinkPlugin';
    }

    public function deactivate(Composer $composer, IOInterface $io) {

    }

    public function uninstall(Composer $composer, IOInterface $io) {

    }


}