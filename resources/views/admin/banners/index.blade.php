@extends('layouts.admin')

@section('title', 'Kelola Banner')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Banner</h1>
        <a href="{{ route('admin.banners.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Tambah Banner
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($banners as $banner)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img class="h-16 w-24 object-cover rounded" src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $banner->title }}</h3>
                                <p class="text-sm text-gray-500">{{ strlen($banner->description) > 100 ? substr($banner->description, 0, 100) . '...' : $banner->description }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs px-2 py-1 rounded {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">Urutan: {{ $banner->order }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4 text-center text-gray-500">
                    Belum ada banner. <a href="{{ route('admin.banners.create') }}" class="text-blue-600 hover:underline">Tambah banner pertama</a>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection