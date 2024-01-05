<?php

namespace App\Command;

use App\Service\HelperMaze;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day10 extends Command
{
    private array $direction = [
        '|' => [
            'N' => ['N'],
            'S' => ['S']
        ],
        '-' => [
            'E' => ['E'],
            'W' => ['W']
        ],
        'L' => [
            'W' => ['N'],
            'S' => ['E'],
        ],
        'J' => [
            'E' => ['N'],
            'S' => ['W'],
        ],
        'F' => [
            'N' => ['E'],
            'W' => ['S'],
        ],
        '7' => [
            'N' => ['W'],
            'E' => ['S'],
        ],
    ];

    protected function configure(): void
    {
        $this->setName('Day10');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $maze = new HelperMaze('data/day10.txt');
            $maze->displayMaze();

            $startPosition = $maze->getCurrentPosition();
            $lastDirection = 'N';
            $maze->move($lastDirection);

            $moveCount = 0;

            while ($maze->getCurrentPosition() !== $startPosition) {
                $newDirections = $this->direction[$maze->getCurrentSymbol()][$lastDirection];
                foreach ($newDirections as $newDirection) {
                    $maze->move($newDirection);
                    $lastDirection = $newDirection;
                }
                $moveCount++;
            }

            $output->writeln("La distance maximum est de : " . round($moveCount / 2));
            return Command::SUCCESS;

        } catch (Exception $e) {
            $output->writeln('ERROR : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}