<?php

namespace App\Command;

use App\Object\DTO\PaymentRequestDTO;
use App\Payment\Object\PaymentGateway;
use App\Service\PaymentService;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:pay',
    description: 'Tries to pay using provided arguments',
    hidden: false
)]
class PayCommand extends Command
{
    private array $allowedPaymentGatewayValues = [];

    public function __construct(private ValidatorInterface $validator, private PaymentService $paymentService)
    {
        $this->allowedPaymentGatewayValues = array_column(PaymentGateway::cases(), 'value');

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('gateway', InputArgument::REQUIRED, 'Payment gateway name. Available: ' . implode(', ', $this->allowedPaymentGatewayValues))
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'Amount to pay')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paymentGateway = PaymentGateway::tryFrom($input->getArgument('gateway'));

        if (!$paymentGateway) {
            throw new InvalidArgumentException('Invalid argument for gateway. Valid values are: ' . implode(', ', $this->allowedPaymentGatewayValues));
        }

        $questionMappings = [
            ['dtoAttr' => 'amount', 'questionLabel' => 'Amount'],
            ['dtoAttr' => 'currency', 'questionLabel' => 'Currency'],
            ['dtoAttr' => 'cardNumber', 'questionLabel' => 'Card number'],
            ['dtoAttr' => 'cardExpYear', 'questionLabel' => 'Card exp year'],
            ['dtoAttr' => 'cardExpMonth', 'questionLabel' => 'Card exp month'],
            ['dtoAttr' => 'cvv', 'questionLabel' => 'CVV'],
        ];

        $paymentRequestDTO = new PaymentRequestDTO();

        /** @var QuestionHelper */
        $helper = $this->getHelper('question');

        $paymentRequestDTO = new PaymentRequestDTO();

        $output->writeln('Please provide values for following parameters');

        $questionAnserwsForConfirmation = [];
        $index = 0;
        while (isset($questionMappings[$index])) {
            $data = $questionMappings[$index];
            $questionLabel = $data['questionLabel'];
            $dtoAttr = $data['dtoAttr'];

            $question = new Question($questionLabel . ': ');
            $answer = $helper->ask($input, $output, $question);
            $error = $this->validator->validatePropertyValue(PaymentRequestDTO::class, $dtoAttr, $answer);
            if (count($error) > 0) {
                $output->writeln('<error>' . $error->get(0)->getMessage() . '</error>');
                continue;
            }
            $index++;
            $setterMethod = 'set' . ucfirst($dtoAttr);
            $getterMethod = 'get' . ucfirst($dtoAttr);
            $paymentRequestDTO->$setterMethod($answer);
            $questionAnserwsForConfirmation[] = $questionLabel . ': '. $paymentRequestDTO->$getterMethod($answer);;
        }

        /** @var FormatterHelper */
        $formatter = $this->getHelper('formatter');
        $confirmationMessages = [
            'Trying to perform the payment operation using the following arguments. Some of the answers might have been formatted.',
            implode('; ', $questionAnserwsForConfirmation)
        ];
        $formattedBlock = $formatter->formatBlock($confirmationMessages, 'info');
        $output->writeln($formattedBlock);
        $confirmationQuestion = new ConfirmationQuestion('Are you sure to continue? [y]es / [n]o: ');
        if (!$helper->ask($input, $output, $confirmationQuestion)) {
            $output->writeln('<info>Command aborted</info>');
            return Command::SUCCESS;
        }

        $output->writeln('-----------------------------------------');
        $output->writeln('Response:');

        try {
            $result = $this->paymentService->charge($paymentRequestDTO, $paymentGateway);
            $output->writeln('Success.');
            $output->writeln('Data:');
            foreach ($result as $key => $value) {
                $output->writeln('- ' . $key . ': ' . $value);
            }
            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }
    }
}