<?php

namespace App\Service;

use Exception;

class HelperMaze
{
    public array $mazeArray;
    public array $currentPosition;

    private string $startSymbol = 'S';

    public function setStartSymbol(string $startSymbol): HelperMaze
    {
        $this->startSymbol = $startSymbol;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function __construct($filePath, $startSymbol)
    {
        $this->startSymbol = $startSymbol;
        $this->loadMazeFromFile($filePath);
    }

    /**
     * @throws Exception
     */
    private function loadMazeFromFile($filePath): void
    {
        $mazeLines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($mazeLines === false) {
            throw new Exception("Unable to read maze file: $filePath");
        }

        $this->mazeArray = array_map('str_split', $mazeLines);

        // Find the starting position
        foreach ($this->mazeArray as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === $this->startSymbol) {
                    $this->currentPosition = ['x' => $x, 'y' => $y];
                    break 2;
                }
            }
        }

        if (!isset($this->currentPosition)) {
            throw new Exception("Starting position $this->startSymbol not found in the maze.");
        }
    }

    public function getCurrentPosition(): array
    {
        return $this->currentPosition;
    }

    public function getCurrentSymbol()
    {
        $x = $this->currentPosition['x'];
        $y = $this->currentPosition['y'];

        if ($this->isValidPosition($x, $y)) {
            return $this->mazeArray[$y][$x];
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function move($direction): bool
    {
        $x = $this->currentPosition['x'];
        $y = $this->currentPosition['y'];

        switch ($direction) {
            case 'N':
                $y--;
                break;
            case 'S':
                $y++;
                break;
            case 'E':
                $x++;
                break;
            case 'W':
                $x--;
                break;
            default:
                throw new Exception("Direction Inconnue: $direction");
        }

        // Check if the new position is within the maze boundaries
        if ($this->isValidPosition($x, $y)) {
            $this->currentPosition['x'] = $x;
            $this->currentPosition['y'] = $y;
            return true;
        }

        return false;
    }

    private function isValidPosition($x, $y): bool
    {
        return isset($this->mazeArray[$y][$x]) && $this->mazeArray[$y][$x] !== '.';
    }

    public function displayMaze(): void
    {
        foreach ($this->mazeArray as $row) {
            echo implode('', $row) . PHP_EOL;
        }
    }
}
