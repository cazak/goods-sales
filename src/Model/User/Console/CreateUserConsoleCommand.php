<?php

declare(strict_types=1);

namespace App\Model\User\Console;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\User\Command\Create\CreateUserCommand;
use App\Model\User\Command\Create\CreateUserCommandHandler;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Entity\ValueObject\Role;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'dev:user:create', description: 'Create a new User')]
final class CreateUserConsoleCommand extends Command
{
    private InputInterface $input;
    private OutputInterface $output;

    public function __construct(
        private readonly BuyerRepository $buyerRepository,
        private readonly UserRepository $userRepository,
        private readonly CreateUserCommandHandler $createUserCommandHandler,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('buyer_id', InputArgument::REQUIRED, 'The buyer ID'),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $id = Uuid::fromString((string) $this->input->getArgument('buyer_id'));

        $buyer = $this->buyerRepository->findById($id->toBinary());

        if ($buyer === null) {
            $this->output->writeln('<fg=red>There is no buyer with this ID.</>');

            return Command::FAILURE;
        }

        if ($buyer->getUser() !== null) {
            $this->output->writeln('<fg=red>This buyer already has user.</>');

            return Command::FAILURE;
        }

        $this->createUserFor($buyer);

        return self::SUCCESS;
    }

    private function createUserFor(Buyer $buyer): void
    {
        $email = $this->getValidEmail();

        $command = new CreateUserCommand(
            $buyer,
            $email,
            $this->getValidPassword(),
            $this->getValidRole(),
        );

        ($this->createUserCommandHandler)($command);

        $this->output->writeln(\sprintf('<fg=green>User "%s" has been successfully created!</>', $email));
    }

    private function getValidEmail(): string
    {
        $email = $this->ask('<fg=blue>Email: </>');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->output->writeln('<fg=red>Incorrect Email address is specified!</>');

            return $this->getValidEmail();
        }

        if ($this->emailIsUnique($email)) {
            return $email;
        }

        $this->output->writeln(\sprintf('<fg=red>Email "%s" is already registered. Try another one.</>', $email));

        return $this->getValidEmail();
    }

    private function getValidPassword(): string
    {
        $password = $this->ask('<fg=blue>Password: </>');

        if (mb_strlen($password) < 8) {
            $this->output->writeln('<fg=red>Password must be at least 8 characters!</>');

            return $this->getValidPassword();
        }

        return $password;
    }

    private function getValidRole(): string
    {
        return $this->choice('<fg=blue>Please select role: </>', Role::casesAtString());
    }

    private function emailIsUnique(string $email): bool
    {
        $foundUser = $this->userRepository->findByEmail($email);

        return $foundUser === null;
    }

    /**
     * @param array<int, string> $choices
     */
    private function choice(string $message, array $choices): string
    {
        $helper = $this->getHelper('question');
        \assert($helper instanceof QuestionHelper);

        $question = new ChoiceQuestion(
            $message,
            $choices,
        );

        return $helper->ask($this->input, $this->output, $question);
    }

    private function ask(string $message): string
    {
        $helper = $this->getHelper('question');
        \assert($helper instanceof QuestionHelper);

        $question = new Question($message, null);

        return trim($helper->ask($this->input, $this->output, $question));
    }
}
