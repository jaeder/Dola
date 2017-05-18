<?php

namespace DFZ\Dola\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DFZ\Dola\Facades\Dola;

class DolaSettingsController extends Controller
{
    public function index()
    {
        // Check permission
        Dola::canOrFail('browse_settings');

        $settings = Dola::model('Setting')->orderBy('order', 'ASC')->get();

        return view('dola::settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        // Check permission
        Dola::canOrFail('browse_settings');

        $lastSetting = Dola::model('Setting')->orderBy('order', 'DESC')->first();

        if (is_null($lastSetting)) {
            $order = 0;
        } else {
            $order = intval($lastSetting->order) + 1;
        }

        $request->merge(['order' => $order]);
        $request->merge(['value' => '']);

        Dola::model('Setting')->create($request->all());

        return back()->with([
            'message'    => 'Successfully Created Settings',
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request)
    {
        // Check permission
        Dola::canOrFail('visit_settings');

        $settings = Dola::model('Setting')->all();

        foreach ($settings as $setting) {
            $content = $this->getContentBasedOnType($request, 'settings', (object) [
                'type'    => $setting->type,
                'field'   => $setting->key,
                'details' => $setting->details,
            ]);

            if ($content === null && isset($setting->value)) {
                $content = $setting->value;
            }

            $setting->value = $content;
            $setting->save();
        }

        return back()->with([
            'message'    => 'Successfully Saved Settings',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        Dola::canOrFail('browse_settings');

        // Check permission
        Dola::canOrFail('visit_settings');

        Dola::model('Setting')->destroy($id);

        return back()->with([
            'message'    => 'Successfully Deleted Setting',
            'alert-type' => 'success',
        ]);
    }

    public function move_up($id)
    {
        $setting = Dola::model('Setting')->find($id);
        $swapOrder = $setting->order;
        $previousSetting = Dola::model('Setting')->where('order', '<', $swapOrder)->orderBy('order', 'DESC')->first();
        $data = [
            'message'    => 'This is already at the top of the list',
            'alert-type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => "Moved {$setting->display_name} setting order up",
                'alert-type' => 'success',
            ];
        }

        return back()->with($data);
    }

    public function delete_value($id)
    {
        // Check permission
        Dola::canOrFail('browse_settings');

        $setting = Dola::model('Setting')->find($id);

        if (isset($setting->id)) {
            // If the type is an image... Then delete it
            if ($setting->type == 'image') {
                if (Storage::disk(config('dola.storage.disk'))->exists($setting->value)) {
                    Storage::disk(config('dola.storage.disk'))->delete($setting->value);
                }
            }
            $setting->value = '';
            $setting->save();
        }

        return back()->with([
            'message'    => "Successfully removed {$setting->display_name} value",
            'alert-type' => 'success',
        ]);
    }

    public function move_down($id)
    {
        $setting = Dola::model('Setting')->find($id);
        $swapOrder = $setting->order;

        $previousSetting = Dola::model('Setting')->where('order', '>', $swapOrder)->orderBy('order', 'ASC')->first();
        $data = [
            'message'    => 'This is already at the bottom of the list',
            'alert-type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'message'    => "Moved {$setting->display_name} setting order down",
                'alert-type' => 'success',
            ];
        }

        return back()->with($data);
    }
}
