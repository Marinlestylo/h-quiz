<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Student;
use League\Fractal;

class StudentTransformer extends TransformerAbstract
{   
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Student $student)
    {
        return [
            'id' => $student->id,
            'orientation' => $student->orientation,
            'name' => $student->user->getfullName(),
            'user_id' => $student->user->id,
        ];
    }
}
