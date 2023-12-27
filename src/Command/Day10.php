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
            'W' => ['W', 'N'],
            'S' => ['S', 'E'],
        ],
        'J' => [
            'E' => ['E', 'N'],
            'S' => ['S', 'W'],
        ],
        'F' => [
            'N' => ['N', 'E'],
            'W' => ['W', 'S'],
        ],
        '7' => [
            'N' => ['N', 'W'],
            'E' => ['E', 'S'],
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
            $lastDirection = 'S';
            $maze->move($lastDirection);

            $moveCount = 0;

            while ($maze->getCurrentPosition() !== $startPosition) {
                exec('echo ' . $maze->getCurrentSymbol() . ' >> test.txt');
                $newDirections = $this->direction[$maze->getCurrentSymbol()][$lastDirection];
                foreach ($newDirections as $newDirection) {

                    $maze->move($newDirection);
                    $lastDirection = $newDirection;
                }
                $moveCount++;
            }


        } catch (Exception $e) {
            $output->writeln('ERROR : NTM');
        }

        $output->writeln("La distance maximum est de : " . $moveCount + 1);
        return Command::SUCCESS;
    }
}