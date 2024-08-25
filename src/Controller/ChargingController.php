<?php

namespace App\Controller;

use App\Object\DTO\ChargingRequestDTO;
use App\Charging\Object\PaymentGateway;
use App\Response\ApiResponse;
use App\Service\ChargingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ChargingController extends AbstractController
{
    public function __construct(private ChargingService $chargingService)
    {}

    /**
     * Tries to charge using the provided arguments
     */
    #[Route('/charge/{paymentGateway}', methods: ['POST'])]
    public function charge(#[MapRequestPayload(serializationContext: ['disable_type_enforcement' => true])] ChargingRequestDTO $chargingRequest, PaymentGateway $paymentGateway): Response
    {
        return $this->json(
            new ApiResponse(ApiResponse::STATUS_OK, $this->chargingService->charge($chargingRequest, $paymentGateway))
        );
    }
}
