<?php

namespace App\Transformers;

use App\Models\Drill;
use League\Fractal\TransformerAbstract;

class DrillTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Drill $drill)
    {
        return [
            'id' => $drill->id,
            'next_repetition' => $drill->next_repetition,
            'keyword_id' => $drill->keyword_id,
            'question' => [
                'id' => $drill->question->id,
                'name' => $drill->question->name,
                'content' => $drill->question->content,
                'validation' => $drill->question->validation,
                'options' => $drill->question->options,
                'type' => $drill->question->type,
                'explanation' => $drill->question->explanation,

            ],
        ];
    }
}
