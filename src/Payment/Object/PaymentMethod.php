<?php

namespace App\Payment\Object;

enum PaymentGateway: string
{
  case ACI = 'aci';
  case SHIFT4 = 'shift4';
}