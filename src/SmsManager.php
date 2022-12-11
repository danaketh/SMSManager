<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

interface SmsManager
{
    public function send(SmsMessage $smsMessage): Response|bool;
}
