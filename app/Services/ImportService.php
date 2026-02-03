<?php

namespace App\Services;

use CihanSenturk\OfxParser\Parser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ImportService
{
    /**
     * Parse an OFX file and return a collection of transactions.
     * 
     * @param string $filePath
     * @return array
     */
    public function parseOfx($filePath)
    {
        try {
            $ofxParser = new Parser();
            $ofx = $ofxParser->loadFromFile($filePath);

            $transactions = [];

            // OFX usually has bank accounts. We need to iterate through them.
            // Adjust depending on the specific structure of the OFX Parser result
            // Typically: $ofx->bankAccounts

            $seenIds = [];

            foreach ($ofx->bankAccounts as $bankAccount) {
                // Get statement
                $statement = $bankAccount->statement;

                foreach ($statement->transactions as $transaction) {
                    // Deduplication using uniqueId
                    $uniqueId = $transaction->uniqueId ?? null;
                    if ($uniqueId && in_array($uniqueId, $seenIds)) {
                        continue;
                    }
                    if ($uniqueId) {
                        $seenIds[] = $uniqueId;
                    }

                    $amount = (float) $transaction->amount;
                    if (abs($amount) < 0.001)
                        continue; // Filter zero amounts

                    $transactions[] = [
                        'date' => $transaction->date->format('Y-m-d H:i:s'),
                        'amount' => abs($amount),
                        'description' => $transaction->memo ?? $transaction->name ?? 'Sem descrição',
                        'type' => $amount < 0 ? 'expense' : 'income',
                        // 'raw_data' => $transaction // Removed to avoid serialization issues
                    ];
                }
            }

            return $transactions;

        } catch (\Exception $e) {
            Log::error('Erro ao processar OFX: ' . $e->getMessage());
            throw new \Exception('Falha ao processar arquivo OFX.');
        }
    }

    /**
     * Parse a CSV file (Nubank format usually).
     * 
     * @param string $filePath
     * @return array
     */
    public function parseCsv($filePath)
    {
        $transactions = [];

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Detect delimiter (read first line)
            $firstLine = fgets($handle);
            rewind($handle);

            $delimiter = ',';
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            }

            // Skip header
            $header = fgetcsv($handle, 0, $delimiter);

            while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if (count($data) < 3) // Relaxed check, sometimes category is missing
                    continue;

                // Nubank format (variable, but usually):
                // 0: date (YYYY-MM-DD or DD/MM/YYYY)
                // 1: category
                // 2: title (description)
                // 3: amount

                // If it's the other format (less columns):
                // 0: date, 1: description, 2: amount (common in some exports)

                if (count($data) >= 4) {
                    $dateStr = $data[0];
                    $description = $data[2];
                    $amountRaw = $data[3];
                } else {
                    $dateStr = $data[0];
                    $description = $data[1];
                    $amountRaw = $data[2];
                }

                // Try to parse date
                try {
                    // Start by replacing slashes with dashes to help standard parser if needed
                    // But Carbon usually handles both.
                    // Nubank often sends DD/MM/YYYY
                    if (strpos($dateStr, '/') !== false) {
                        $date = Carbon::createFromFormat('d/m/Y', $dateStr);
                    } else {
                        $date = Carbon::parse($dateStr);
                    }
                } catch (\Exception $e) {
                    continue; // Skip invalid dates
                }

                // Handle Amount (Brazilian format 1.234,56 vs US 1,234.56)
                // If delimiter is semi-colon, usually number has comma decimal
                // Remove dots (thousands) and replace comma with dot
                if ($delimiter === ';') {
                    $amountRaw = str_replace('.', '', $amountRaw); // Remove thousands sep
                    $amountRaw = str_replace(',', '.', $amountRaw); // Decimal to dot
                }

                $amount = (float) $amountRaw;

                // Filter zero amounts (using epsilon for float safety)
                if (abs($amount) < 0.001)
                    continue;

                // Determine logic based on sign
                // Nubank/General Credit Card CSV:
                // Positive = Expense (Purchase)
                // Negative = Income (Payment/Reversal)
                $type = 'expense';
                if ($amount < 0) {
                    $type = 'income';
                }

                $transactions[] = [
                    'date' => $date->format('Y-m-d H:i:s'),
                    'amount' => abs($amount),
                    'description' => $description,
                    'type' => $type,
                ];
            }
            fclose($handle);
        }

        return $transactions;
    }
}
