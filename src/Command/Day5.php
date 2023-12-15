<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

set_time_limit(0);
ini_set('memory_limit', -1);

final class Day5 extends Command
{
    private string $pathToFile = '/../../data/day5.txt';
    private array $seed = [];
    private array $map = [];
    private array $result;

    protected function configure(): void
    {
        $this->setName('Day5');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getAndSanitize();
        $this->buildMapSeed();

        $output->writeln('Result 1 : ' . min($this->result));

        $this->getAndSanitize();

        $lastResult = [];

        $numberToAdd = false;
        $currentValue = 0;
        foreach ($this->seed as $key => $value) {
            if ($numberToAdd === false) {
                $lastResult[] = $value;
                $currentValue = $value;
                $numberToAdd = true;
            } else {
                for ($i = 0; $i < $value; $i++) {
                    $lastResult[] = $currentValue + $i;
                }
                $numberToAdd = false;
            }
        }

        $this->seed = $lastResult;
        $this->result = [];

        $this->buildMapSeed();

        $output->writeln('Result 2 : ' . min($this->result));

        return Command::SUCCESS;
    }

    private function getAndSanitize(): void
    {
        $file = file(__DIR__ . $this->pathToFile);
        $mappingName = '';

        foreach ($file as $line) {
            $cleanLine = explode(PHP_EOL, trim($line))[0];
            if (preg_match("/^seeds: (.*)$/", $cleanLine, $match)) {
                $this->seed = array_map("intval", explode(" ", $match[1]));
            } elseif (preg_match("/^\w+-to-(\w+) map:$/", $cleanLine, $match)) {
                $mappingName = $match[1];
            } elseif (preg_match("/^(\d+) (\d+) (\d+)$/", $cleanLine, $match)) {
                $this->map[$mappingName][] = [
                    intval($match[1]),
                    intval($match[2]),
                    intval($match[3])
                ];
            }
        }
    }

    private function buildMapSeed(): void
    {
        foreach ($this->seed as &$oneSeed) {
            foreach ($this->map as $mapping) {
                foreach ($mapping as $oneMapping) {
                    [$start, $source, $length] = $oneMapping;

                    if ($oneSeed >= $source && $oneSeed < $source + $length) {
                        $oneSeed = $oneSeed - $source + $start;
                        break;
                    }
                }
            }
            $this->result[] = $oneSeed;
        }
    }
}
