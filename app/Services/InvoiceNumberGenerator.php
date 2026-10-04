<?php

namespace App\Services;

use App\Models\InvoiceSequence;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceNumberGenerator
{
    /**
     * Compute Indian Financial Year string (e.g. '2026-27') from a date.
     */
    public static function getFinancialYear(Carbon|string|null $date = null): string
    {
        $dt = $date ? Carbon::parse($date) : Carbon::now();
        $year = (int) $dt->format('Y');
        $month = (int) $dt->format('n'); // 1-12

        if ($month >= 4) {
            $startYear = $year;
            $endYear = $year + 1;
        } else {
            $startYear = $year - 1;
            $endYear = $year;
        }

        $endYearShort = substr((string) $endYear, -2);
        return "{$startYear}-{$endYearShort}";
    }

    /**
     * Format the invoice number string using configured pattern.
     */
    public static function formatInvoiceNumber(string $fy, int $sequence): string
    {
        $pattern = (string) Setting::get('invoice.format', 'TDC/{FY}/{SEQ:4}');

        $paddedSeq = str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

        $formatted = str_replace('{FY}', $fy, $pattern);
        $formatted = preg_replace('/\{SEQ:\d+\}/', $paddedSeq, $formatted);
        $formatted = str_replace('{SEQ}', (string) $sequence, $formatted);

        return $formatted;
    }

    /**
     * Atomically generate the next sequential invoice number within a DB transaction.
     *
     * @return array{financial_year: string, sequence_number: int, invoice_number: string}
     */
    public function generateNextNumber(int $branchId = 1, Carbon|string|null $date = null): array
    {
        $fy = self::getFinancialYear($date);

        return DB::transaction(function () use ($branchId, $fy) {
            // Find or create sequence entry
            $sequenceRecord = InvoiceSequence::where('branch_id', $branchId)
                ->where('financial_year', $fy)
                ->lockForUpdate()
                ->first();

            if (!$sequenceRecord) {
                // Insert first, then lock
                $sequenceRecord = InvoiceSequence::create([
                    'branch_id'      => $branchId,
                    'financial_year' => $fy,
                    'last_sequence'  => 0,
                ]);

                $sequenceRecord = InvoiceSequence::where('id', $sequenceRecord->id)
                    ->lockForUpdate()
                    ->first();
            }

            $nextSeq = $sequenceRecord->last_sequence + 1;
            $sequenceRecord->update(['last_sequence' => $nextSeq]);

            $invoiceNumber = self::formatInvoiceNumber($fy, $nextSeq);

            return [
                'financial_year'  => $fy,
                'sequence_number' => $nextSeq,
                'invoice_number'  => $invoiceNumber,
            ];
        });
    }
}
