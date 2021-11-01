<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;

trait CanRate
{

    public function ratings(string $model = null)
    {
        $modelClass = $model ? $model : $this->getMorphClass();

        $morphToMany = $this->morphToMany(
            $modelClass, // Clase con la que me quiero relacionar
            'qualifier', // Nombre de mi mi relación
            'ratings', // Nombre de la tabla
            'qualifier_id', // Columna con la cual yo hago relacion
            'rateable_id' // Columna con la que quiero relacionarme
        );

        $morphToMany
            ->as('rating')
            ->withTimestamps()
            ->withPivot('score', 'rateable_type')
            ->wherePivot('rateable_type', $modelClass)
            ->wherePivot('qualifier_type', $this->getMorphClass());

        return $morphToMany;
    }

    public function rate(Model $model, float $score)
    {
        if($this->hasRated($model))
            return false;

        $this->ratings($model)->attach($model->getKey(), [
            "score" => $score,
            "rateable_type" => get_class($model)
        ]);

        return true;
    }

    public function hasRated(Model $model)
    {
        return !is_null($this->ratings($model->getMorphClass())->find($model->getKey()));
    }
}
