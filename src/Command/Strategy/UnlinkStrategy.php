<?php


namespace n1kk0\ComposerLink\Command\Strategy;


use Exception;
use n1kk0\ComposerLink\Manager\LinkManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnlinkStrategy extends CommandStrategy
{

    public function satisfiedBy(InputInterface $input): bool
    {
        return true;
    }

    public function execute(LinkManager $linkManager, InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('package');
        try {

            if($path) {
                $packageLink = $linkManager->unlinkFromPath(
                    $path
                );

                if($input->getOption('save')) {
                    $linkManager->getConfigManager()
                        ->removePackage($packageLink);
                }

                $output->writeln('<info> Package ' . $packageLink->getName() . ' is unlinked');
                return 0;
            }

            $linkManager->unlinkAllFromConfig();
            return 0;

        } catch (Exception $e) {

            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return (int)$e->getCode();
        }
    }


}