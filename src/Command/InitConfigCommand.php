<?php
declare(strict_types = 1);

namespace Gitfuzz\Command;

use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitConfigCommand extends Command
{
    protected $configFileName = '.gitfuzz';

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
    }

    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Init gitfuzz config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFileExists = file_exists($this->configFileName);

        if ($configFileExists) {
            $output->writeln('<error>Config file exists</error>');
            return;
        }

        $this->initializeConfigFile($output);
        $output->writeln('<info>Config file was created</info>');
    }

    protected function initializeConfigFile(OutputInterface $output)
    {
        $output->writeln('Creating config file');
        $authors = array_map(function () {
            return "{$this->faker->firstName} {$this->faker->lastName} <{$this->faker->email}>";
        }, range(1, 6));

        $commits = [
            'min' => 1,
            'max' => 10,
        ];

        file_put_contents($this->configFileName, json_encode(compact('authors', 'commits'), JSON_PRETTY_PRINT));
    }
}
