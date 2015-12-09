<?php
namespace JLP\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Psr\Log\LogLevel;


/**
 * Class PasserelleExecuteCommand
 * @package JLP\CoreBundle\Command
 */
class PasserelleExecuteCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this->setName('passerelle:execute')->setDescription(
            'Execute the passerelle on the current connectimmo.zip'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            
            $verbosityLevelMap = array(
                LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
                LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL
            );
            
            
            $logger = new ConsoleLogger($output,$verbosityLevelMap);
            
            $output->writeln("\n\r<question>Execution de la passerelle JLP-IMMO</question>");

            // Appel du service correpondant au CRON
            $services = $this->getContainer()->get('jlp_core.passerelle');
            $responseServices = $services->execute($logger);
            
            $output->writeln("\n\r");
            
        } catch (\Exception $e) {
            $output->writeln(
                "\t<error>Passerelle Exception : " . $e . '</error>'
            );
        }
    }
}
