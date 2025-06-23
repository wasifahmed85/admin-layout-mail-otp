<?php

namespace App\Http\Traits;

use Illuminate\Http\RedirectResponse;

trait AuditRelationTraits
{
    protected function creater_name($model): string
    {
        return $model->creater_admin ? $model->creater_admin?->name : ($model->creater ? $model->creater?->name : "System Generate");
    }

    protected function updater_name($model): string
    {
        return $model->updater_admin ? $model->updater_admin?->name : ($model->updater ? $model->updater?->name : "N/A");
    }

    protected function deleter_name($model): string
    {
        return $model->deleter_admin ? $model->deleter_admin?->name : ($model->deleter ? $model->deleter?->name : "N/A");
    }
}
