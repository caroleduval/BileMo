<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use AppBundle\Entity\User;
use AppBundle\Entity\Client;

class UserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('BM:user:create')
            ->setDescription('Create a user.')
            ->addArgument('client', InputArgument::REQUIRED, 'Client_Id ?')
            ->addArgument('username', InputArgument::REQUIRED, 'Username ?')
            ->addArgument('email', InputArgument::REQUIRED, 'Email ?')
            ->addArgument('password', InputArgument::REQUIRED, 'Password ?')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Set the user as admin ?', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client_id = $input->getArgument('client');
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $admin = $input->getOption('admin');

        $rolesArr=("y"==$admin)?array('ROLE_ADMIN'):array('ROLE_USER');

        $emi = $this->getContainer()->get('doctrine.orm.entity_manager');
        $client=$emi->getRepository(Client::class)->find($client_id);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setClient($client);
        $user->setRoles($rolesArr);

        $emi->persist($user);
        $emi->flush();


        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('client')) {
            $question = new Question('Please give the client Id:');
            $question->setValidator(function ($client) {
                if (empty($client)) {
                    throw new \Exception('Client can not be empty');
                }

                return $client;
            });
            $questions['client'] = $question;
        }

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
