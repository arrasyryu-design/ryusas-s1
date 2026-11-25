<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pelaporan terbuka untuk siapa saja (tanpa login)
    }

    public function rules(): array
    {
        return [
            'reporter_name' => ['nullable', 'string', 'max:100'],
            'reporter_class' => ['nullable', 'string', 'max:50'],
            'reporter_contact' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', Rule::exists('categories', 'id')->where('is_active', true)],
            'location' => ['required', 'string', 'max:200'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'min:20', 'max:3000'],
            'photos' => ['required', 'array', 'min:1', 'max:3'],
            'photos.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'category_id.exists' => 'Kategori kerusakan yang dipilih tidak valid.',
            'title.max' => 'Judul laporan maksimal :max karakter.',
            'description.min' => 'Deskripsi kerusakan minimal :min karakter.',
            'photos.required' => 'Foto kerusakan wajib diunggah (minimal 1 foto).',
            'photos.min' => 'Foto kerusakan wajib diunggah (minimal 1 foto).',
            'photos.max' => 'Maksimal 3 foto yang dapat diunggah.',
            'photos.*.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 5 MB.',
            'photos.*.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya maksimal 5 MB.',
            'max' => ':attribute terlalu panjang (maksimal :max karakter).',
        ];
    }

    public function attributes(): array
    {
        return [
            'reporter_name' => 'Nama pelapor',
            'reporter_class' => 'Kelas',
            'reporter_contact' => 'Kontak',
            'category_id' => 'Kategori kerusakan',
            'location' => 'Lokasi kerusakan',
            'title' => 'Judul laporan',
            'description' => 'Deskripsi kerusakan',
            'photos' => 'Foto kerusakan',
        ];
    }
}
