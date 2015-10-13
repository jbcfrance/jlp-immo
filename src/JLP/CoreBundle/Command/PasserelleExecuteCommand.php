<?php
namespace JLP\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;


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
            $output->writeln("\n\r<question>Execution de la cron Option cockpit</question>");

            // Appel du service correpondant au CRON
            $services = $this->getContainer()->get('jlp.passerelle');
            $responseServices = $services->execute();

            $output->writeln(
                "\t<info>Nombre de client ajouter dans le referentiel client cockpit ARS : " . $responseServices->getNbTraite(
                ) . '</info>'
            );
            foreach ($responseServices->getTraites() as $index => $traite) {
                $output->writeln(
                    "\t\t<info>" . ($index + 1) . " ) [" . $traite['idClient'] . "] => " . $traite['libelle'] . "</info>"
                );
            }
            $output->writeln(
                "\t<comment>Nombre de cas traite en erreur : " . $responseServices->getNbErreur() . '</comment>'
            );
            foreach ($responseServices->getErreurs() as $index => $error) {
                $output->writeln(
                    "\t\t<comment>" . ($index + 1) . " ) [" . $error['idClient'] . "] => " . $error['libelle'] . "</comment>"
                );
            }
            $output->writeln("\n\r");
        } catch (\Exception $e) {

            $logger = $this->getContainer()->get('logger');

            $message = sprintf(
                '%s: %s (exception) fichier %s ligne %s durant l\'execution de la command CronOption',
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );
            $logger->crit($message);
            $this->getApplication()->renderException($e, $output->getErrorOutput());

            $statusCode = $e->getCode();

            $statusCode = is_numeric($statusCode) && $statusCode ? $statusCode : 1;

            exit($statusCode);
        }
    }
}
