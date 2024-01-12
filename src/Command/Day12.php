<?php

namespace App\Command;

use App\Service\HelperCommand;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day12 extends HelperCommand
{
    private array $data = [];

    protected function configure(): void
    {
        $this->setName('Day12');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = [];

        $file = $this->getFile();

        foreach ($file as $line) {
            [$motors, $motorsCounter] = explode(' ', $line);
            $this->data[] = [
                'visual' => $motors,
                'numeric' => explode(',', trim($motorsCounter)),
            ];
        }

        foreach ($this->data as ['visual' => $possibility, 'numeric' => $expectedValues]) {
            $nbPossibilities = 0;
            $this->makePossibilities($possibleArrangements, $possibility);
            foreach ($possibleArrangements as $arrangement) {

                $isPossible = $this->isValidPossibility($arrangement, $expectedValues);
                if ($isPossible) {
                    $nbPossibilities++;
                }
            }
            $result[] = $nbPossibilities;
            unset($possibleArrangements);
        }


        $output->writeln('Le résultat est : ' . array_sum($result));
        return Command::SUCCESS;
    }

    function isValidPossibility(string $possibility, array $expectedValues): bool
    {
        $occurrences = preg_split("/(\.)+/", $possibility);
        $nbConsecutifs = [];

        if (substr_count($possibility, "#") !== array_sum($expectedValues)) {
            return false;
        }

        foreach ($occurrences as $occurrence) {
            if ($occurrence !== '') {
                $nbConsecutifs[] += substr_count($occurrence, "#");
            }
        }

        if (array_diff_assoc($nbConsecutifs, $expectedValues)) {
            return false;
        }

        return true;
    }

    private function makePossibilities(&$combinations, $chaine, $index = 0): void
    {
        if ($index == strlen($chaine)) {
            $combinations[] = $chaine;
            return;
        }

        if ($chaine[$index] == '?') {
            $chaine[$index] = '.';
            $this->makePossibilities($combinations, $chaine, $index + 1);

            $chaine[$index] = '#';
            $this->makePossibilities($combinations, $chaine, $index + 1);

            $chaine[$index] = '?';
        } else {
            $this->makePossibilities($combinations, $chaine, $index + 1);
        }
    }
}