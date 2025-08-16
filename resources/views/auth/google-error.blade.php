<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Google Login Error</h2>
        <p class="mt-2 text-gray-500">Terjadi masalah saat login dengan Google</p>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error Details:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p><strong>Message:</strong> {{ $error ?? 'Unknown error' }}</p>
                    @if(isset($details))
                        <p class="mt-1"><strong>Details:</strong> {{ $details }}</p>
                    @endif
                    @if(isset($file))
                        <p class="mt-1"><strong>File:</strong> {{ $file }}</p>
                    @endif
                    @if(isset($line))
                        <p class="mt-1"><strong>Line:</strong> {{ $line }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <a href="{{ route('login') }}" 
           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            Kembali ke Login
        </a>
        
        <a href="/google-test-form" 
           class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            Coba Google Login (Test Form)
        </a>
    </div>
</x-guest-layout>