<x-app-layout>

<div class="py-4 space-y-4">
    @foreach( $budgets as $budget)
    <div class="max-w-2xl mx-auto px-2">
        <div class="w-full bg-white rounded-xl shadow-lg">
            <div class="flex items-center">
                <div class="flex flex-col text-gray-900 w-full">
                    <div class="flex justify-between pt-1">
                        <strong class="pl-2">{{ $budget->title }}</strong>
                        
                        <!-- Modal actions-->
                        <div>
                            @include('budgets.partials.modal-actions')
                        </div>
                    </div>
                    <div class="flex space-x-2 items-center px-2">
                        <!-- Progress bar -->
                        @php
                            $percentage = ($budget->rest_amount / $budget->amount) * 100;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 h-3 rounded-full" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <div class="flex items-center">
                            <strong class="">{{ $budget->rest_amount }}</strong>
                            <small>/{{ $budget->amount }}</small>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pl-2">
                        <!-- Shared users section -->
                        <div class="flex-grow">
                            @if($budget->shared_users_email)
                            <small>{{ $budget->shared_users_email }}</small>
                            @else
                            <span></span> <!-- Empty span to maintain structure if no shared users -->
                            @endif
                        </div>
                        <!-- upload file -->
                        <div class="flex flex-col-l h-full px-2">
                            <input type="file" name="receipt" id="receipt" class="hidden" onchange="handleFileUpload(event)" accept="image/*" capture="environment" required>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height:30px" class="cursor-pointer" onclick="triggerFileUpload()">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <!-- <label for="receipt" class="block text-center text-gray-700 text-xs font-bold">
                            Upload Receipt
                        </label> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="max-w-2xl mx-auto px-2">
        <div class="w-full flex justify-center bg-white rounded-xl shadow-lg py-2">
            <a href="{{route('budgets.create')}}" class="flex space-x-1 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Add a budget to track</span>
            </a>
        </div>
    </div>

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
