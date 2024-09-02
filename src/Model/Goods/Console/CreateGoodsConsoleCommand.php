<?php

declare(strict_types=1);

namespace App\Model\Goods\Console;

use App\Model\Goods\Command\Create\CreateGoodsCommand;
use App\Model\Goods\Command\Create\CreateGoodsCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'dev:goods:create', description: 'Create a new Goods')]
final class CreateGoodsConsoleCommand extends Command
{
    private InputInterface $input;
    private OutputInterface $output;

    public function __construct(
        private readonly CreateGoodsCommandHandler $createGoodsCommandHandler,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $command = new CreateGoodsCommand(
            $this->getValidName(),
            $this->getValidPrice('Purchase'),
            $this->getValidPrice('Rental (4 hours)'),
            $this->getValidPrice('Rental (8 hours)'),
            $this->getValidPrice('Rental (12 hours)'),
            $this->getValidPrice('Rental (24 hours)'),
        );

        ($this->createGoodsCommandHandler)($command);

        return self::SUCCESS;
    }

    private function getValidPrice(string $type): int
    {
        $input = $this->ask(\sprintf('<fg=blue>%s price: </>', $type));

        if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
            $money = (int) $input;

            if ($money >= 0) {
                return $money;
            }

            $this->output->writeln('<fg=red>The amount of money cannot be negative</>');
        } else {
            $this->output->writeln('<fg=red>The amount of money must be an integer value</>');
        }

        return $this->getValidPrice($type);
    }

    private function getValidName(): string
    {
        $firstName = $this->ask('<fg=blue>Name: </>');

        if (mb_strlen($firstName) > 1) {
            return $firstName;
        }

        $this->output->writeln('<fg=red>The name cannot be that short</>');

        return $this->getValidName();
    }

    private function ask(string $message): string
    {
        $helper = $this->getHelper('question');
        \assert($helper instanceof QuestionHelper);

        $question = new Question($message, null);

        return trim($helper->ask($this->input, $this->output, $question));
    }
}
