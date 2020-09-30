<?php

namespace App\Command;

use App\Generators\CSVWishListGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCSVWishListCommand extends Command
{
    protected static $defaultName = 'app:generate-csv-wish-list';

    private CSVWishListGenerator $generator;
    private string $filePath;

    private const FILENAME = 'data.csv';

    public function __construct(CSVWishListGenerator $generator, string $publicDir)
    {
        $this->generator = $generator;
        $this->filePath = $publicDir.'/'.self::FILENAME;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generates wish lists data in CSV format')
            ->setHelp('This command generates wish lists data in CSV format.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $io->success('Start file generation...');

            $data = $this->generator->generate();
            file_put_contents($this->filePath, $data);

            $io->success(sprintf('Data successfully saved to file %s', $this->filePath));

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error(sprintf('Command failed with error: %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
