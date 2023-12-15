<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day4 extends Command
{
    private $pathToFile = '/../../data/day4.txt';
    private $data = [];
    private $result = [];
    private $cardsCounter = [];

    protected function configure()
    {
        $this->setName('Day4');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getAndSanitaize();

        foreach ($this->data as $cardsID => $cardsData) {
            $listOfWinnners = array_intersect($cardsData['winner'], $cardsData['set']);

            $this->result[$cardsID] = count($listOfWinnners);
            $foundNumber = count($listOfWinnners);


            if ($this->result[$cardsID] > 0) {
                $this->result[$cardsID] = pow(2, $foundNumber - 1);
            }

            for ($i = 1; $i <= $foundNumber; $i++) {
                if (isset($this->cardsCounter[$cardsID + $i])) {
                    $this->cardsCounter[$cardsID + $i] += $this->cardsCounter[$cardsID];
                } 
            }
        }

        $output->writeln('Day4 part 1 : ' . array_sum($this->result));
        $output->writeln('Day4 part 2 : ' . array_sum($this->cardsCounter));

        return Command::SUCCESS;
    }

    private function getAndSanitaize(): void
    {
        $file = file(__DIR__ . $this->pathToFile);

        $this->cardsCounter = array_fill(1, count($file), 1);

        foreach ($file as $line) {
            $cards = explode(':', trim($line));
            $cardsID = trim(str_replace('Card ', '', $cards[0]));

            [$winner, $set] = explode('|', trim($cards[1]));

            $winnerArray = explode(' ', trim($winner));
            $setArray = explode(' ', trim($set));

            $this->data[$cardsID] = [
                'winner' => $this->removeEmptyValues($winnerArray),
                'set' => $this->removeEmptyValues($setArray)
            ];
        }
    }

    private function removeEmptyValues(array $array): array
    {
        foreach ($array as $key => $value) {
            if (empty($value)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
