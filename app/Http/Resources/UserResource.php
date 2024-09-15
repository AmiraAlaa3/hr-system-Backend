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
            'password' => $this->password,
            // Return the group data with both group ID and name
            'group' => [
                'id' => $this->groups->first()->id, // Assuming the user has only one group
                'name' => $this->groups->first()->name,
            ],
            'permissions' => $this->groups->first()->permissions->map(function ($permission) {
                $permissions = [];
        
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
        
                return $permissions;

            }),
        ];
    }
}
