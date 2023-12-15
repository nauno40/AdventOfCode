<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day2 extends Command
{
    private $pathToFile = '/../../data/day2.txt';

    protected function configure()
    {
        $this->setName('Day2');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Result is : ' . $this->filterDataAndCalculateSum($this->getAndSanitize()));
        return self::SUCCESS;
    }

    private function getAndSanitize()
    {
        $file = fopen(__DIR__ . $this->pathToFile, 'r');

        $data = [];

        while (!feof($file)) {

            $line = fgets($file);
            if (empty($line)) {
                continue;
            }
            $lineWhithoutGame = str_ireplace('Game ', '', trim((string) $line));

            $newLine = explode(':', $lineWhithoutGame);
            $gameId = $newLine[0];

            $runs = explode(';', (string)$newLine[1]);

            $tirage = 1;
            foreach ($runs as $run) {
                $result = explode(',', $run);
                foreach ($result as $value) {
                    [$number, $color] = explode(' ', trim($value));

                    $data[$gameId][$tirage][$color] = $number;
                }
                $tirage++;
            }
        }

        return $data;
    }

    private function filterDataAndCalculateSum($data)
    {

        $maxValues = [
            'green' => 13,
            'red' => 12,
            'blue' => 14,
        ];

        foreach ($data as $gameId => $values) {
            foreach ($values as $result) {
                if ($result['green'] > $maxValues['green'] || $result['red'] > $maxValues['red'] || $result['blue'] > $maxValues['blue']) {
                    unset($data[$gameId]);
                }
            }
        }

        return array_sum(array_keys($data));
    }
}
