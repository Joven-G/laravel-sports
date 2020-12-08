<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'date'  => $this->date,
            'start' => $this->start,
            'end'   => $this->end,
            'hour'  => $this->hour,
            'color' => $this->color,
            'field_number' => $this->field_number,
            'user_id' => $this->user_id
        ];
    }
}

