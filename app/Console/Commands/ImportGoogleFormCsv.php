<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ImportGoogleFormCsv extends Command
{
    protected $signature = 'import:google-form {file : Path to the CSV file}';
    protected $description = 'Import Google Form CSV responses into MySQL database and send convocations';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found at: {$filePath}");
            return 1;
        }

        $file = fopen($filePath, 'r');
        
        // Skip header row
        fgetcsv($file);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            $email = trim($row[5] ?? '');

            if (empty($email)) {
                continue;
            }

            // Prevent duplicates if re-running
            if (Registration::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            // Robust Date Parsing (Handles YYYY-MM-DD and d/m/Y)
            $birthDate = null;
            if (!empty($row[7])) {
                try {
                    $birthDate = Carbon::parse(trim($row[7]))->format('Y-m-d');
                } catch (\Exception $e) {
                    $birthDate = null;
                }
            }

            // Normalize Phone Number (Prepend '0' if 9 digits starting with 6 or 7)
            $phone = trim($row[6] ?? '');
            if (preg_match('/^[67]\d{8}$/', $phone)) {
                $phone = '0' . $phone;
            }

            // Create the record in MySQL
            $registration = Registration::create([
                'form_timestamp'    => !empty($row[0]) ? Carbon::parse($row[0]) : now(),
                'nom'               => trim($row[1] ?? ''),
                'prenom'            => trim($row[2] ?? ''),
                'ecole'             => strtoupper(trim($row[3] ?? '')),
                'filiere'           => trim($row[4] ?? ''),
                'email'             => $email,
                'phone_number'      => $phone,
                'date_de_naissance' => $birthDate,
                'ville'             => trim($row[8] ?? ''),
                'quartier'          => trim($row[9] ?? ''),
                'is_present'        => false,
            ]);

            // Dispatch Email with PDF & QR Pass
            try {
                Mail::to($email)->send(new EmailConfirmation($registration));
            } catch (\Exception $e) {
                $this->warn("Imported ID {$registration->id}, but failed to send email: " . $e->getMessage());
            }

            $imported++;
        }

        fclose($file);

        $this->info("Import complete! Imported & Emailed: {$imported} | Skipped (duplicates): {$skipped}");
        return 0;
    }
}