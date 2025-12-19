<?php

namespace App\Http\Controllers\API;

use App\Models\SPPD;
use App\Models\User;
use App\Traits\JsonReturner;
use Illuminate\Http\Request;
use App\Models\SuratPerintah;
use App\Models\StatusSuratLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MobileSPTResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MobileSPPDResource;

class MobileController extends Controller
{
    use JsonReturner;

    public function getSuratPerintahList(Request $request)
    {
        // verify API x-api-key header
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return $this->wrongKeyTokenResponse('Missing API Key', 200);
        }
        if ($apiKey != env('API_KEY')) {
            return $this->wrongKeyTokenResponse('Invalid API Key', 200);
        }

        $validate = Validator::make($request->all(), [
            'nip' => 'required|string',
            'search' => 'nullable|string',
            'statusFilter' => 'nullable|in:draft,published,completed',
            'instanceFilter' => 'nullable|integer|exists:instances,id',
            'dateFilter' => 'nullable|date',
            'perPage' => 'nullable|integer|min:1|max:100',
        ], [], [
            'nip' => 'NIP',
            'search' => 'Kata Kunci Pencarian',
            'statusFilter' => 'Filter Status',
            'instanceFilter' => 'Filter Instansi',
            'dateFilter' => 'Filter Tanggal',
            'perPage' => 'Data Per Halaman',
        ]);

        if ($validate->fails()) {
            return $this->validationResponse($validate->errors()->first());
        }

        $auth = User::where('username', $request->nip)->first();
        if (!$auth) {
            return $this->errorResponse('User not found', 404);
        }

        $datas = SuratPerintah::search($request->search)
            ->with(['employeeGiver', 'sppds', 'instance', 'publicationEmployee'])
            // ->when($auth->instance_id, function ($query) {
            //     $query->where('instance_id', $auth->instance_id);
            // })
            ->when($auth->instance_id, function ($query) use ($auth) {
                $query->where('employee_giver_id', $auth->id)
                    ->orWhere('publication_employee_id', $auth->id)
                    ->orWhere('created_by', $auth->id)
                    ->orWhereRelation('sppds', 'employee_executor_id', $auth->employee_id);
                //   ->orWhereHas('sppds', function ($sppdQuery) {
                //       $sppdQuery->where('employee_executor_id', $auth->id);
                //   });
            })
            ->when($request->statusFilter, function ($query) use ($request) {
                $query->where('status', $request->statusFilter);
            })
            ->when($request->instanceFilter, function ($query) use ($request) {
                $query->where('instance_id', $request->instanceFilter);
            })
            ->when($request->dateFilter, function ($query) use ($request) {
                $query->whereDate('publication_date', $request->dateFilter);
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate($request->perPage);

        return $this->successResponse(MobileSPTResource::collection($datas), 'Surat Perintah fetched successfully');
    }

    public function getSuratPerintahDetail(Request $request, $id)
    {
        // verify API x-api-key header
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return $this->wrongKeyTokenResponse('Missing API Key', 200);
        }
        if ($apiKey != env('API_KEY')) {
            return $this->wrongKeyTokenResponse('Invalid API Key', 200);
        }

        $validate = Validator::make($request->all(), [
            'nip' => 'required|string',
        ], [], [
            'nip' => 'NIP',
        ]);
        if ($validate->fails()) {
            return $this->validationResponse($validate->errors()->first());
        }

        $data = SuratPerintah::with(['employeeGiver', 'sppds', 'instance', 'publicationEmployee'])
            ->find($id);

        if (!$data) {
            return $this->errorResponse('Surat Perintah not found', 404);
        }

        return $this->successResponse(new MobileSPTResource($data), 'Surat Perintah detail fetched successfully');
    }

    public function getSppdDetail(Request $request, $id)
    {
        // verify API x-api-key header
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return $this->wrongKeyTokenResponse('Missing API Key', 200);
        }
        if ($apiKey != env('API_KEY')) {
            return $this->wrongKeyTokenResponse('Invalid API Key', 200);
        }

        $validate = Validator::make($request->all(), [
            'nip' => 'required|string',
        ], [], [
            'nip' => 'NIP',
        ]);
        if ($validate->fails()) {
            return $this->validationResponse($validate->errors()->first());
        }

        $data = SPPD::with(['suratPerintah', 'employeeGiver', 'employeeGiverInstance', 'employeeExecutor', 'employeeExecutorInstance', 'instance', 'instancePembebanan', 'publicationEmployee'])
            ->find($id);

        if (!$data) {
            return $this->errorResponse('SPPD not found', 404);
        }

        return $this->successResponse(new MobileSPPDResource($data), 'SPPD detail fetched successfully');
    }

    public function rejectSPT(Request $request, $id)
    {
        // verify API x-api-key header
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return $this->wrongKeyTokenResponse('Missing API Key', 200);
        }
        if ($apiKey != env('API_KEY')) {
            return $this->wrongKeyTokenResponse('Invalid API Key', 200);
        }

        $validate = Validator::make($request->all(), [
            'nip' => 'required|string',
            'keterangan' => 'required|string|max:1000',
        ], [], [
            'nip' => 'NIP',
            'keterangan' => 'Keterangan',
        ]);
        if ($validate->fails()) {
            return $this->validationResponse($validate->errors()->first());
        }

        $data = SuratPerintah::find($id);
        if (!$data) {
            return $this->errorResponse('Surat Perintah not found', 404);
        }

        $auth = User::where('username', $request->nip)->first();
        if (!$auth) {
            return $this->errorResponse('User not found', 404);
        }

        DB::beginTransaction();
        try {
            // Create initial status log
            StatusSuratLog::create([
                'type' => 'surat_perintah',
                'reference_id' => $data->id,
                'old_status' => $data->status,
                'new_status' => 'rejected',
                'keterangan' => $request->keterangan ?? 'Surat Perintah Tugas ditolak melalui aplikasi mobile' . $auth->name ? ' oleh ' . $auth->name : '.',
            ]);

            $data->status = 'rejected';
            $data->save();
            DB::commit();

            return $this->successResponse(null, 'Surat Perintah Tugas Berhasil Ditolak');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to reject Surat Perintah: ' . $e->getMessage(), 500);
        }
    }

    public function approveSPT(Request $request, $id)
    {
        // verify API x-api-key header
        $apiKey = $request->header('x-api-key');
        if (!$apiKey) {
            return $this->wrongKeyTokenResponse('Missing API Key', 200);
        }
        if ($apiKey != env('API_KEY')) {
            return $this->wrongKeyTokenResponse('Invalid API Key', 200);
        }

        $validate = Validator::make($request->all(), [
            'nip' => 'required|string',
            'keterangan' => 'nullable|string|max:1000',
            'passphrase' => 'required|string',
        ], [], [
            'nip' => 'NIP',
            'keterangan' => 'Keterangan',
            'passphrase' => 'Passphrase',
        ]);
        if ($validate->fails()) {
            return $this->validationResponse($validate->errors()->first());
        }

        $data = SuratPerintah::find($id);
        if (!$data) {
            return $this->errorResponse('Surat Perintah Tugas not found', 404);
        }

        // check data already approved
        if ($data->status == 'approved') {
            return $this->errorResponse('Surat Perintah Tugas telah ditandatangani.');
        }

        if ($data->status == 'draft') {
            return $this->errorResponse('Surat Perintah Tugas masih dalam status draft. Silakan kirim terlebih dahulu sebelum menandatangani.');
        }

        $auth = User::where('username', $request->nip)->first();
        if (!$auth) {
            return $this->errorResponse('User not found', 404);
        }

        $pathPdfSPT = storage_path('app/public/surat_perintah_tugas/' . $data->file_pdf);
        if (!file_exists($pathPdfSPT)) {
            return $this->errorResponse('PDF file not found. Please generate the PDF before approving.', 404);
        }

        $ttdSPT = $this->TandaTanganDigital('spt', $pathPdfSPT, $request->passphrase, $auth, $data);
        if ($ttdSPT['status'] == 'error') {
            return $this->errorResponse($ttdSPT['message'], 500);
        }

        if ($ttdSPT['status'] == 'success') {
            // TTD SPPD jika ada
            $sppds = SPPD::where('surat_perintah_id', $data->id)->get();
            foreach ($sppds as $sppd) {
                $pathPdfSPPD = storage_path('app/public/sppd/' . $sppd->file_pdf);
                if (file_exists($pathPdfSPPD)) {
                    $ttdSPPD = $this->TandaTanganDigital('sppd', $pathPdfSPPD, $request->passphrase, $auth, $sppd);
                    if ($ttdSPPD['status'] == 'error') {
                        return $this->errorResponse('Failed to sign SPPD ID ' . $sppd->id . ': ' . $ttdSPPD['message'], 500);
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            // Create initial status log
            StatusSuratLog::create([
                'type' => 'surat_perintah',
                'reference_id' => $data->id,
                'old_status' => $data->status,
                'new_status' => 'approved',
                'keterangan' => 'Surat Perintah Tugas ditandatangani melalui aplikasi mobile' . ($auth->name ? ' oleh ' . $auth->name : '.'),
            ]);

            $data->status = 'approved';
            $data->tanggal_tte = now();
            $data->save();

            // check and update SPPD status if all signed
            $sppds = SPPD::where('surat_perintah_id', $data->id)->get();
            foreach ($sppds as $sppd) {
                if ($sppd->file_pdf_signed) {
                    $sppd->status = 'approved';
                    $data->tanggal_tte = now();
                    $sppd->save();
                    // Create initial status log for SPPD
                    StatusSuratLog::create([
                        'type' => 'sppd',
                        'reference_id' => $sppd->id,
                        'old_status' => $sppd->status,
                        'new_status' => 'approved',
                        'keterangan' => 'SPPD ditandatangani secara digital melalui aplikasi mobile' . ($auth->name ? ' oleh ' . $auth->name : '.'),
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse(null, 'Surat Perintah Tugas Berhasil Ditandatangani');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to approve Surat Perintah: ' . $e->getMessage(), 500);
        }
    }

    private function TandaTanganDigital($type, $pathFilePdf, $passphrase, $user, $data)
    {
        if ($user == null) {
            return [
                'status' => 'error',
                'message' => 'User not found',
            ];
        }

        if ($type == 'spt') {
            $pathByType = storage_path('app/public/surat_perintah_tugas/');
        } else if ($type == 'sppd') {
            $pathByType = storage_path('app/public/sppd/');
        } else {
            return [
                'status' => 'error',
                'message' => 'Invalid document type for digital signature',
            ];
        }
        $path_pdf = $pathByType . $data->file_pdf;

        if (!file_exists($path_pdf)) {
            return [
                'status' => 'error',
                'message' => 'PDF file not found for digital signature',
            ];
        }

        if ($type == 'spt') {
            $path_output = storage_path('app/public/surat_perintah_tugas_sign');
        } else if ($type == 'sppd') {
            $path_output = storage_path('app/public/sppd_sign');
        }

        // Pastikan folder output ada
        if (!file_exists($path_output)) {
            mkdir($path_output, 0777, true);
        }

        $server_ip = 'http://103.162.35.72';
        $server_user = 'esign';
        $server_pass = 'qwerty';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $server_ip . '/api/sign/pdf',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10, // batas waktu 10 detik
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_USERPWD => "$server_user:$server_pass",
            CURLOPT_POSTFIELDS => array(
                'file' => curl_file_create($path_pdf, 'application/pdf'),
                // 'nik' => $user->nik,
                // 'passphrase' => $passphrase,
                'nik' => '1610071606900001',
                'passphrase' => 'Juventini@1897',
                'halaman' => 'pertama',
                'image' => 'false',
                'linkQR' => 'https://sppd.oganilirkab.go.id/',
                'tampilan' => 'invisible'
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode("$server_user:$server_pass")
            ),
        ));

        $result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error_msg = curl_error($curl);
        curl_close($curl);

        // Tangani error koneksi ke server
        if ($result === false || $http_code != 200) {
            return [
                'status' => 'error',
                // 'message' => 'Failed to connect to digital signature server: ' . $curl_error_msg,
                'message' => 'Passphrase atau NIK yang Anda masukkan salah.',
            ];
        }

        // Cek apakah respons JSON error atau file PDF
        $decoded = json_decode($result);
        if (json_last_error() === JSON_ERROR_NONE && isset($decoded->error)) {
            return [
                'status' => 'error',
                'message' => 'Digital signature error: ' . $decoded->error,
            ];
        }

        // Simpan file hasil TTD
        $get_filename = explode('.pdf', $data->file_pdf);
        $filename = $get_filename[0] . '-sign.pdf';
        file_put_contents($path_output . '/' . $filename, $result);

        if ($type == 'spt') {
            $saveVerify = SuratPerintah::where('id', $data->id)->first();
            $saveVerify->file_pdf_signed = $filename;
            // $saveVerify->tanggal_tte = now();
            $saveVerify->save();
        } else if ($type == 'sppd') {
            $saveVerify = SPPD::where('id', $data->id)->first();
            $saveVerify->file_pdf_signed = $filename;
            // $saveVerify->tanggal_tte = now();
            $saveVerify->save();
        }

        return [
            'status' => 'success',
            'message' => 'Document signed successfully',
        ];

        // $this->dispatch('close-modal');
        // $this->dispatch('notificationkananatas', icon: 'success', title: 'Surat berhasil ditanda tangani secara elektronik');
    }
}
