<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Output\OutputInterface;

class ClientCreateCommand extends ContainerAwareCommand
{
    protected function configure ()
    {
        $this
            ->setName('BM:client:create')
            ->setDescription('Creates a new client')
            ->addArgument('name', InputArgument::REQUIRED, 'Sets the client name', null)
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($input->getArgument('name'));
        $clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new client with name : <info>%s</info>', $client->getName()));
        $output->writeln(sprintf('Public id :<info>%s</info>', $client->getPublicId()));
        $output->writeln(sprintf('Secret :<info>%s</info>', $client->getSecret()));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('name')) {
            $question = new Question('Please enter the client name:');
            $question->setValidator(function ($name) {
                if (empty($name)) {
                    throw new \Exception('Name can not be empty');
                }

                return $name;
            });
            $questions['name'] = $question;
        }

        foreach ($questions as $zame => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($zame, $answer);
        }
    }
}
