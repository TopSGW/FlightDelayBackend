<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClaimFileNumber extends Model
{
    protected $table = 'claim_file_numbers';

    public function getNextFileNumber($source)
    {
        $lastClaimFileNumber = self::select()->latest()->first();

        if ($lastClaimFileNumber && (int)$lastClaimFileNumber->created_at->year < (int)date('Y')) {
            $this->resetAutoIncrement();
        }

        $claimFileNumber = self::create();

        return join('-', [$source, date('Y'), $claimFileNumber->id]);
    }

    public function resetAutoIncrement()
    {
        if (env('DB_CONNECTION') === 'mysql') {
            DB::table($this->table)->truncate();

            // Set the auto increment value to 1000, so it can be used as an automatic file numbering.
            DB::statement("ALTER TABLE {$this->table} AUTO_INCREMENT=1;");
        }
    }
}
