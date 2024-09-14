<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'password'=> $this->password,
            'groups' => $this->groups->map(function ($group) {
                $permissions = [];

                // Iterate over each permission in the group
                foreach ($group->permissions as $permission) {
                    if ($permission->add === 'true') {
                        $permissions[] = 'add';
                    }
                    if ($permission->edit === 'true') {
                        $permissions[] = 'edit';
                    }
                    if ($permission->view === 'true') {
                        $permissions[] = 'view';
                    }
                    if ($permission->delete === 'true') {
                        $permissions[] = 'delete';
                    }
                }

                return [
                    'name' => $group->name,
                    'permissions' => $permissions,
                ];
            }),
        ];
    }
}
