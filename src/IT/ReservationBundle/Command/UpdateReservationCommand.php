<?php

namespace IT\ReservationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateReservationCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // On set le nom de la commande
        $this->setName('reservation:update');

        // On set la description
        $this->setDescription("This Command create an update for a specific reservation");

        // On set l'aide
        $this->setHelp("Je serai affiche si on lance la commande app/console app:test -h");

        // On prépare les arguments
        $this->addArgument('id', InputArgument::REQUIRED, "What is the ID of the reservation ?");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $id = (int) $input->getArgument('id');
            $em = $this->getContainer()->get('doctrine')->getManager();
            $reservation = $em->getRepository("ITReservationBundle:Reservation")->findOneBy(['id' => $id]);
            $reservation->setStatut("Terminé");
            $em->persist($reservation);
            $em->flush();
            $output->writeln("Executed successfully");
        } catch (\Exception $e) {
            $output->writeln("Error");
        }

    }
}