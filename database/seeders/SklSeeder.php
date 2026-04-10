<?php

namespace Database\Seeders;

use App\Models\BirthRecord;
use App\Models\Skl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SklSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $birthRecords = BirthRecord::whereDoesntHave('skl')->get();

        if ($birthRecords->isEmpty()) {
            $this->command->warn('No birth records without SKL found.');
            return;
        }

        $count = 0;
        foreach ($birthRecords as $index => $birthRecord) {
            // Generate document number format: SKL/RM-RSUKM/XXXX/YYYY
            $year = Carbon::now()->year;
            $sequence = str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            $documentNumber = "SKL/RM-RSUKM/{$sequence}/{$year}";

            Skl::firstOrCreate(
                ['birth_record_id' => $birthRecord->id],
                [
                    'uuid' => Str::uuid()->toString(),
                    'document_number' => $documentNumber,
                    'issue_date' => $birthRecord->birth_date,
                    'signer_name' => $birthRecord->doctor->name ?? 'dr. Ahmad Rasyid, Sp.OG',
                    'signer_role' => 'Dokter Penanggung Jawab Persalinan',
                    'is_signed' => true,
                ]
            );
            $count++;
        }

        $this->command->info("SKL documents seeded successfully! Total: {$count} documents");
    }
}
