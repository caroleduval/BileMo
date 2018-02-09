<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use AppBundle\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCommand extends ContainerAwareCommand
{
    private $em;
    private $encoder;

    public function __construct($name = null, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('BM:user:create')
            ->setDescription('Create a user.')
            ->addArgument('client', InputArgument::OPTIONAL, 'Client_Id ?')
            ->addArgument('username', InputArgument::OPTIONAL, 'Username ?')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email ?')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password ?')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Set the user as admin ?', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the client id: ', null);
        $client_id = $helper->ask($input, $output, $question);
        if (empty($client_id)) {
            throw new \Exception('client id can not be empty');
        }

        $question = new Question('Please enter the username: ', null);
        $username = $helper->ask($input, $output, $question);
        if (empty($username)) {
            throw new \Exception('username can not be empty');
        }

        $question = new Question('Please enter the email: ', null);
        $email = $helper->ask($input, $output, $question);
        if (empty($email)) {
            throw new \Exception('email can not be empty');
        }

        $question = new Question('Please enter the password: ', null);
        $plainPassword = $helper->ask($input, $output, $question);
        if (empty($plainPassword)) {
            throw new \Exception('password can not be empty');
        }

        $admin = $input->getOption('admin');
        $rolesArr = ("y" == $admin) ? array('ROLE_ADMIN') : array('ROLE_USER');

        $client=$this->em->getRepository(Client::class)->find($client_id);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $encoded = $this->encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $user->setClient($client);
        $user->setRoles($rolesArr);

        $this->em->persist($user);
        $this->em->flush();


        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }
}
