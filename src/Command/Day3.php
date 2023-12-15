<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day3 extends Command
{
    private $pathToFile = '/../../data/day3.txt';

    protected function configure()
    {
        $this->setName('Day3');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = file(__DIR__ . $this->pathToFile);

        $sum_of_all_part_numbers = $sum_of_gear_ratios = 0;
        $number_locations = $symbol_locations = $gear_locations = $gear_list = [];

        foreach ($file as $y => $line) {
            $number = "";
            foreach (str_split(trim($line)) as $x => $char) {
                if (is_numeric($char)) {
                    $number .= $char;
                } else {
                    if ($number !== "") {
                        $number_locations[] = [$number, $x, $y];
                        $number = "";
                    }
                    if ($char !== ".") {
                        $symbol_locations[] = [$x, $y];
                    }
                    if ($char === "*") {
                        $gear_locations[] = [$x, $y];
                    }
                }
            }
            if ($number !== "") {
                $number_locations[] = [$number, $x + 1, $y];
            }
        }

        foreach ($number_locations as [$number, $last_x, $y]) {
            $adjacent_gear = $adjacent_symbol = false;
            for ($x = $last_x - strlen($number); $x < $last_x; $x++) {
                foreach ([[-1, -1], [-1, 0], [-1, 1], [0, 1], [1, 1], [1, 0], [1, -1], [0, -1]] as [$offset_x, $offset_y]) {
                    $x_bis = $x + $offset_x;
                    $y_bis = $y + $offset_y;

                    if (in_array([$x_bis, $y_bis], $symbol_locations)) {
                        $adjacent_symbol = true;
                    }
                    if (in_array([$x_bis, $y_bis], $gear_locations)) {
                        $adjacent_gear = $x_bis . "/" . $y_bis;
                    }
                    if ($adjacent_symbol && $adjacent_gear) {
                        break;
                    }
                }
            }

            if ($adjacent_gear) {
                $gear_list[$adjacent_gear][] = intval($number);
            }

            if ($adjacent_symbol) {
                $sum_of_all_part_numbers += intval($number);
            }
        }

        foreach ($gear_list as $gear_values) {
            if (count($gear_values) === 2) {
                $sum_of_gear_ratios += array_product($gear_values);
            }
        }

        $output->writeln('Result 1 : ' . $sum_of_all_part_numbers);
        $output->writeln('Result 2 : ' . $sum_of_gear_ratios);

        return Command::SUCCESS;
    }
}
