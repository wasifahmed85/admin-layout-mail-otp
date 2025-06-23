<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AuthBaseModel extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    public function creater_admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id')->select(['name', 'id']);
    }

    public function updater_admin()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'id')->select(['name', 'id']);
    }

    public function deleter_admin()
    {
        return $this->belongsTo(Admin::class, 'deleted_by', 'id')->select(['name', 'id']);
    }

    public function creater()
    {
        return $this->morphTo();
    }

    public function updater()
    {
        return $this->morphTo();
    }

    public function deleter()
    {
        return $this->morphTo();
    }

    protected $appends = [
        'modified_image',

        'verify_label',
        'verify_color',

        'status_label',
        'status_color',
        'status_btn_label',
        'status_btn_color',

        'created_at_human',
        'updated_at_human',
        'deleted_at_human',

        'created_at_formatted',
        'updated_at_formatted',
        'deleted_at_formatted',
    ];


    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public static function statusList(): array
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
    public function getStatusLabelAttribute()
    {
        return $this->status ? self::statusList()[$this->status] : 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'badge-success' : 'badge-error';
    }

    public function getStatusBtnLabelAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? self::statusList()[self::STATUS_INACTIVE] : self::statusList()[self::STATUS_ACTIVE];
    }

    public function getStatusBtnColorAttribute()
    {
        return $this->status == self::STATUS_ACTIVE ? 'btn-error' : 'btn-success';
    }

    // Verify Accessors
    public function getVerifyLabelAttribute()
    {
        return $this->email_verified_at ? 'Verified' : 'Unverified';
    }

    public function getVerifyColorAttribute()
    {
        return $this->email_verified_at ? 'badge-success' : 'badge-error';
    }

    // Verified scope
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }
    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('email_verified_at');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    // Accessor for created time
    public function getCreatedAtFormattedAttribute()
    {
        return timeFormat($this->created_at);
    }

    // Accessor for updated time
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormat($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time
    public function getDeletedAtFormattedAttribute()
    {
        return $this->deleted_at ? timeFormat($this->deleted_at) : 'N/A';
    }

    // Accessor for created time human readable
    public function getCreatedAtHumanAttribute()
    {
        return timeFormatHuman($this->created_at);
    }

    // Accessor for updated time human readable
    public function getUpdatedAtHumanAttribute()
    {
        return $this->created_at != $this->updated_at ? timeFormatHuman($this->updated_at) : 'N/A';
    }

    // Accessor for deleted time human readable
    public function getDeletedAtHumanAttribute()
    {
        return $this->deleted_at ? timeFormatHuman($this->deleted_at) : 'N/A';
    }

    // Accessor for modified image
    public function getModifiedImageAttribute()
    {
        return auth_storage_url($this->image);
    }
}