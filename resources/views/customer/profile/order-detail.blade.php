@extends('layouts.customer')

@section('title', 'Order Details - Toko Sepatu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
                    <p class="text-gray-600 mt-2">Order #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('profile.orders') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Status</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Order Date</p>
                            <p class="text-gray-900">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                                                            @if($item->product && $item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded object-cover">
                                            @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->product->name ?? 'Product not available' }}</h3>
                                    <p class="text-sm text-gray-600">Size: {{ $item->size ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Information -->
                @if($order->shipping_address)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Information</h2>
                    <div class="space-y-2">
                        <p class="text-gray-900"><strong>{{ $order->shipping_address_object->name }}</strong></p>
                        <p class="text-gray-600">{{ $order->shipping_address_object->email }}</p>
                        <p class="text-gray-600">{{ $order->shipping_address_object->address }}</p>
                        <p class="text-gray-600">{{ $order->shipping_address_object->city }}, {{ $order->shipping_address_object->province }} {{ $order->shipping_address_object->postal_code }}</p>
                        <p class="text-gray-600">Phone: {{ $order->shipping_address_object->phone }}</p>
                        @if($order->shipping_expedition_name)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-600">Shipping Method</p>
                            <p class="text-gray-900">{{ $order->shipping_expedition_name }}</p>
                            @if($order->shipping_estimation)
                            <p class="text-sm text-gray-600">Estimated: {{ $order->shipping_estimation }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="text-green-600">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->cod_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">COD Fee</span>
                            <span class="text-gray-900">Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total</span>
                                <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Payment Method</p>
                            <p class="text-gray-900">{{ $order->payment_method_label }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-600">Payment Status</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_method !== 'cod' && $order->payment_status === 'pending')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('order.continue-payment', $order) }}" 
                           class="w-full inline-block text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Continue Payment
                        </a>
                    </div>
                    @endif
                    
                    @if(isset($transactionDetails) && $transactionDetails)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button onclick="window.print()" 
                                class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            🖨️ Print Invoice
                        </button>
                    </div>
                    @endif
                    
                    @if($order->status === 'delivered')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button onclick="openReviewModal()" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Leave Review
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Midtrans Invoice -->
        @if(isset($transactionDetails) && $transactionDetails)
        <div class="mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-blue-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"/>
                        <path d="M6 8h8v2H6V8zm0 4h4v2H6v-2z"/>
                    </svg>
                    Midtrans Payment Invoice
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        @if(isset($transactionDetails['transaction_id']))
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Transaction ID</span>
                            <span class="font-mono text-sm bg-white px-2 py-1 rounded">{{ $transactionDetails['transaction_id'] }}</span>
                        </div>
                        @endif
                        
                        @if(isset($transactionDetails['payment_type']))
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Payment Type</span>
                            <span class="font-medium">{{ strtoupper($transactionDetails['payment_type']) }}</span>
                        </div>
                        @endif
                        
                        @if(isset($transactionDetails['transaction_time']))
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Transaction Time</span>
                            <span class="font-medium">{{ date('d M Y H:i', strtotime($transactionDetails['transaction_time'])) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-3">
                        @if(isset($transactionDetails['transaction_status']))
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Status</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($transactionDetails['transaction_status'] === 'settlement' || $transactionDetails['transaction_status'] === 'capture')
                                    bg-green-100 text-green-800
                                @elseif($transactionDetails['transaction_status'] === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ strtoupper($transactionDetails['transaction_status']) }}
                            </span>
                        </div>
                        @endif
                        
                        @if(isset($transactionDetails['gross_amount']))
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Amount</span>
                            <span class="font-bold">{{ $transactionDetails['currency'] ?? 'IDR' }} {{ number_format($transactionDetails['gross_amount'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        @if(isset($transactionDetails['fraud_status']) && $transactionDetails['fraud_status'] !== 'accept')
                        <div class="flex justify-between">
                            <span class="text-blue-700 font-medium">Fraud Status</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ strtoupper($transactionDetails['fraud_status']) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <p class="text-xs text-blue-600">
                        <strong>Note:</strong> This is your official payment receipt from Midtrans. Keep this for your records.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Review Modal -->
@if($order->status === 'delivered')
<div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Leave Review</h3>
                    <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="reviewForm" action="{{ route('orders.review', $order) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        @foreach($order->items as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    @if($item->size)
                                    <p class="text-sm text-gray-600">Size: {{ $item->size }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                    <div class="flex space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" onclick="setRating({{ $item->product_id }}, {{ $i }})" 
                                                class="star-btn text-gray-300 hover:text-yellow-400 focus:outline-none" 
                                                data-product="{{ $item->product_id }}" data-rating="{{ $i }}">
                                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="reviews[{{ $item->product_id }}][rating]" id="rating_{{ $item->product_id }}" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Review</label>
                                    <textarea name="reviews[{{ $item->product_id }}][comment]" rows="3" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                                              placeholder="Share your experience with this product..." required></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeReviewModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Submit Reviews
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function openReviewModal() {
    document.getElementById('reviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
}

function setRating(productId, rating) {
    document.getElementById('rating_' + productId).value = rating;
    
    // Update star display
    const stars = document.querySelectorAll(`[data-product="${productId}"]`);
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}
</script>

@endsection 