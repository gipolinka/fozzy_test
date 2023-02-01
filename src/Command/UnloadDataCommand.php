<?php


namespace App\Command;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

class UnloadDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:seeds';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Uploaded default data to the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try{
            $finder = new Finder();
            $finder->in('config');
            $finder->name('seeds.sql');

            foreach( $finder as $file ){
                $content = $file->getContents();
                $commands = explode(';', $content);
                foreach ($commands as $command){
                    if ($command != "") {
                        $statement = $this->entityManager->getConnection()->prepare($command);
                        $statement->execute();
                    }
                }
            }
            $io->success('Data is loaded!.');
        }catch (\Exception $exception){
            $io->error($exception);

            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}