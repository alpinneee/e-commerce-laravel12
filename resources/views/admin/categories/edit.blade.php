@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            ← Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    @if($category->image)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" 
                             class="w-24 h-24 object-cover rounded border">
                    @else
                        <p class="text-gray-500">Belum ada gambar</p>
                    @endif
                </div>

                <!-- New Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru</label>
                    <input type="file" name="image" id="image" accept="image/*" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="previewNewImage(this)">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB. Disarankan ukuran 300x300px.</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div id="newImagePreview" class="mt-2 hidden">
                        <img id="newPreview" class="w-24 h-24 object-cover rounded border">
                    </div>
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewNewImage(input) {
    const preview = document.getElementById('newPreview');
    const previewContainer = document.getElementById('newImagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.classList.add('hidden');
    }
}
</script>
@endsection 