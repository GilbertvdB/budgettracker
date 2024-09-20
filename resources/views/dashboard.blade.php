<x-app-layout>
<style>
.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

/* Spin animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Checkmark animation */
.checkmark {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #4CAF50;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #4CAF50;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #4CAF50;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke-check 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

/* Animations */
@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

@keyframes stroke-check {
    100% { stroke-dashoffset: 0; }
}

@keyframes fill {
    100% { box-shadow: inset 0px 0px 0px 30px whitesmoke; }
}

@keyframes scale {
    0%, 100% { transform: none; }
    50% { transform: scale3d(1.1, 1.1, 1); }
}
</style>

<div class="outer-container">

<x-flash-message/>

@if($budgets)
<div class="py-4 space-y-4">
    @foreach( $budgets as $budget)
    <div class="max-w-2xl mx-auto px-2">
        <div class="w-full bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-lg">
            <div class="flex items-center">
                <div class="flex flex-col space-y-2 text-gray-900 dark:text-gray-100 w-full">
                    <div class="flex justify-between pt-1">
                        <strong class="text-xl pl-2">{{ $budget->title }}</strong>
                        
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
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $percentage }}%;"></div>
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
                            <div class="flex space-x-1 items-center">
                            <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            </strong>    
                            <small>{{ $budget->shared_users_email }}</small>
                            </div>
                            @else
                            <span></span> <!-- Empty span to maintain structure if no shared users -->
                            @endif
                        </div>
                        <!-- upload file -->
                        <div class="flex flex-col-l h-full px-2">
                            <form id="uploadForm-{{ $budget->id }}" action="{{ route('upload.receipt', $budget->id ) }}" method="POST" enctype="multipart/form-data" data-budget-id="{{ $budget->id }}">
                                @csrf
                                <input type="file" name="receipt" id="receipt-{{ $budget->id }}" class="my-image-field hidden" onchange="compressAndUploadImage(event)" accept="image/*" capture="environment">
                            </form>

                            <!-- Loading Spinner (initially hidden) -->
                            <div id="loading" class="hidden fixed inset-0 bg-gray-600 bg-opacity-60 flex items-center justify-center z-50">
                                <div id="spinner" class="flex flex-col items-center">
                                    <div class="spinner border-t-4 border-b-4 border-blue-500 rounded-full w-12 h-12"></div>
                                    <p id="compressing" class="text-white text-lg ml-4">Compressing, please wait...</p>
                                    <p id="uploading" class="hidden text-white text-lg ml-4">Uploading, please wait...</p>
                                </div>
                                <div id="success-checkmark" class="hidden flex flex-col items-center">
                                    <!-- Green Checkmark SVG -->
                                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                    </svg>
                                    <p class="text-white text-lg ml-4">Upload complete!</p>
                                </div>
                            </div>
                            
                            <div class="pb-1">
                                <!-- uplobad receipt -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height:40px" class="cursor-pointer" onclick="triggerFileUpload({{$budget->id}})">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            </div>
   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
    <div class="max-w-2xl mx-auto px-2">
        <div class="w-full flex justify-center bg-white dark:bg-gray-800 dark:text-gray-100 rounded-xl shadow-lg py-2">
            <a href="{{route('budgets.create')}}" class="flex space-x-1 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Add a budget to track</span>
            </a>
        </div>
    </div>
</div>
</div>
</x-app-layout>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');   
// Function to trigger the file input click
function triggerFileUpload(budgetId) {
    document.getElementById('receipt-' + budgetId).click();
}

function compressAndUploadImage(event) {
    const inputElement = event.target; // Get the input element that triggered the event
    const formElement = inputElement.closest('form'); // Find the closest form element (ancestor)

    // Retrieve the data-budget-id value from the form
    const budgetId = formElement.getAttribute('data-budget-id');
    console.log('Budget ID:', budgetId);

    const file = event.target.files[0];
    
    if (!file || !file.type.startsWith('image/')) {
        alert('Please upload a valid image file.');
        return;
    }
    document.getElementById('loading').classList.remove('hidden');

    console.log('Original file selected:', file);
    console.log('Original file size (in bytes):', file.size);

    const maxWidth = 1080;
    const maxHeight = 1920;
    const dpi = 96; // DPI (Dots per inch)
    const bitDepth = 24; // 24-bit color

    const reader = new FileReader();

    reader.onload = function (event) {
        const img = new Image();

        img.onload = function () {
            const width = img.width;
            const height = img.height;

            console.log(`Original image dimensions: ${width}x${height}`);

            let newWidth = width;
            let newHeight = height;

            // Check if the image needs resizing
            if (width > maxWidth || height > maxHeight) {
                const aspectRatio = width / height;

                if (width > height) {
                    newWidth = maxWidth;
                    newHeight = Math.round(maxWidth / aspectRatio);
                } else {
                    newHeight = maxHeight;
                    newWidth = Math.round(maxHeight * aspectRatio);
                }

                console.log(`Resizing image to: ${newWidth}x${newHeight}`);
            } else {
                console.log('No resizing required.');
            }

            // Create canvas to resize the image
            const canvas = document.createElement('canvas');
            canvas.width = newWidth;
            canvas.height = newHeight;

            const ctx = canvas.getContext('2d');

            // Apply DPI transformation by adjusting the canvas' resolution
            canvas.style.width = `${newWidth / dpi * 96}px`; // Horizontal DPI = 96
            canvas.style.height = `${newHeight / dpi * 96}px`; // Vertical DPI = 96

            // Draw the resized image on the canvas
            ctx.drawImage(img, 0, 0, newWidth, newHeight);

            // Convert canvas to a Blob (lossy compression, jpeg format with quality 0.8)
            canvas.toBlob(
                function (blob) {
                    console.log('Image compression complete.');
                    console.log('Compressed image size (in bytes):', blob.size);

                    // Create a FormData object to send via AJAX
                    const formData = new FormData();
                    formData.append('receipt', blob, file.name); // append the compressed image
                    formData.append('budget_id', budgetId); // append the budget ID from data attribute

                    // Send the compressed image via AJAX to the server
                    console.log('here we upload formData');
                    uploadImageToServer(formData);
                },
                'image/jpeg', // lossy compression in jpeg format
                0.8 // Quality level (between 0.0 and 1.0)
            );
        };

        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
}

// Example AJAX function to send the compressed image to Laravel backend
function uploadImageToServer(formData) {
    console.log('Starting image upload...');
    document.getElementById('compressing').classList.add('hidden');
    document.getElementById('uploading').classList.remove('hidden');
    
    fetch(`/upload-receipt`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Append debug messages
            // debugElement.innerText += 'Response received\n';
            // debugElement.innerText += `Response data: ${JSON.stringify(data)}\n`;
            
            // Hide the loading spinner
            document.getElementById('spinner').classList.add('hidden');

            // Handle success response
            if (data.success) {
                // Show the green checkmark for success
                document.getElementById('success-checkmark').classList.remove('hidden');

                // Optionally refresh after a delay
                setTimeout(() => {
                    window.location.reload();
                }, 2000); // Delay for 2 seconds before refreshing
            } else {
                alert('Upload failed. Please try again.');
            }
        })
        .catch(error => {
            // Hide the loading spinner
            document.getElementById('loading').classList.add('hidden');

            // Append error messages
            // debugElement.innerText += 'Error occurred\n';
            // debugElement.innerText += `Error message: ${error.message}\n`;

            // Handle error
            console.error('Error uploading file:', error);
            alert('An error occurred. Please try again.');
        });
}

</script>

