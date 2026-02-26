<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $url;
    protected $hostKey;

    public function __construct()
    {
        $this->url = env('GETA_WHATSAPP_URL');
        $this->hostKey = env('GETA_HOST_KEY');
    }

    public function sendOTPMessage($phone, $otp)
    {
        // COMPONENTS ARE REQUIRED BECAUSE YOUR TEMPLATE HAS A URL BUTTON
        $payload = [
            "to" => $phone,
            "content" => [
                "type" => "template",
                "template" => [
                    "name" => "otpverification",
                    "language" => ["code" => "en"],
                    "components" => [
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $otp] // OTP in body
                            ]
                        ],
                        [
                            "type" => "button",
                            "sub_type" => "URL", // Your template requires this
                            "index" => 0,
                            "parameters" => [
                                ["type" => "text", "text" => $otp] // short parameter required
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return Http::withHeaders([
            "geta-host" => $this->hostKey,
            "Content-Type" => "application/json"
        ])->post($this->url, $payload)->json();
    }
}
