<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_ref' => $this->transaction_ref,
            'type' => $this->type,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'status' => $this->status,
            'card_type' => $this->card_type,
            'card_scheme' => $this->card_scheme,
            'card_description' => $this->card_description,
            'date' => $this->created_at,
        ];
    }
}
