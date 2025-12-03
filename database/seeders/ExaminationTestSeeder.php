<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationSlot;
use App\Models\Examination;
use App\Models\ExaminationSlot;
use App\Models\TestCenter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExaminationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // 🧩 1️⃣ Create a sample examination
            $exam = Examination::create([
                'title' => 'SKSU College Entrance Examination 2025',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(10),
                'school_year' => '2025-2026',
                'type' => 'College Admission',
                'is_published' => true,
                'is_application_open' => true,
                'show_result' => false,
            ]);

            // 🏢 2️⃣ Use existing test centers (or create if needed)
            $testCenter1 = TestCenter::firstOrCreate(
                ['name' => 'Main Campus Testing Center'],
                [
                    'campus_id' => 1,
                    'address' => 'Main Campus Building A',
                    'is_active' => true,
                ]
            );

            $testCenter2 = TestCenter::firstOrCreate(
                ['name' => 'Access Campus Testing Center'],
                [
                    'campus_id' => 2,
                    'address' => 'Access Campus Building B',
                    'is_active' => true,
                ]
            );

            // 🏫 3️⃣ Create examination slots for test centers
            $slotDataList = [
                [
                    'test_center_id' => $testCenter1->id,
                    'building_name' => 'Main Building A',
                    'date_of_exam' => now()->addDays(7),
                    'total_examinees' => 100,
                    'number_of_rooms' => 4,
                    'is_active' => true,
                ],
                [
                    'test_center_id' => $testCenter2->id,
                    'building_name' => 'ACCESS Center',
                    'date_of_exam' => now()->addDays(8),
                    'total_examinees' => 120,
                    'number_of_rooms' => 5,
                    'is_active' => true,
                ],
            ];

            foreach ($slotDataList as $slotData) {
                $slot = $exam->examinationSlots()->create($slotData);

                // 🪑 Auto-generate rooms per slot (capacity is now computed)
                for ($i = 1; $i <= $slotData['number_of_rooms']; $i++) {
                    $slot->rooms()->create([
                        'room_number' => 'Room '.$i,
                    ]);
                }
            }

            // ✅ Reload slots & rooms after they’re created
            $allSlots = ExaminationSlot::with('rooms')->get();

            // 🎓 4️⃣ Use the existing applicant user
            $applicant = User::where('email', 'applicant@gmail.com')->first();

            if (! $applicant) {
                throw new \Exception('⚠️ applicant@gmail.com user not found. Please run DefaultAccountSeeder first.');
            }

            // 👩‍🎓 5️⃣ Create 10 dummy applications for the applicant
            for ($i = 1; $i <= 10; $i++) {
                $application = Application::create([
                    'examination_id' => $exam->id,
                    'user_id' => $applicant->id, // ✅ real user
                    'exam_number' => rand(1000, 9999),
                    'examinee_number' => "EXM-2025-{$i}",
                    'permit_number' => "PERMIT-{$i}",
                    'permit_issued_at' => now(),
                    'status' => 'Approved',
                ]);

                // 🎯 Assign to random slot & room
                $randomSlot = $allSlots->random();
                $availableRooms = $randomSlot->rooms->filter(fn($room) => !$room->isFull());

                if ($availableRooms->isNotEmpty()) {
                    $randomRoom = $availableRooms->random();

                    ApplicationSlot::create([
                        'application_id' => $application->id,
                        'examination_slot_id' => $randomSlot->id,
                        'examination_room_id' => $randomRoom->id,
                        'seat_number' => $randomRoom->occupied + 1,
                    ]);
                }
            }
        });

        echo "✅ Examination test data seeded successfully.\n";
    }
}
