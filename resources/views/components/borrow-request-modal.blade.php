<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="borrowRequestModal">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-green-700" id="modalItemTitle">Yêu cầu mượn: </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="borrowRequestForm" method="POST" action="{{ route('borrow-requests.store') }}">
                @csrf
                <input type="hidden" name="item_id" id="modalItemId">

                <!-- Thông tin người mượn -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="borrower_name">Họ và tên *</label>
                    <input type="text" id="borrower_name" name="borrower_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                           value="{{ Auth::user()->name ?? '' }}">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_info">Email/SĐT liên hệ *</label>
                    <input type="text" id="contact_info" name="contact_info" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                           value="{{ Auth::user()->email ?? '' }}">
                </div>

                <!-- Thông tin mượn -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Thời gian mượn *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-500 text-xs mb-1">Từ ngày</label>
                            <input type="date" name="start_date" required min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>
                        <div>
                            <label class="block text-gray-500 text-xs mb-1">Đến ngày</label>
                            <input type="date" name="end_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="purpose">Mục đích sử dụng *</label>
                    <select id="purpose" name="purpose" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option value="">-- Chọn mục đích --</option>
                        <option value="Học tập">Học tập</option>
                        <option value="Ôn thi">Ôn thi</option>
                        <option value="Dự án">Dự án</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="message">Lời nhắn cho người cho mượn</label>
                    <textarea id="message" name="message" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                              placeholder="Xin vui lòng cho mình mượn..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="agreement" required
                               class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Tôi cam kết sử dụng và bảo quản cẩn thận</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()"
                            class="mr-2 px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                        Hủy
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Gửi yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(itemId, itemName) {
        document.getElementById('modalItemId').value = itemId;
        document.getElementById('modalItemTitle').textContent = 'Yêu cầu mượn: ' + itemName;
        document.getElementById('borrowRequestModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Set min end date based on start date
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');

        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
    }

    function closeModal() {
        document.getElementById('borrowRequestModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Xử lý form submit
    document.getElementById('borrowRequestForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate dates
        const startDate = new Date(this.start_date.value);
        const endDate = new Date(this.end_date.value);

        if (endDate <= startDate) {
            alert('Ngày kết thúc phải sau ngày bắt đầu');
            return;
        }

        if (!this.purpose.value) {
            alert('Vui lòng chọn mục đích sử dụng');
            return;
        }

        // Submit form
        this.submit();
    });
</script>
