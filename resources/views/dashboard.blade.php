<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="py-4 space-y-4">
    @if($pinnedBudgets->isEmpty())
    <div class="max-w-2xl mx-auto px-4">
        <div class="w-full bg-white rounded-xl shadow-lg py-2">
        <a href="{{route('budgets.create')}}" class="flex space-x-1 px-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Add a budget to track</span>
        </a>
        </div>
    </div>
        @else
        @foreach( $pinnedBudgets as $budget)
        <div class="max-w-2xl mx-auto px-4">
            <div class="w-full bg-white rounded-xl shadow-lg py-2">
                <div class="flex items-center">
                    <div class="flex flex-col text-gray-900 w-full px-2">
                            <strong>{{ $budget->title }}</strong>
                            @php
                                $percentage = ($budget->rest_amount / $budget->amount) * 100;
                            @endphp
                
                            <!-- Progress bar -->
                            <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
                                <div class="bg-green-500 h-4 rounded-full" style="width: {{ $percentage }}%;"></div>
                            </div>
                            <div class="flex items-center mt-2">
                                <span class="text-lg">Available: <strong class="text-xl">{{ $budget->rest_amount }}</strong></span>
                                <span>/{{ $budget->amount }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col border-l h-full px-2">
                            <!-- Hidden file input -->
                            <input type="file" name="receipt" id="receipt" class="hidden" onchange="handleFileUpload(event)" accept="image/*" capture="environment" required>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height:80px" class="cursor-pointer" onclick="triggerFileUpload()">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <label for="receipt" class="block text-center text-gray-700 text-sm font-bold">
                            Upload Receipt
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
</x-app-layout>
<script>
    // Trigger the hidden file input when the SVG is clicked
    function triggerFileUpload() {
        document.getElementById('receipt').click();
    }

    // Handle the file upload (for example, showing the file name)
    function handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            alert('File selected: ' + file.name); // You can handle the file as needed
        }
    }
</script>
