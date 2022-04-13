<?php

namespace EscolaLms\Lrs\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'data' => $this->data,
            'voided' => $this->voided,
            'owner_id' => $this->owner_id,
            'entity_id' => $this->entity_id,
            'client_id' => $this->client_id,
            'access_id' => $this->access_id,
            'pending' => $this->pending,
            'validation' => $this->validation,
        ];
    }
}
