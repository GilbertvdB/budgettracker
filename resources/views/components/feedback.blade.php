<div x-data="feedbackModal()" class="relative">
    <!-- Feedback Button -->
    <button @click="toggle" class="fixed left-1 bottom-14 bg-blue-500 text-white rounded-full p-1 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
        </svg>
    </button>

    <!-- Feedback Modal -->
    <div x-show="isOpen" @click.away="close" class="fixed left-1 bottom-14 bg-white shadow-lg rounded-lg p-4 w-64 z-50">
        <h3 class="font-bold mb-2">Feedback</h3>
        <textarea x-model="message" placeholder="Type your feedback here..." class="w-full border p-2 rounded" rows="4"></textarea>
        <button @click="sendFeedback" class="mt-2 bg-blue-500 text-white rounded px-4 py-1">Send</button>
        <button @click="close" class="mt-1 text-red-500">Cancel</button>
    </div>
</div>

<script>
    function feedbackModal() {
        return {
            isOpen: false,
            message: '',
            toggle() {
                this.isOpen = !this.isOpen;
            },
            close() {
                this.isOpen = false;
                this.message = '';
            },
            sendFeedback() {
                fetch('/feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Include CSRF token
                    },
                    body: JSON.stringify({ message: this.message }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    this.close(); // Close modal after sending
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

        };
    }
</script>

<style>
    /* Add any additional styling as needed */
    [x-cloak] {
        display: none;
    }
</style>
