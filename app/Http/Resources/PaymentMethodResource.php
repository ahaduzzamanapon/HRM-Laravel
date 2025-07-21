<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'method_name' => $this->method_name,
            'method_type' => $this->method_type,
            'method_number' => $this->method_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
