<?php

namespace App\Service;

use App\Repository\PaymentTypeRepository;

class PaymentTypeService
{
    private PaymentTypeRepository $paymentTypeRepository;

    public function __construct(PaymentTypeRepository   $paymentTypeRepository)
    {
        $this->paymentTypeRepository = $paymentTypeRepository;
    }

    public function get_all_payment_types()
    {
        return $this->paymentTypeRepository->getAll();
    }
}