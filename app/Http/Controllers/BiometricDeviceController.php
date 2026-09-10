<?php

namespace App\Http\Controllers;

use App\Models\BiometricDevice;
use Illuminate\Http\Request;
use Flash;

class BiometricDeviceController extends Controller
{
    public function index()
    {
        if (!isSuperAdmin() && !can('biometric') && !can('manage_biometric_devices')) {
            abort(403, 'Unauthorized access to Biometric Devices.');
        }

        $devices = BiometricDevice::paginate(10);
        return view('biometric_devices.index', compact('devices'));
    }

    public function create()
    {
        if (!isSuperAdmin() && !can('biometric') && !can('manage_biometric_devices')) {
            abort(403, 'Unauthorized access to add Biometric Device.');
        }

        return view('biometric_devices.create');
    }

    public function store(Request $request)
    {
        if (!isSuperAdmin() && !can('biometric') && !can('manage_biometric_devices')) {
            abort(403, 'Unauthorized access to save Biometric Device.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:biometric_devices'
        ]);

        BiometricDevice::create($request->all());

        Flash::success('Biometric device saved successfully.');

        return redirect(route('biometricDevices.index'));
    }

    public function destroy($id)
    {
        if (!isSuperAdmin() && !can('biometric') && !can('manage_biometric_devices')) {
            abort(403, 'Unauthorized access to delete Biometric Device.');
        }

        $device = BiometricDevice::find($id);

        if (empty($device)) {
            Flash::error('Biometric device not found');
            return redirect(route('biometricDevices.index'));
        }

        $device->delete();

        Flash::success('Biometric device deleted successfully.');

        return redirect(route('biometricDevices.index'));
    }
}
