<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "id" => $this->id,
            "subject" => $this->subject,
            "description" => $this->description,
            "status_id" => $this->status_id,
            "status" => $this->status->name,
            "customer_id" => $this->customer_id,
            "customer" => $this->customer->name,
            "user_id" => new UserResource($this->user),
            "date" => $this->created_at->format("d M Y"),
            "time" => $this->created_at->format("H:i A"),
        ];
    }
}
