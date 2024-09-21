<x-admin-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Feedbacks') }}
        </h2>
        <a href="{{ route('admin.feedbacks.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            {{ __('Back') }}
        </a>
    </div>
</x-slot>
    
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <!-- <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8"> -->
        <div class="flex flex-col w-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
            <strong>Feedback</strong>
            <span>by {{ $feedback->user->email }}</span>    
            <div class="rounded-lg border bg-gray-700 p-4 mt-1">
                <p>{{ $feedback->message }}</p>
            </div>
        <!-- </div> -->
    </div>
</div>
</x-admin-layout>