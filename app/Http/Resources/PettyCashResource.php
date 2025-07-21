<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PettyCashResource extends JsonResource
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
            'date' => $this->date,
            'account_ledgers' => $this->account_ledgers,
            'account_description' => $this->account_description,
            'amount' => $this->amount,
            'attachment' => $this->attachment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
