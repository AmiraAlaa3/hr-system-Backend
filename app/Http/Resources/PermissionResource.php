<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'page' => $this->page,
            'add' => $this->add === 'true',
            'delete' => $this->delete === 'true',
            'edit' => $this->edit === 'true',
            'view' => $this->view === 'true',
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}

