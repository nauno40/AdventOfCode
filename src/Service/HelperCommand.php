<?php

namespace App\Service;

use Symfony\Component\Console\Command\Command;

class HelperCommand extends Command
{
    protected function getFile(): array
    {
        return file(__DIR__ . './../../data/' . strtolower($this->getName()) . '.txt');
    }

}