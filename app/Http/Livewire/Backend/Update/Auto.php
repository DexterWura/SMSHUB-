<?php

namespace App\Http\Livewire\Backend\Update;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Auto extends Component {

    public $status = [
        'available' => false,
        'disabled' => false,
        'message' => 'No Update Available'
    ];
    public $progress = '';

    protected $listeners = ['apply'];

    public function mount() {
        $this->check();
    }

    public function apply($step = 0) {
        if ($this->status['available'] == false && $this->status['disabled'] == false) {
            $this->progress .= '<div class="text-white">No Update Available</div>';
        } else {
            if ($step === 0) {
                $this->progress .= '<div class="text-white">Fetching Files from Server</div>';
                $this->emit('apply', 1);
            } else if ($step === 1) {
                try {
                    $request = Http::get(base64_decode('aHR0cHM6Ly9wb3J0YWwudGhlaHAuaW4vYXBpL3VwZGF0ZS90c21z'), [
                        'domain' => $_SERVER['HTTP_HOST'],
                        'version' => config('app.settings.version')
                    ]);
                    Storage::put('files.zip', $request->getBody());
                    $zip = new \ZipArchive;
                    if ($zip->open(Storage::path('files.zip')) === TRUE) {
                        $this->progress .= '<div class="text-green-500">Extracting Files</div>';
                        $zip->extractTo(base_path());
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $item = $zip->getNameIndex($i);
                            $this->progress .= '<div class="text-white">/' . $item . '</div>';
                        }
                        $zip->close();
                    } else {
                        throw new Exception('Not able to Open ZIP file');
                    }
                    $this->progress .= '<div class="text-green-500">Files Received and Updated Successfully</div>';
                    $this->progress .= '<div class="text-white">Updating Available Vendor Files</div>';
                    Storage::delete('files.zip');
                    $this->emit('apply', 2);
                } catch (Exception $e) {
                    $this->progress .= '<div class="text-red-600">Encountered Error' . $e->getMessage() . '</div>';
                }
            } else if ($step === 2) {
                try {
                    if (file_exists(base_path() . '/vendor_new')) {
                        File::deleteDirectory(base_path('vendor'));
                        rename(base_path('vendor_new'), base_path('vendor'));
                    }
                    $this->progress .= '<div class="text-green-500 font-bold">Vendor Files Updated</div>';
                    $this->progress .= '<div class="text-white">Preparing Database Changes</div>';
                    $this->emit('apply', 3);
                } catch (Exception $e) {
                    Artisan::call('migrate:rollback', ["--step" => 1]);
                    $this->progress .= '<div class="text-red-600">Encountered Error' . $e->getMessage() . '</div>';
                }
            } else if ($step === 3) {
                try {
                    Artisan::call('migrate', ["--force" => true]);
                    Artisan::call('db:seed', ["--force" => true]);
                    Artisan::call('view:clear');
                    $this->progress .= '<div class="text-green-500">Database Changes Completed Successfully</div>';
                    Setting::put('version', $this->status['version']);
                    $this->progress .= '<br><div class="text-green-500 font-bold">Version Upgrade Completed</div>';
                    $this->status = [
                        'available' => false,
                        'disabled' => false,
                        'message' => 'No Update Available'
                    ];
                } catch (Exception $e) {
                    $this->progress .= '<div class="text-red-600">Encountered Error' . $e->getMessage() . '</div>';
                }
            }
        }
    }

    public function render() {
        return view('backend.update.auto');
    }

    /** Check for Update */
    private function check() {
        try {
            $request = Http::get(base64_decode('aHR0cHM6Ly9wb3J0YWwudGhlaHAuaW4vYXBpL2NoZWNrL3RzbXM'), [
                'domain' => $_SERVER['HTTP_HOST'],
            ]);
            $response = $request->object();
            if (isset($response->error)) {
                return false;
            }
            $php = $response->php;
            $version = $response->version;
            if (version_compare(config('app.settings.version'), $version, '<')) {
                if (version_compare(phpversion(), $php, '<')) {
                    $this->status = [
                        'available' => true,
                        'disabled' => true,
                        'message' => 'Version ' . $version . ' available. However, you need to upgrade your PHP version to ' . $php . ' or above to apply the Update.',
                        'version' => $version
                    ];
                    return false;
                }
                $this->status = [
                    'available' => true,
                    'disabled' => false,
                    'message' => 'Version ' . $version . ' available. You can apply this OTA Update by clicking on below Apply button.',
                    'version' => $version
                ];
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
