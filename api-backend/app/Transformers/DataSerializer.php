<?php

namespace App\Transformer;

use League\Fractal\Serializer\ArraySerializer;

class DataSerializer extends ArraySerializer {
    public function collection(?string $resourceKey, array $data): array
    {
        return [
            'count' => count($data),
            'data' => $data
        ];
    }
}