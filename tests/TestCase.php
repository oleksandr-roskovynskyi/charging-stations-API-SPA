<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $base_route = null;
    protected $base_model = null;

    protected function setBaseRoute($route)
    {
        $this->base_route = $route;
    }

    protected function setBaseModel($model)
    {
        $this->base_model = $model;
    }

    protected function create($attributes = [], $model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ?? $route;
        $model = $this->base_model ?? $model;

        $attributes = raw($model, $attributes);

        $response = $this->postJson($route, $attributes)->assertSuccessful();

        $model = new $model;

        $this->assertDatabaseHas($model->getTable(), $attributes);

        return $response;
    }

    protected function update($attributes = [], $model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ?? $route;
        $model = $this->base_model ?? $model;

        $model = create($model);

        $response = $this->patchJson($route . '/' . $model->id, $model->toArray());

        tap($model->fresh(), function ($model) use ($attributes) {
            collect($attributes)->each(function($value, $key) use ($model) {
                $this->assertEquals($value, $model[$key]);
            });
        });

        return $response;
    }

    protected function destroy($model = '', $route = '')
    {
        $this->withoutExceptionHandling();

        $route = $this->base_route ?? $route;
        $model = $this->base_model ?? $model;

        $model = create($model);

        $response = $this->deleteJson($route . '/' . $model->id);

        $this->assertDatabaseMissing($model->getTable(), $model->toArray());

        return $response;
    }
}
