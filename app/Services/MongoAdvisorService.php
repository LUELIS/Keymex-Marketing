<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MongoAdvisorService
{
    protected $connection = 'mongodb';
    protected $collection = 'advisors';

    public function search(string $query, int $limit = 20): Collection
    {
        // Les données sont dans raw_data
        return DB::connection($this->connection)
            ->table($this->collection)
            ->where(function ($q) use ($query) {
                $q->where('raw_data.firstname', 'like', "%{$query}%")
                  ->orWhere('raw_data.lastname', 'like', "%{$query}%")
                  ->orWhere('raw_data.email', 'like', "%{$query}%");
            })
            ->limit($limit)
            ->get()
            ->map(fn ($advisor) => $this->formatAdvisor($advisor));
    }

    public function find(string $id): ?array
    {
        $advisor = DB::connection($this->connection)
            ->table($this->collection)
            ->where('_id', $id)
            ->first();

        return $advisor ? $this->formatAdvisor($advisor) : null;
    }

    public function all(): Collection
    {
        return DB::connection($this->connection)
            ->table($this->collection)
            ->orderBy('raw_data.lastname')
            ->get()
            ->map(fn ($advisor) => $this->formatAdvisor($advisor));
    }

    protected function formatAdvisor(array|object $advisor): array
    {
        $advisor = (array) $advisor;
        $rawData = (array) ($advisor['raw_data'] ?? []);

        return [
            'id' => (string) ($advisor['_id'] ?? $advisor['immofacile_id'] ?? ''),
            'immofacile_id' => $advisor['immofacile_id'] ?? null,
            'firstname' => $rawData['firstname'] ?? '',
            'lastname' => $rawData['lastname'] ?? '',
            'fullname' => trim(($rawData['firstname'] ?? '') . ' ' . ($rawData['lastname'] ?? '')),
            'email' => $rawData['email'] ?? '',
            'phone' => $rawData['mobile_phone'] ?? $rawData['phone'] ?? '',
            'agency' => $advisor['agency_slug'] ?? '',
            'photo' => $rawData['picture'] ?? null,
        ];
    }
}
