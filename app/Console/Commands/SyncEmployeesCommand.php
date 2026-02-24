<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncEmployeesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync employee data from external API to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting employee synchronization...');

        try {
            // Get API configuration from environment
            $apiUrl = env('EMPLOYEE_API_URL', 'http://localhost:8001/api/');
            $region = env('EMPLOYEE_API_REGION', '5');
            $apiToken = env('EMPLOYEE_API_TOKEN', '');
            
            $fullUrl = "{$apiUrl}employees/region?region={$region}";
            $this->info("Fetching data from: {$fullUrl}");

            // Fetch data from API with timeout and headers
            $response = Http::timeout(600)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $apiToken,
                ])
                ->get($fullUrl);

            if (!$response->successful()) {
                throw new \Exception("API request failed with status: {$response->status()}");
            }

            $responseData = $response->json();
            
            // Validate response structure
            if (!isset($responseData['data']) || !is_array($responseData['data'])) {
                throw new \Exception("Invalid API response format. Expected 'data' array in response");
            }

            $employees = $responseData['data'];
            $this->info("Fetched " . count($employees) . " employees from API");

            // Start database transaction
            DB::beginTransaction();

            try {
                // Truncate the employees table
                $this->warn("Truncating employees table...");
                DB::table('employees')->truncate();

                // Insert new data in chunks for better performance
                $this->info("Inserting new employee data...");
                
                $chunks = array_chunk($employees, 500);
                $bar = $this->output->createProgressBar(count($employees));
                $bar->start();

                foreach ($chunks as $chunk) {
                    $insertData = [];
                    
                    foreach ($chunk as $employee) {
                        $insertData[] = [
                            'sap' => $employee['sap'] ?? null,
                            'name' => $employee['name'] ?? null,
                            'phone' => $employee['phone'] ?? null,
                            'email' => $employee['email'] ?? null,
                            'birthplace' => $employee['birthplace'] ?? null,
                            'birthdate' => $employee['birthdate'] ?? null,
                            'gender' => $employee['gender'] ?? null,
                            'religion' => $employee['religion'] ?? null,
                            'personnel_area' => $employee['personnel_area'] ?? null,
                            'desc_personnel_area' => $employee['desc_personnel_area'] ?? null,
                            'personnel_subarea' => $employee['personnel_subarea'] ?? null,
                            'desc_personnel_subarea' => $employee['desc_personnel_subarea'] ?? null,
                            'region' => $employee['region'] ?? null,
                            'org_unit' => $employee['org_unit'] ?? null,
                            'desc_org_unit' => $employee['desc_org_unit'] ?? null,
                            'employee_group' => $employee['employee_group'] ?? null,
                            'desc_employee_group' => $employee['desc_employee_group'] ?? null,
                            'employee_subgroup' => $employee['employee_subgroup'] ?? null,
                            'desc_employee_subgroup' => $employee['desc_employee_subgroup'] ?? null,
                            'level' => $employee['level'] ?? null,
                            'position' => $employee['position'] ?? null,
                            'desc_position' => $employee['desc_position'] ?? null,
                            'job' => $employee['job'] ?? null,
                            'desc_job' => $employee['desc_job'] ?? null,
                            'suku' => $employee['suku'] ?? null,
                            'kode_ring' => $employee['kode_ring'] ?? null,
                            'desc_kode_ring' => $employee['desc_kode_ring'] ?? null,
                            'division' => $employee['division'] ?? null,
                            'work_start_date' => $employee['work_start_date'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        $bar->advance();
                    }

                    DB::table('employees')->insert($insertData);
                }

                $bar->finish();
                $this->newLine();

                // Commit transaction
                DB::commit();

                $this->info("✓ Successfully synchronized " . count($employees) . " employees");
                
                Log::info("Employee sync completed successfully", [
                    'count' => count($employees),
                    'api_url' => $fullUrl,
                ]);

                return Command::SUCCESS;

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            $this->error("✗ Employee synchronization failed: " . $e->getMessage());
            
            Log::error("Employee sync failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
