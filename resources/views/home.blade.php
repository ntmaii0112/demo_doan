@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Left Ad Section -->
        <div class="ads">Advertisement</div>

        <div class="flex-1">
            <!-- Search Section -->
            <section class="p-8 text-center">
                <h2 class="text-3xl font-bold text-green-700">Give & Get Smart – Share Your Unused School Supplies!</h2>
                <div class="mt-4">
                    <form method="GET" action="{{ route('items.search') }}" class="flex justify-center max-w-3xl mx-auto">
                        <input type="text" name="query" placeholder="Search for supplies..."
                               class="border border-gray-300 rounded-l-md px-4 py-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-green-600"
                               value="{{ request('query') ?? '' }}">
                        <button class="bg-green-600 text-white px-6 py-2 rounded-r-md hover:bg-green-700 transition" type="submit">
                            Search
                        </button>
                    </form>
                </div>
            </section>

            <!-- Search Results Section -->
            @if(isset($searchResults) && $searchResults->count() > 0)
                <section class="p-4 bg-white">
                    <h2 class="text-2xl font-bold mb-6 text-green-700 pl-4">
                        Search Results for "{{ request('query') }}"
                    </h2>
                    <div class="space-y-4">
                        @foreach ($searchResults as $item)
                            <div class="bg-white border rounded-lg overflow-hidden hover:shadow-md transition flex flex-col md:flex-row min-w-0">
                                <!-- Tách riêng phần clickable và button -->
                                <div class="flex flex-col md:flex-row">
                                    <!-- Phần thông tin có thể click -->
                                    <a href="{{ route('items.show', $item->id) }}" class="flex flex-1">
                                        <!-- Image Column -->
                                        <div class="md:w-1/4 flex-shrink-0">
                                            @if($item->first_image_url)
                                                <img src="{{ $item->first_image_url }}"
                                                     alt="{{ $item->name }}"
                                                     class="w-full h-48 md:h-full object-cover">
                                            @else
                                                <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="848px">
                                            @endif
                                        </div>

                                        <!-- Info Column -->
                                        <div class="flex-1 min-w-0 p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-bold text-green-700">{{ $item->name }}</h3>
                                                    <p class="text-gray-500 text-sm mt-1">
                                                        {{ $item->created_at->format('Y') }} -
                                                        {{ ucfirst($item->item_condition) }} -
                                                        Đã sử dụng
                                                    </p>
                                                </div>
                                            </div>

                                            <p class="text-gray-600 mt-2">{{ Str::limit($item->description, 100) }}</p>

                                            <div class="flex items-center mt-3 text-sm text-gray-500">
                                                <span class="mr-2">📍 {{ $item->location ?? 'N/A' }}</span>
                                                <span class="px-2 py-1 rounded
                                                    @if($item->status === 'available') bg-green-100 text-green-800
                                                    @elseif($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </div>

                                            <div class="flex items-center mt-4">
                                                <div class="flex items-center">
                                                    <div class="bg-gray-100 rounded-full p-1 mr-2">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium">{{ $item->user->name ?? 'Unknown' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="p-4 flex items-center justify-end md:justify-center w-48 flex-shrink-0">
                                        <x-borrow-button
                                            :item="$item"
                                            :requestCount="$requestCount"
                                            :userRequests="$userRequests"
                                        />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($searchResults->hasPages())
                        <div class="mt-6">
                            {{ $searchResults->appends(request()->query())->links() }}
                        </div>
                    @endif
                </section>
            @elseif(request()->has('query'))
                <section class="p-8 bg-white text-center">
                    <p class="text-gray-600">No items found for "{{ request('query') }}"</p>
                    <a href="{{ route('home') }}" class="text-green-600 hover:underline mt-2 inline-block">
                        Back to home
                    </a>
                </section>
            @endif

            <!-- Featured Items Section (Chỉ hiển thị khi không có tìm kiếm) -->
            @if(!request()->has('query'))
                <section class="p-8 bg-yellow-100">
                    <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Featured Items</h2>
                    <div class="slider flex gap-4 overflow-x-auto">
                        @foreach ($featuredItems as $item)
                            <a href="{{ route('items.show', $item->id) }}" class="slider-item min-w-[200px] bg-white p-4 shadow rounded-md block hover:bg-green-50 transition">
                                @if($item->first_image_url)
                                    <img src="{{ $item->first_image_url }}"
                                         alt="{{ $item->name }}"
                                         class="w-full h-40 object-cover rounded-t-md mb-3">
                                @else
                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center rounded-t-md mb-3">
                                        <span class="text-gray-500 text-sm">No Image</span>
                                    </div>
                                @endif

                                <div class="font-bold text-lg text-green-700">{{ $item->name }}</div>
                                <div class="text-gray-600 text-sm">{{ Str::limit($item->description, 60) }}</div>
                                <div class="text-xs mt-2 text-gray-400">#{{ $item->item_condition }} | {{ $item->status }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
                <!-- Categories Section -->
                <section class="p-8">
                    <h2 class="text-2xl font-bold text-center mb-6 text-green-700">Popular Categories</h2>
                    <div class="category-grid">
                        <a href="{{ route('home', ['category' => 1]) }}" class="category-card">📚 Books</a>
                        <a href="{{ route('home', ['category' => 2]) }}" class="category-card">✏️ Stationery</a>
                        <a href="{{ route('home', ['category' => 3]) }}" class="category-card">💻 Gadgets</a>
                        <a href="{{ route('home', ['category' => 4]) }}" class="category-card">🎒 Backpacks</a>
                        <a href="{{ route('home', ['category' => 5]) }}" class="category-card">🎨 Art Supplies</a>
                        <a href="{{ route('home', ['category' => 6]) }}" class="category-card">📝 Notebooks</a>
                    </div>
                </section>
            @endif
        </div>

        <!-- Right Ad Section -->
        <div class="ads">Advertisement</div>
    </div>

    <!-- Include Borrow Request Modal -->
    @include('components.borrow-request-modal')
@endsection
