<?php

namespace App\Livewire\Public\Scan;

use App\Models\SPPD;
use App\Models\SuratPerintah;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')]
#[Title('Scan QR Code')]

class Detail extends Component
{
    public $typeData = null;
    public $data;
    public $dataLogs;
    public $dataLastLog;
    public $dataTte;

    public function mount($id)
    {
        $currentRouteName = request()->route()->getName();

        if ($currentRouteName === 'scan.spt') {
            $this->typeData = 'surat_perintah';
        } elseif ($currentRouteName === 'scan.sppd') {
            $this->typeData = 'sppd';
        } else {
            abort(404);
        }

        if ($this->typeData === 'surat_perintah') {
            $this->loadDataSpt($id);
        } elseif ($this->typeData === 'sppd') {
            $this->loadDataSppd($id);
        }
    }

    public function loadDataSpt($id)
    {
        $data = SuratPerintah::where('uuid',$id)->firstOrFail();
        $dataLogs = $data->statusLogs;
        $dataLastLog = $data->lastStatusLog;
        $dataTte = $data->tteRecord;

        $this->data = $data;
        $this->dataLogs = $dataLogs;
        $this->dataLastLog = $dataLastLog;
        $this->dataTte = $dataTte;
        // dd($data, $dataLogs, $dataLastLog, $dataTte);
    }

    public function loadDataSppd($id)
    {
        $data = SPPD::where('uuid',$id)->firstOrFail();
        $dataLogs = $data->statusLogs;
        $dataLastLog = $data->lastStatusLog;
        $dataTte = $data->tteRecord;

        $this->data = $data;
        $this->dataLogs = $dataLogs;
        $this->dataLastLog = $dataLastLog;
        $this->dataTte = $dataTte;
    }

    public function render()
    {
        return view('livewire.public.scan.detail');
    }
}
