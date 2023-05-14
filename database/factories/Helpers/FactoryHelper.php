<?php

namespace Database\Factories\Helpers;


class FactoryHelper
{
    /**
     * this function will get random id from model
     * @param string|HasFactory $model
     */

    public static function getRandomModelId(string $model)
    {
        $count = $model::query()->count();

        if ($count == 0) {
            return $postId = $model::factory()->create()->id;
        } else {
            return rand(1, $count);
        }
    }
}
