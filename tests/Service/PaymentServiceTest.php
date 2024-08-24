<?php

namespace App\Tests\Service;

use App\Object\DTO\PaymentRequestDTO;
use App\Payment\Object\PaymentGateway;
use App\Service\PaymentService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentServiceTest extends KernelTestCase
{
    public function testShift4(): void
    {
        self::bootKernel();
        $result = $this->getServiceTestResult(PaymentGateway::SHIFT4);
        $this->assertCount(5, $result);
    }

    public function testShift4Exception(): void
    {
        self::bootKernel();
        $this->expectException(InvalidArgumentException::class);
        $result = $this->getServiceTestResult(PaymentGateway::SHIFT4, 'error');
    }

    public function testACISuccess(): void
    {
        self::bootKernel();
        $result = $this->getServiceTestResult(PaymentGateway::ACI);
        $this->assertCount(5, $result);
    }

    public function testACIException(): void
    {
        self::bootKernel();
        $this->expectException(InvalidArgumentException::class);
        $result = $this->getServiceTestResult(PaymentGateway::ACI, 'error');
    }

    private function getServiceTestResult(PaymentGateway $paymentGateway, string $testType = 'success'): array
    {
        $container = static::getContainer();

        /** @var PaymentService */
        $paymentService = $container->get(PaymentService::class);

        return $paymentService->charge($this->generatePaymentRequestDTO($paymentGateway, ['erroneous_data' => 'error' === $testType]), $paymentGateway);
    }

    private function generatePaymentRequestDTO(PaymentGateway $paymentGateway, array $options = []): PaymentRequestDTO
    {
        $paymentRequestDTO = new PaymentRequestDTO();
        $paymentRequestDTO
            ->setCurrency('EUR')
            ->setCardNumber(PaymentGateway::ACI === $paymentGateway ? '4200000000000000' : '4242424242424242');

        $amount = 10;
        $cardExpYear = '2034';
        $cardExpMonth = '05';
        $cvv = '123';
        if ($options['erroneous_data'] ?? false) {
            $amount = 0; // Setting amount 0 is not possible when using command line or api endpoint because of validation
            $cardExpYear = 1234;
            $cardExpMonth = 13;
            $cvv = '456';
        }

        $paymentRequestDTO
            ->setAmount($amount)
            ->setCardExpYear($cardExpYear)
            ->setCardExpMonth($cardExpMonth)
            ->setCvv($cvv);

        return $paymentRequestDTO;
    }
}
