<?php

namespace App\Http\Controllers;

use App\Models\BiometricCommand;
use App\Models\BiometricDevice;
use Illuminate\Http\Request;
use Flash;

class BiometricCommandController extends Controller
{
    public function index()
    {
        $commands = BiometricCommand::with('device')->orderBy('id', 'desc')->paginate(20);
        return view('biometric_commands.index', compact('commands'));
    }

    public function create()
    {
        $devices = BiometricDevice::pluck('name', 'id');
        // If names are not present, fallback to serial numbers
        if ($devices->isEmpty() || $devices->filter()->isEmpty()) {
            $devices = BiometricDevice::pluck('serial_number', 'id');
        } else {
            // Append serial number for clarity
            $devices = BiometricDevice::all()->mapWithKeys(function ($device) {
                return [$device->id => ($device->name ?: 'Unnamed') . ' (' . $device->serial_number . ')'];
            });
        }

        return view('biometric_commands.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'biometric_device_id' => 'required|exists:biometric_devices,id',
            'command_type' => 'required|string',
        ]);

        $commandString = $request->command_type;
        if ($request->filled('command_params')) {
            $commandString .= " " . $request->command_params;
        }

        BiometricCommand::create([
            'biometric_device_id' => $request->biometric_device_id,
            'command_type' => $request->command_type,
            'command_string' => $commandString,
            'status' => 'pending'
        ]);

        Flash::success('Command queued successfully. The device will pick it up on its next request.');

        return redirect(route('biometricCommands.index'));
    }

    public function destroy($id)
    {
        $command = BiometricCommand::find($id);

        if (empty($command)) {
            Flash::error('Command not found');
            return redirect(route('biometricCommands.index'));
        }

        $command->delete();

        Flash::success('Command deleted successfully.');

        return redirect(route('biometricCommands.index'));
    }
}
