<?php

namespace App\Command;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

set_time_limit(0);
ini_set('memory_limit', -1);

final class Day6 extends Command
{
    protected function configure(): void
    {
        $this->setName('Day6');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $times = array(38, 94, 79, 70);
        $distances = array(241, 1549, 1074, 1091);

        $result = 1;
        foreach ($times as $key => $time) {

            $record = $distances[$key];
            $beats = 0;
            for ($hold = 1; $hold < $time; $hold++) {
                $timeRemaining = $time - $hold;
                $distance = $timeRemaining * $hold;
                if ($distance > $record) {
                    $beats++;
                } elseif ($beats) {
                    break;
                }
            }
            $result *= $beats;
        }

         $output->writeln("Le nombre total de façons de gagner est : " . $result);

        return Command::SUCCESS;
    }
}
