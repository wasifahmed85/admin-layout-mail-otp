<?php

namespace App\Services;

use App\Http\Traits\FileManagementTrait;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AdminService
{
    use FileManagementTrait;

    public function getAdmins($orderBy = 'name', $order = 'asc')
    {
        return Admin::orderBy($orderBy, $order)->latest();
    }
    public function getAdmin(string $encryptedId): Admin|Collection
    {
        return Admin::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedAdmin(string $encryptedId): Admin|Collection
    {
        return Admin::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createAdmin(array $data, $file = null): Admin
    {
        return DB::transaction(function () use ($data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file, 'admins', $data['name']);
            }
            $data['status'] = Admin::STATUS_ACTIVE;
            $data['created_by'] = admin()->id;
            $admin = Admin::create($data);
            return $admin;
        });
    }

    public function updateAdmin(Admin $admin, array $data, $file = null): Admin
    {
        return DB::transaction(function () use ($admin, $data, $file) {
            if ($file) {
                $data['image'] = $this->handleFileUpload($file, 'admins', $data['name']);
                $this->fileDelete($admin->image);
            }
            $data['password'] = $data['password'] ?? $admin->password;
            $data['updated_by'] = admin()->id;
            $admin->update($data);
            return $admin;
        });
    }

    public function delete(Admin $admin): void
    {
        $admin->update(['deleted_by' => admin()->id]);
        $admin->delete();
    }

    public function restore(string $encryptedId): void
    {
        $admin = $this->getDeletedAdmin($encryptedId);
        $admin->update(['updated_by' => admin()->id]);
        $admin->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $admin = $this->getDeletedAdmin($encryptedId);
        $admin->forceDelete();
    }

    public function toggleStatus(Admin $admin): void
    {
        $admin->update([
            'status' => !$admin->status,
            'updated_by' => admin()->id
        ]);
    }
}

