<x-admin-layout>
    <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8 mt-4 border text-center">
        <!-- upload file -->
        <div class="flex flex-col h-full px-2 dark:text-white">
            <form id="uploadForm" class="flex flex-col items-center" action="{{ route('admin.upload.image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" id="image" class="my-image-field" onchange="compressAndUploadImage(event)" accept="image/*" capture="environment">
                <button class="border p-2 rounded-lg" type="button" onclick="compressAndUploadImage()">Upload</button>
            </form>
        </div>
    </div>
</x-admin-layout>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
function compressAndUploadImage(event) {
    const fileInput = document.getElementById('image');
    const file = fileInput.files[0]; 

    if (!file || !file.type.startsWith('image/')) {
        alert('Please upload a valid image file.');
        return;
    }

    const maxWidth = 1080;
    const maxHeight = 1920;
    const reader = new FileReader();

    reader.onload = function (event) {
        const img = new Image();

        img.onload = function () {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;

            if (width > maxWidth || height > maxHeight) {
                const aspectRatio = width / height;

                if (width > height) {
                    width = maxWidth;
                    height = Math.round(maxWidth / aspectRatio);
                } else {
                    height = maxHeight;
                    width = Math.round(maxHeight * aspectRatio);
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Convert canvas to Blob
            canvas.toBlob(function (blob) {
                const formData = new FormData();
                formData.append('compressed_image', blob, file.name); // Append the compressed image to FormData

                // Make the AJAX request
                fetch('upload-image', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Upload successful:', data);
                    window.location.href = 'ocr/show';
                })
                .catch(error => {
                    console.error('Error during upload:', error);
                });
            }, 'image/jpeg', 0.8);
        };

        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
}
</script>
