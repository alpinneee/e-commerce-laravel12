@extends('layouts.admin')

@section('title', 'Kelola Ukuran - ' . $product->name)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Ukuran</h1>
                <p class="text-gray-600">{{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.product-sizes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Tambah/Edit Ukuran -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah/Edit Ukuran</h2>
                <form action="{{ route('admin.product-sizes.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="size" class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <select name="size" id="size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Pilih Ukuran</option>
                            @foreach($availableSizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                        @error('size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" id="stock" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Ukuran -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ukuran Tersedia</h2>
                @if($product->sizes->count() > 0)
                    <div class="space-y-3">
                        @foreach($product->sizes->sortBy('size') as $size)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <span class="font-medium">Ukuran {{ $size->size }}</span>
                                    <span class="text-sm text-gray-500 ml-2">Stok: {{ $size->stock }}</span>
                                </div>
                                <form action="{{ route('admin.product-sizes.destroy', [$product, $size]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Yakin ingin menghapus ukuran ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Belum ada ukuran yang ditambahkan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection