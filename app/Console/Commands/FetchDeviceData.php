<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\V1\Device\Models\Device;
use Modules\V1\Device\Models\DeviceVendor;

final class FetchDeviceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-device-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from the Tenovi API and insert it into the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $apiUrl = 'https://api2.tenovi.com/clients/fortis-software-solution/hwi/hwi-device-types/';
        $apiKey = 'Api-Key jriYObl6.3GjZHCnxr7Fdi9LZHe3MMcIrvDTxkc3H';
        DB::beginTransaction();
        try {

            // Fetch vendor ID from the database
            $vendor = DeviceVendor::where('name', 'Tenovi')->first();
            //$vendor = DB::table('device_vendors')->where('name', 'Tenovi')->first();
            if ( ! $vendor) {
                $this->error('Vendor "Tenovi" not found.');

                return;
            }
            $vendorId = $vendor->uuid;
            //dd($vendorId);
            // Make the POST request with headers
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
            ])->get($apiUrl);

            // Check for a successful response
            if ($response->successful()) {
                $data = $response->json();
                //dd($data);
                foreach ($data as $device) {
                    // Save or update the device information
                    $deviceData = Device::firstOrNew(['api_key' => $device['id']]);  //new Device();
                    $deviceData->device_vendor_id = $vendorId;
                    $deviceData->device_type = 'RPM';
                    $deviceData->sensor_code = $device['sensor_code'];
                    $deviceData->image = $device['image'];
                    $deviceData->name = $device['name'];
                    $deviceData->up_front_cost = $device['up_front_cost'];
                    $deviceData->shipping_cost = $device['shipping_cost'];
                    $deviceData->monthly_cost = $device['monthly_cost'];
                    $deviceData->sensor_id_required = $device['sensor_id_required'];
                    $deviceData->in_stock = $device['in_stock'];
                    $deviceData->virtual = $device['virtual'];
                    $deviceData->deprecated = $device['deprecated'];
                    $deviceData->save();

                    /*$deviceId = DB::table('devices')->updateOrInsert(
                        ['api_key' => $device['id']], // Match based on device ID
                        [
                            'device_vendor_id' => $vendorId,
                            'name' => $device['name'],
                            'sensor_code' => $device['sensor_code'],
                            'image' => $device['image'],
                            'up_front_cost' => $device['up_front_cost'],
                            'shipping_cost' => $device['shipping_cost'],
                            'monthly_cost' => $device['monthly_cost'],
                            'sensor_id_required' => $device['sensor_id_required'],
                            'in_stock' => $device['in_stock'],
                            'virtual' => $device['virtual'],
                            'deprecated' => $device['deprecated'],
                            'created_at' => now(),
                            'updated_at' => now(),v
                        ]
                    );*/

                    // Save metrics for each device
                    /*foreach ($device['metrics'] as $metric) {
                        DB::table('metrics')->updateOrInsert(
                            ['id' => $metric['id']], // Match based on metric ID
                            [
                                'device_id' => $deviceId,
                                'name' => $metric['name'],
                                'primary_units' => $metric['primary_units'],
                                'primary_display_name' => $metric['primary_display_name'],
                                'secondary_units' => $metric['secondary_units'],
                                'secondary_display_name' => $metric['secondary_display_name'],
                                'default_y_axis_max' => $metric['default_y_axis_max'],
                                'default_y_axis_min' => $metric['default_y_axis_min'],
                                'is_vital' => $metric['is_vital'],
                                'created' => $metric['created'],
                                'modified' => $metric['modified'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }*/
                }
                DB::commit();
                $this->info('Data fetched and inserted successfully.');
            } else {
                $this->error('Failed to fetch data: ' . $response->status());
                DB::rollBack();
            }
        } catch (Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }
}
