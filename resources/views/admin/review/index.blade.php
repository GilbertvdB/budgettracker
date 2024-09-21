<x-admin-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Reviews') }}
            </h2>
            <a href="{{ url()->previous() }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>
    
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">

        <!-- display table -->
        <div class="container mx-auto">

            @if ($reviews->isEmpty())
                <p class="text-gray-700 dark:text-white">No reviews available.</p>
            @else
                <table class="min-w-full bg-white rounded-t-lg">
                    <thead>
                        <tr class="text-left">
                            <th class="py-4 px-4 border-b border-gray-300">Id</th>
                            <th class="py-4 px-4 border-b border-gray-300">Total</th>
                            <th class="py-4 px-4 border-b border-gray-300">Status</th>
                            <th class="py-4 px-4 border-b border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @foreach ($reviews as $review)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-indigo-50 text-left">
                                <td class="py-4 px-4 border-b border-gray-300">{{ $review->id }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $review->total }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $review->status }}</td>
                                <td class="py-2 px-4 border-b border-gray-300">
                                    <a href="{{ route('admin.reviews.edit', $review->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 sm:hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <span class="hidden sm:inline">Review</span>
                                    </a>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2" onclick="return confirm('Are you sure you want to delete this review?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 sm:hidden">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>    
                                            <span class="hidden sm:inline">Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>