<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ $project->name }}
                </h2>

                <p class="text-sm text-gray-500">
                    Detail Project
                </p>
            </div>

            <a
                href="{{ route('projects.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-lg text-sm"
            >
                ← Kembali
            </a>

        </div>

    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Informasi Project --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">

                <h3 class="font-bold text-gray-800 text-lg mb-2">
                    Informasi Project
                </h3>

                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    {{ $project->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <div class="text-xs text-gray-400 border-t pt-3">
                    Dibuat pada:
                    {{ $project->created_at->format('d M Y, H:i') }}
                </div>

            </div>

            {{-- Daftar Task --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="font-bold text-gray-800 text-lg">
                        Daftar Tugas
                    </h3>

                    {{-- Tombol tambah task nanti bisa dikerjakan Lintang --}}
                    {{-- 
                    <a href="#" class="...">
                        + Tambah Task
                    </a>
                    --}}

                </div>

                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-lg p-8 text-center">

                    <p class="text-gray-500 text-sm">
                        Daftar tugas akan terhubung dengan modul
                        <strong>SRS 2 - Manajemen Tugas</strong>.
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>