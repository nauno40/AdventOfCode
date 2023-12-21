<?php

namespace App\Command;

use App\Service\HelperCommand;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Day8 extends HelperCommand
{
    const ENTREE = 'AAA';
    const SORTIE = 'ZZZ';
    const DIRECTION = [
        'L' => 0,
        'R' => 1
    ];

    protected function configure(): void
    {
        $this->setName('Day8');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $this->getFile();

        $map = [];
        $inputCommand = [];
        foreach ($file as $lineNumber => $line) {
            if ($lineNumber === 0) {
                $inputCommand = str_split(trim($line));
            } elseif ($lineNumber > 1) {
                preg_match("/^(\w+)\s*=\s*\((\w+),\s*(\w+)\)$/", trim($line), $matches);
                $map[$matches[1]] = [$matches[2], $matches[3]];
            }
        }

        $nbInputCommand = count($inputCommand);
        $nbLoop = 0;

        $position = self::ENTREE;
        while ($position !== self::SORTIE) {
            $position = $map[$position][self::DIRECTION[$inputCommand[$nbLoop++ % $nbInputCommand]]];
        }

        $output->writeln('Le résultat est : ' . $nbLoop);

        return Command::SUCCESS;
    }
}