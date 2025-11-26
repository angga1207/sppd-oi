<?php

namespace App\Livewire\Admin\SuratPerintah;

use Livewire\Component;
use App\Models\SuratPerintah;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
#[Title('Preview Surat Perintah')]
class Preview extends Component
{
    public $dataId;
    public $previewData;

    public function mount($id)
    {
        if (!$id) {
            return redirect()->route('admin.surat-perintah.index');
        }
        $this->dataId = $id;
        $this->previewData = SuratPerintah::find($id);
    }

    public function render()
    {
        return view('livewire.admin.surat-perintah.preview');
    }
}
