<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">

                {{-- Pesan error --}}
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ route('projects.update', $project->id) }}"
                    method="POST"
                    class="space-y-5"
                >

                    @csrf
                    @method('PUT')

                    {{-- Nama Project --}}
                    <div>

                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Nama Project
                            <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $project->name) }}"
                            class="border-gray-300 rounded-lg shadow-sm w-full py-2.5 px-3 text-gray-800"
                            required
                        >

                    </div>

                    {{-- Deskripsi --}}
                    <div>

                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            Deskripsi Project
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="border-gray-300 rounded-lg shadow-sm w-full py-2.5 px-3 text-gray-800"
                        >{{ old('description', $project->description) }}</textarea>

                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">

                        <a
                            href="{{ route('projects.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg font-medium"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-sm"
                        >
                            Update Project
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>