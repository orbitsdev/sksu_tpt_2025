<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationInformation;
use App\Models\ApplicationSlot;
use App\Models\Examination;
use App\Models\ExaminationSlot;
use App\Models\HonorOrAwardsReceived;
use App\Models\Payment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get the applicant user
            $applicant = User::where('email', 'applicant@gmail.com')->first();

            if (!$applicant) {
                $this->command->warn('⚠️ Applicant user not found. Please run DefaultAccountSeeder first.');
                return;
            }

            // Get available examinations
            $examinations = Examination::where('is_public', true)->get();

            if ($examinations->isEmpty()) {
                $this->command->warn('⚠️ No examinations found. Please run ExaminationSeeder first.');
                return;
            }

            // Get available programs
            $programs = Program::where('is_offered', true)->take(5)->get();

            if ($programs->count() < 2) {
                $this->command->warn('⚠️ Not enough programs found. Please run ProgramSeeder first.');
                return;
            }

            // Create 3 sample applications with different steps
            $applicationsData = [
                [
                    'current_step' => 100,
                    'step_description' => 'Admission Decision Finalized',
                    'has_payment' => true,
                    'payment_status' => 'VERIFIED',
                    'has_info' => true,
                    'has_slot' => true,
                    'has_permit' => true,
                    'is_finalized' => true,
                ],
                [
                    'current_step' => 59,
                    'step_description' => 'Submitted for Verification (Pending)',
                    'has_payment' => true,
                    'payment_status' => 'PENDING',
                    'has_info' => true,
                    'has_slot' => false,
                    'has_permit' => false,
                    'is_finalized' => false,
                ],
                [
                    'current_step' => 10,
                    'step_description' => 'Account Creation',
                    'has_payment' => false,
                    'payment_status' => null,
                    'has_info' => false,
                    'has_slot' => false,
                    'has_permit' => false,
                    'is_finalized' => false,
                ],
            ];

            $createdCount = 0;

            foreach ($applicationsData as $index => $appData) {
                $examination = $examinations->random();

                // Create application first
                $application = Application::create([
                    'examination_id' => $examination->id,
                    'user_id' => $applicant->id,
                    'current_step' => $appData['current_step'],
                    'step_description' => $appData['step_description'],
                    'examinee_number' => $appData['has_permit'] ? 'EXM-2025-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT) : null,
                    'permit_number' => $appData['has_permit'] ? 'PERMIT-2025-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT) : null,
                    'permit_issued_at' => $appData['has_permit'] ? now()->subDays(rand(0, 3)) : null,
                    'first_priority_program_id' => $programs->get(0)->id,
                    'second_priority_program_id' => $programs->get(1)->id,
                    'final_program_id' => $appData['is_finalized'] ? $programs->get(0)->id : null,
                    'finalized_at' => $appData['is_finalized'] ? now() : null,
                ]);

                // Create payment if needed (now references application)
                if ($appData['has_payment']) {
                    Payment::create([
                        'application_id' => $application->id,
                        'examination_id' => $examination->id,
                        'applicant_id' => $applicant->id,
                        'cashier_id' => null,
                        'campus_id' => $programs->first()->campus_id,
                        'amount' => 500.00,
                        'amount_paid' => 500.00,
                        'change' => 0.00,
                        'payment_method' => $index === 0 ? 'CASH' : 'GCASH',
                        'payment_channel' => $index === 0 ? null : 'GCash',
                        'payment_reference' => $index === 0 ? null : 'GCASH' . rand(100000, 999999),
                        'official_receipt_number' => $appData['payment_status'] === 'VERIFIED' ? 'OR-2025-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT) : null,
                        'status' => $appData['payment_status'],
                        'paid_at' => now()->subDays(rand(1, 10)),
                        'verified_at' => $appData['payment_status'] === 'VERIFIED' ? now()->subDays(rand(0, 5)) : null,
                        'verified_by' => $appData['payment_status'] === 'VERIFIED' ? 1 : null,
                    ]);
                }

                // Create application information if needed
                if ($appData['has_info']) {
                    ApplicationInformation::create([
                        'application_id' => $application->id,
                        'type' => $index % 2 === 0 ? 'Freshmen' : 'Transferee',
                        'first_name' => $applicant->personalInformation->first_name ?? 'John',
                        'last_name' => $applicant->personalInformation->last_name ?? 'Doe',
                        'extension' => null,
                        'present_address' => 'Barangay Sample, Tacurong City, Sultan Kudarat',
                        'permanent_address' => 'Barangay Sample, Tacurong City, Sultan Kudarat',
                        'contact_number' => '09123456789',
                        'date_of_birth' => now()->subYears(18)->format('Y-m-d'),
                        'place_of_birth' => 'Tacurong City',
                        'tribe' => 'N/A',
                        'religion' => 'Roman Catholic',
                        'nationality' => 'Filipino',
                        'citizenship' => 'Filipino',
                        'sex' => 'Male',
                        'photo' => null,
                        'school_graduated' => 'Sample National High School',
                        'year_graduated' => '2024',
                        'school_last_attended' => 'Sample National High School',
                        'year_last_attended' => '2024',
                        'previous_school_address' => 'Tacurong City, Sultan Kudarat',
                        'track_and_strand_taken' => 'STEM',
                    ]);

                    // Add some honors/awards for the first application
                    if ($index === 0) {
                        HonorOrAwardsReceived::create([
                            'application_id' => $application->id,
                            'title' => 'Academic Excellence Award',
                        ]);
                        HonorOrAwardsReceived::create([
                            'application_id' => $application->id,
                            'title' => 'Science Fair Winner',
                        ]);
                    }
                }

                // Create application slot if needed
                if ($appData['has_slot']) {
                    // Get available examination slot
                    $examinationSlot = ExaminationSlot::where('examination_id', $examination->id)
                        ->where('is_active', true)
                        ->with('rooms')
                        ->first();

                    if ($examinationSlot && $examinationSlot->rooms->isNotEmpty()) {
                        $room = $examinationSlot->rooms->first();

                        ApplicationSlot::create([
                            'application_id' => $application->id,
                            'examination_slot_id' => $examinationSlot->id,
                            'examination_room_id' => $room->id,
                            'seat_number' => rand(1, 30),
                        ]);
                    }
                }

                $createdCount++;
            }

            $this->command->info("✅ ApplicationSeeder: {$createdCount} applications created successfully.");
        });
    }
}
