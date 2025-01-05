<?php

namespace App\Http\Controllers\Supervisor;

use Illuminate\Http\Request;
use App\Models\LaporKesalahan;
use App\Models\User;
use App\Http\Controllers\Controller;


class LaporKesalahanController extends Controller
{
    public function viewLapor(){
        $lapor_kesalahans = LaporKesalahan::all();

        return view ('supervisor.lapor', compact ('lapor_kesalahans'));
    }
    public function storeLapor(Request $request) {
        // Validate the incoming request data
        $lapor = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
        ]);
    
        // Store the data with the user's ID
        LaporKesalahan::create([
            'user_id' => $lapor['user_id'], // Storing the user ID
            'tanggal' => $lapor['tanggal'],
            'kategori' => $lapor['kategori'],
            'keterangan' => $lapor['keterangan'],
            'status' => 'Diproses' // Initial status
        ]);
    
        // Redirect or return a response
        return redirect()->route('supervisor.lapor')->with('success', 'Laporan kesalahan berhasil ditambahkan.');
    }
    public function editLapor($id)
    {
        // Find the report by its ID
        $lapor = LaporKesalahan::findOrFail($id);
        
        // Fetch the list of users with the role 'pegawai'
        $users = User::where('role', 'pegawai')->get();

        // Return the edit view with the report data
        return view('supervisor.edit-lapor', compact('lapor', 'users'));
    }

    public function updateLapor(Request $request, $id){
        // Validate the request data
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        // Find the specific report by its ID
        $lapor = LaporKesalahan::findOrFail($id);

        // Force the status to always be 'Diproses'
        $validatedData['status'] = 'Diproses';

        // Update the report with the validated data
        $lapor->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('supervisor.lapor')->with('success', 'Laporan kesalahan berhasil diupdate.');
    }
    
    public function viewpegawaiLaporans() {
        // Get all reports assigned to the logged-in user
        $laporans = LaporKesalahan::where('user_id', auth()->user()->id)->get();
    
        return view('pegawai.laporan', compact('laporans'));
    }
    
    public function updateStatus($id) {
        $laporan = LaporKesalahan::findOrFail($id);
    
        // Ensure that only the assigned user can mark the report as done
        if ($laporan->user_id == auth()->user()->id) {
            $laporan->update([
                'status' => 'Selesai'
            ]);
        }
    
        return redirect()->back()->with('success', 'Laporan berhasil diselesaikan.');
    }
}
