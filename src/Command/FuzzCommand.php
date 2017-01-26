<?php
declare(strict_types = 1);

namespace Gitfuzz\Command;

use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FuzzCommand extends Command
{
    protected $configFileName = '.gitfuzz';

    protected $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
    }

    protected function configure()
    {
        $this->setName('fuzz')
            ->setDescription('Fuzz current git repository');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->ensureGitRepository();
        $this->ensureConfigFile();

        $config = $this->loadConfig();

        $this->makeFuzz($config, $output);
    }

    protected function makeFuzz(array $config, OutputInterface $output)
    {
        $numberOfCommits = mt_rand($config['commits']['min'], $config['commits']['max']);
        $makeCommit = function () use ($config, $output) {
            $author = $this->faker->randomElement($config['authors']);
            $commitMessage = $this->faker->sentence;

            $output->writeln("New commit: {$commitMessage} <comment>by {$author}</comment>");

            $process = new Process("git commit -m '{$commitMessage}' --allow-empty --author '{$author}'");
            $process->run();

            if (false === $process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        };

        for ($i = 0; $i < $numberOfCommits; $i++) {
            $makeCommit();
        }
    }

    protected function ensureGitRepository()
    {
        $error = new \RuntimeException('You need to be inside GIT root directory');

        if (false === file_exists('.git')) {
            throw $error;
        }

        if (false === is_dir('.git')) {
            throw $error;
        }
    }

    protected function ensureConfigFile()
    {
        if (false === file_exists($this->configFileName)) {
            throw new \RuntimeException('Can\'t find config file');
        }
    }

    protected function loadConfig(): array
    {
        return json_decode(file_get_contents($this->configFileName), true);
    }
}
