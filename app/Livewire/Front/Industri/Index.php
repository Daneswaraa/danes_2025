<?php

namespace App\Livewire\Front\Industri;

use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Pkl;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $indNama, $indBidUs, $indAlmt, $indKontak, $indEmail, $indWebsite;
    public $siswaId, $mulai, $selesai;
    public $isOpen = 0;

    use WithPagination;

    public $rowPerPage = 3;
    public $search;
    public $userMail;

    public function mount()
    {
        // Membaca email user yang sedang login
        $this->userMail = Auth::user()->email;

        // Ambil data siswa berdasarkan email pengguna yang sedang login
        $siswa = Siswa::where('email', '=', $this->userMail)->first();

        // Isi properti siswaId jika data siswa ditemukan
        $this->siswaId = $siswa ? $siswa->id : null;
    }

    public function render()
    {
        return view('livewire.front.industri.index', [
            'industris' => $this->search === null
                ? Industri::latest()->paginate($this->rowPerPage)
                : Industri::latest()
                    ->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('bidang_usaha', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%')
                    ->paginate($this->rowPerPage),
            'siswa_login' => Siswa::where('email', '=', $this->userMail)->first(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->indNama = '';
        $this->indBidUs = '';
        $this->indAlmt = '';
        $this->indKontak = '';
        $this->indEmail = '';
        $this->indWebsite = '';
        $this->mulai = '';
        $this->selesai = '';
    }

    public function store()
    {
        $this->validate([
            'siswaId'    => 'required|exists:siswas,id', // Validasi siswaId
            'indNama'    => 'required|exists:industris,id', // Pastikan industri_id valid
            'indBidUs'   => 'required|exists:gurus,id', // Pastikan guru_id valid
            'indAlmt'    => 'required|string',
            'indKontak'  => 'required|string',
            'indEmail'   => 'required|email',
            'indWebsite' => 'nullable|url',
            'mulai'      => 'required|date',
            'selesai'    => 'required|date|after:mulai',
        ]);

        DB::beginTransaction();

        try {
            // Cari data siswa berdasarkan ID
            $siswa = Siswa::findOrFail($this->siswaId);

            // Periksa apakah siswa sudah melapor PKL
            if ($siswa->status_lapor_pkl) {
                DB::rollBack(); // Batalkan transaksi
                $this->closeModal();

                return redirect()->route('dashboard')->with('error', 'Transaksi dibatalkan: Siswa sudah melapor.');
            }

            // Simpan data PKL
            Pkl::create([
                'siswa_id'    => $this->siswaId,
                'industri_id' => $this->indNama,
                'guru_id'     => $this->indBidUs,
                'mulai'       => $this->mulai,
                'selesai'     => $this->selesai,
            ]);

            // Update status_lapor siswa
            $siswa->update(['status_lapor_pkl' => 1]);

            DB::commit(); // Commit transaksi

            $this->closeModal();
            $this->resetInputFields();

            return redirect()->route('dashboard')->with('success', 'Data PKL berhasil disimpan dan status siswa diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika terjadi error
            $this->closeModal();

            // Log error untuk debugging (opsional)
            \Log::error('Terjadi kesalahan saat menyimpan data PKL: ' . $e->getMessage());

            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}