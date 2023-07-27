<?php

namespace App\Http\Resources;

use App\Models\Chalet;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'paid_amount' => $this->paid_amount,
            'tax' => $this->tax,
            'total_price' => $this->total_price,
            'date' => $this->created_at,
            'last_update' => $this->updated_at,
            'payment' => $this->payment_id == null ? null : new PaymentResource(Payment::find($this->payment_id)),
            'is_review_seen' => $this->is_review_seen == 1 ? true : false,
            'chalet' => new ChaletListItemResource(Chalet::find($this->chalet_id)),
            'user' => new UserMiniResource(User::find($this->user_id)),
        ];
    }
}
