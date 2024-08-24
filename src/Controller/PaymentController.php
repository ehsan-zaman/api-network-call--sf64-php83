<?php

namespace App\Controller;

use App\Object\DTO\PaymentRequestDTO;
use App\Payment\Object\PaymentGateway;
use App\Response\ApiResponse;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    public function __construct(private PaymentService $paymentService)
    {}

    /**
     * Makes payment using the provided arguments
     */
    #[Route('/payment/{paymentGateway}', methods: ['POST'])]
    public function payment(#[MapRequestPayload(serializationContext: ['disable_type_enforcement' => true])] PaymentRequestDTO $paymentRequest, PaymentGateway $paymentGateway): Response
    {
        return $this->json(
            new ApiResponse(ApiResponse::STATUS_OK, $this->paymentService->charge($paymentRequest, $paymentGateway))
        );
    }
}
