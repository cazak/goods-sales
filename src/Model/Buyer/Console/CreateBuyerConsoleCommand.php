<?php

declare(strict_types=1);

namespace App\Model\Buyer\Console;

use App\Model\Buyer\Command\Create\CreateBuyerCommand;
use App\Model\Buyer\Command\Create\CreateBuyerCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Uid\UuidV7;

#[AsCommand(name: 'dev:buyer:create', description: 'Create a new Buyer')]
final class CreateBuyerConsoleCommand extends Command
{
    private InputInterface $input;
    private OutputInterface $output;

    public function __construct(
        private readonly CreateBuyerCommandHandler $createBuyerCommandHandler,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $id = new UuidV7();

        $command = new CreateBuyerCommand(
            $id,
            $this->getValidFirstName(),
            $this->getValidLastName(),
            $this->getValidMoneyAmount(),
        );

        ($this->createBuyerCommandHandler)($command);

        $output->writeln(\sprintf('<fg=green>Person "%s" has been successfully created!</>', $id->toString()));

        $output->writeln(
            \sprintf(
                '<fg=blue>If you need a user, run the command: <fg=yellow>php bin/console dev:user:create %s</>',
                $id->toString(),
            ),
        );

        return self::SUCCESS;
    }

    private function getValidFirstName(): string
    {
        $firstName = $this->ask('<fg=blue>First name: </>');

        if (mb_strlen($firstName) > 1) {
            return $firstName;
        }

        $this->output->writeln('<fg=red>The first name cannot be that short</>');

        return $this->getValidFirstName();
    }

    private function getValidLastName(): string
    {
        $lastName = $this->ask('<fg=blue>Last name: </>');

        if (mb_strlen($lastName) > 1) {
            return $lastName;
        }

        $this->output->writeln('<fg=red>The last name cannot be that short</>');

        return $this->getValidLastName();
    }

    private function getValidMoneyAmount(): int
    {
        $input = $this->ask('<fg=blue>Amount of money: </>');

        if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
            $money = (int) $input;

            if ($money >= 0) {
                return $money;
            }

            $this->output->writeln('<fg=red>The amount of money cannot be negative</>');
        } else {
            $this->output->writeln('<fg=red>The amount of money must be an integer value</>');
        }

        return $this->getValidMoneyAmount();
    }

    private function ask(string $message): string
    {
        $helper = $this->getHelper('question');
        \assert($helper instanceof QuestionHelper);

        $question = new Question($message, null);

        return trim($helper->ask($this->input, $this->output, $question));
    }
}
