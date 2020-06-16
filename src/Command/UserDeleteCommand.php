<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\managerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDeleteCommand extends Command
{
    private $userRepository;
    private $resetPasswordRequestRepository;
    private $manager;

    protected static $defaultName = 'app:delete-users';

    /**
     * cf : https://symfony.com/doc/current/console.html#getting-services-from-the-service-container
     */
    public function __construct(UserRepository $userRepository, ResetPasswordRequestRepository $resetPasswordRequestRepository, EntityManagerInterface $manager)
    {
        $this->userRepository = $userRepository;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
        $this->manager = $manager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Delete users with status 0.')
            ->setHelp('This command allows you to delete a user...')
            ->addOption('dump', null, InputOption::VALUE_NONE, 'Displays more information');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Delete users');

        $users = $this->userRepository->findByStatus(false);

        if (empty($users)) {
            $io->success('No users to delete.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            
            $io->note('User ' . $user->getId() . ' deleted. Email : ' . $user->getEmail());
            //! delete user picture
            $this->manager->remove($user);
        }

        $this->manager->flush();

        $io->success('Users has been deleted.');
        return Command::SUCCESS;
    }
}
