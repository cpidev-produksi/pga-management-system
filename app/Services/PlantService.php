<?php

namespace App\Services;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PlantService
{
    /**
     * Get all plants yang bisa diakses user
     */
    public function getUserPlants(User $user): Collection
    {
        if ($user->is_super_admin) {
            // Super admin bisa lihat semua plant
            return Plant::where('is_active', true)->get();
        }

        // Regular user: hanya plant mereka
        if ($user->plant_uuid) {
            return Plant::where('uuid', $user->plant_uuid)->get();
        }

        return new Collection();
    }

    /**
     * Set current plant ke session
     */
    public function setCurrentPlant(string $plantUuid, User $user): bool
    {
        // Verify user bisa akses plant ini
        if (!$user->canAccessPlant($plantUuid)) {
            return false;
        }

        $plant = Plant::find($plantUuid);

        if (!$plant) {
            return false;
        }

        session(['current_plant_uuid' => $plant->uuid]);
        session(['current_plant_name' => $plant->name]);

        // Remember last plant (optional)
        session(['last_plant_uuid' => $plant->uuid]);

        return true;
    }

    /**
     * Get current plant
     */
    public function getCurrentPlant(): ?Plant
    {
        $plantUuid = session('current_plant_uuid');

        if (!$plantUuid) {
            return null;
        }

        return Plant::find($plantUuid);
    }

    /**
     * Get visitor count untuk plant (hari ini)
     */
    public function getTodayVisitorCount(string $plantUuid): int
    {
        return \App\Models\Visitor::byPlant($plantUuid)
            ->today()
            ->count();
    }

    /**
     * Get visitor count untuk semua plant (hari ini) - untuk super admin
     */
    public function getAllPlantsVisitorCount(): array
    {
        $plants = Plant::where('is_active', true)->get();

        return $plants->mapWithKeys(function ($plant) {
            return [
                $plant->uuid => [
                    'name' => $plant->name,
                    'count' => $this->getTodayVisitorCount($plant->uuid),
                ],
            ];
        })->toArray();
    }
}