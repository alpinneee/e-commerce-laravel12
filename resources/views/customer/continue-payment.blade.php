@extends('layouts.customer')

@section('title', 'Lanjutkan Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Lanjutkan Pembayaran</h1>
        
        <!-- Order Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nomor Pesanan</p>
                    <p class="font-semibold">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                    <p class="font-semibold text-lg text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu Pembayaran
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pesanan</p>
                    <p class="font-semibold">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Button -->
        <div class="text-center">
            <button type="button" onclick="payNow()" 
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                    id="pay-button">
                Bayar Sekarang
            </button>
            
            <div class="mt-4">
                <a href="{{ route('profile.orders') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
function payNow() {
    const payButton = document.getElementById('pay-button');
    payButton.disabled = true;
    payButton.innerHTML = '⏳ Memproses...';
    
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('Payment success:', result);
            alert('Pembayaran berhasil!');
            window.location.href = '{{ route("profile.orders") }}';
        },
        onPending: function(result) {
            console.log('Payment pending:', result);
            alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
            window.location.href = '{{ route("profile.orders") }}';
        },
        onError: function(result) {
            console.log('Payment error:', result);
            alert('Pembayaran gagal. Silakan coba lagi.');
            payButton.disabled = false;
            payButton.innerHTML = 'Bayar Sekarang';
        },
        onClose: function() {
            console.log('Payment popup closed');
            payButton.disabled = false;
            payButton.innerHTML = 'Bayar Sekarang';
        }
    });
}
</script>
@endsection