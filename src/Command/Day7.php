<?php

namespace App\Command;

use App\Service\HelperCommand;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

set_time_limit(0);
ini_set('memory_limit', -1);

final class Day7 extends HelperCommand
{
    const FIVEOFAKIND = 6;
    const FOUROFAKIND = 5;
    const FULLHOUSE = 4;
    const THREEOFAKIND = 3;
    const TWOPAIRS = 2;
    const PAIR = 1;
    const HIGHCARD = 0;
    private array $cardsStrength = [
        'A' => 13,
        'K' => 12,
        'Q' => 11,
        'J' => 10,
        'T' => 9,
        '9' => 8,
        '8' => 7,
        '7' => 6,
        '6' => 5,
        '5' => 4,
        '4' => 3,
        '3' => 2,
        '2' => 1,
    ];

    protected function configure(): void
    {
        $this->setName('Day7');
    }

    #[NoReturn] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $this->getFile();

        $hands = [];

        foreach ($file as $value) {
            $value = explode(' ', $value);
            $hands[] = [
                'hand' => $value[0],
                'bid' => intval($value[1]),
                'type' => $this->getHandType($value[0]),
            ];
        }

        usort($hands, function ($a, $b) {
            if ($a['type'] === $b['type']) {
                $characters = str_split($a['hand']);
                foreach ($characters as $key => $char) {
                    if ($this->cardsStrength[$char] === $this->cardsStrength[$b['hand'][$key]]) {
                        continue;
                    } elseif ($this->cardsStrength[$char] > $this->cardsStrength[$b['hand'][$key]]) {
                        return 1;
                    } elseif ($this->cardsStrength[$char] < $this->cardsStrength[$b['hand'][$key]]) {
                        return -1;
                    }
                }
            } elseif ($a['type'] > $b['type']) {
                return 1;
            } else {
                return -1;
            }
            return 0;
        });

        $winners = 0;
        foreach ($hands as $key => $hand) {
            $winners += $hand['bid'] * ($key + 1);
        }

        $output->writeln('Le resultat est : ' . $winners);

        return Command::SUCCESS;
    }

    private function getHandType($hand): int
    {
        $nbOccurenceByCard = array_count_values(str_split($hand));
        rsort($nbOccurenceByCard);

        return match (true) {
            $nbOccurenceByCard[0] === 5 => self::FIVEOFAKIND,
            $nbOccurenceByCard[0] === 4 => self::FOUROFAKIND,
            $nbOccurenceByCard[0] === 3 && $nbOccurenceByCard[1] === 2 => self::FULLHOUSE,
            $nbOccurenceByCard[0] === 3 => self::THREEOFAKIND,
            $nbOccurenceByCard[0] === 2 && $nbOccurenceByCard[1] === 2 => self::TWOPAIRS,
            $nbOccurenceByCard[0] === 2 => self::PAIR,
            default => self::HIGHCARD,
        };
    }
}
