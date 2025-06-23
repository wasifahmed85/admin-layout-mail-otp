<?php

namespace App\Http\Traits;

use Illuminate\Database\Schema\Blueprint;

trait AuditColumnsTrait
{
    public function addAdminAuditColumns(Blueprint $table): void
    {
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();

        $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
        $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
        $table->foreign('deleted_by')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');

        $table->index('created_by');
        $table->index('updated_by');
        $table->index('deleted_by');
    }
    public function dropAdminAuditColumns(Blueprint $table): void
    {
        $table->dropForeign(['created_by']);
        $table->dropForeign(['updated_by']);
        $table->dropForeign(['deleted_by']);

        $table->dropIndex(['created_by']);
        $table->dropIndex(['updated_by']);
        $table->dropIndex(['deleted_by']);
    }


    public function addMorphedAuditColumns(Blueprint $table): void
    {
        $table->unsignedBigInteger('creater_id')->nullable();
        $table->string('creater_type')->nullable();
        $table->unsignedBigInteger('updater_id')->nullable();
        $table->string('updater_type')->nullable();
        $table->unsignedBigInteger('deleter_id')->nullable();
        $table->string('deleter_type')->nullable();

        $table->index('creater_id');
        $table->index('updater_id');
        $table->index('deleter_id');
        $table->index('creater_type');
        $table->index('updater_type');
        $table->index('deleter_type');
    }

    public function dropAuditColumns(Blueprint $table): void
    {
        $table->dropForeign(['created_by']);
        $table->dropForeign(['updated_by']);
        $table->dropForeign(['deleted_by']);

        $table->dropColumn('created_by');
        $table->dropColumn('updated_by');
        $table->dropColumn('deleted_by');

        $table->dropIndex(['creater_id']);
        $table->dropIndex(['updater_id']);
        $table->dropIndex(['deleter_id']);
        $table->dropIndex(['creater_type']);
        $table->dropIndex(['updater_type']);
        $table->dropIndex(['deleter_type']);
    }
}
