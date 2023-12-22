<?php

namespace App\Command;

use App\Service\HelperCommand;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day9 extends HelperCommand
{
    protected function configure(): void
    {
        $this->setName('Day9');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $this->getFile();

        $result = [];
        foreach ($file as $oasis) {
            $oasisData = explode(' ', trim($oasis));
            $result[] = $this->extrapolateValue($oasisData);
        }

        $output->writeln('Le résultat est : ' . array_sum($result));

        return Command::SUCCESS;
    }

    /**
     * @param array $sequence
     * @return int
     */
    private function extrapolateValue(array $sequence)
    {
        $allDiff = [$sequence];

        $treeDepth = 0;
        while (true) {

            $diff = [];
            for ($i = 0; $i < count($allDiff[$treeDepth]) - 1; $i++) {
                $diff[$i] = $allDiff[$treeDepth][$i + 1] - $allDiff[$treeDepth][$i];
            }

            $allDiff[] = $diff;

            if (count(array_unique($diff)) === 1) {
                break;
            }
            $treeDepth++;
        }

        $extrapolatedValue = 0;
        foreach (array_reverse($allDiff) as $depth => $values) {
            $extrapolatedValue += end($values);
        }

        return $extrapolatedValue;
    }
}