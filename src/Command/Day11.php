<?php

namespace App\Command;

use App\Service\HelperCommand;
use App\Service\HelperMaze;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day11 extends HelperCommand
{
    protected function configure(): void
    {
        $this->setName('Day11');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fullGalaxy = $this->buildGalaxyExtended();
        $moons = $this->getMoonsInGalaxy($fullGalaxy);
        $result = 0;

        for ($i = 0; $i < count($moons); $i++) {
            for ($j = $i + 1; $j < count($moons); $j++) {
                $moon1 = $moons[$i];
                $moon2 = $moons[$j];
                $result += $this->lineNoDiag($moon1['x'], $moon1['y'], $moon2['x'], $moon2['y']);

            }
        }

        $output->writeln('Le résultat est : ' . $result);
        return Command::SUCCESS;
    }

    private function buildExpansionVoid($oldGalaxy): array
    {
        $galaxy = [];
        foreach ($oldGalaxy as $row) {
            $galaxy[] = $row;
            if (count(array_unique($row)) === 1) {
                $galaxy[] = $row;
            }
        }

        return $galaxy;
    }

    private function reverseArray($array): array
    {
        $result = [];
        foreach ($array as $y => $ligne) {
            foreach ($ligne as $x => $valeur) {
                $result[$x][$y] = $valeur;
            }
        }

        return $result;
    }

    public function buildGalaxyExtended(): array
    {
        $maze = new HelperMaze('data/day11.txt', '#');
        $galaxy = $this->buildExpansionVoid($maze->mazeArray);
        $galaxy = $this->reverseArray($galaxy);
        $galaxy = $this->buildExpansionVoid($galaxy);

        return $this->reverseArray($galaxy);
    }

    /**
     * // https://en.wikipedia.org/wiki/Bresenham's_line_algorithm
     *
     * @param int $x0
     * @param int $y0
     * @param int $x1
     * @param int $y1
     * @return int
     */
    function lineNoDiag(int $x0, int $y0, int $x1, int $y1): int
    {
        $count = 0;
        $xDist = abs($x1 - $x0);
        $yDist = -abs($y1 - $y0);
        $xStep = ($x0 < $x1) ? 1 : -1;
        $yStep = ($y0 < $y1) ? 1 : -1;
        $error = $xDist + $yDist;

        while ($x0 != $x1 || $y0 != $y1) {
            $count++;
            if (2 * $error - $yDist > $xDist - 2 * $error) {
                $error += $yDist;
                $x0 += $xStep;
            } else {
                $error += $xDist;
                $y0 += $yStep;
            }
        }

        return $count;
    }

    public function getMoonsInGalaxy(array $fullGalaxy): array
    {
        $moons = [];
        foreach ($fullGalaxy as $y => $row) {
            if (count(array_unique($row)) >= 1) {
                foreach ($row as $x => $cell) {
                    if ($cell === '#') {
                        $moons[] = [
                            'x' => $x,
                            'y' => $y
                        ];
                    }
                }
            }
        }
        return $moons;
    }
}