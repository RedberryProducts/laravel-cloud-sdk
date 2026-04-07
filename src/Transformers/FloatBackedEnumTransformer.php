<?php

namespace Redberry\LaravelCloudSdk\Transformers;

use BackedEnum;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class FloatBackedEnumTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        if ($value instanceof BackedEnum) {
            return (float) $value->value;
        }

        return $value;
    }
}
