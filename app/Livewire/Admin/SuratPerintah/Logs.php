<?php

namespace App\Livewire\Admin\SuratPerintah;

use Livewire\Component;
use App\Models\SuratPerintah;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Logs extends Component
{
    #[Layout('components.layouts.app')]
    #[Title('Log Surat')]

    public $dataId;
    public $dataSurat;

    public function mount($id)
    {
        if (!$id) {
            return redirect()->route('admin.surat-perintah.index');
        }
        $this->dataId = $id;
        $this->dataSurat = SuratPerintah::findOrFail($id);
    }

    public function render()
    {
        $logs = $this->dataSurat->statusLogs()->orderBy('created_at', 'desc')->get();

        return view('livewire.admin.surat-perintah.logs', [
            'logs' => $logs,
        ]);
    }
}
