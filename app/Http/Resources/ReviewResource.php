<?php

namespace App\Http\Resources;

use App\Models\Chalet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'stars' => $this->stars,
            'chalet' => new ChaletListItemResource(Chalet::find($this->chalet_id)),
            'user' => new UserMiniResource(User::find($this->user_id)),
            'date' => $this->created_at,
        ];
    }
}
