<x-admin-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Feedbacks') }}
            </h2>
            <a href="{{ url()->previous() }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>
    
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">

        <!-- display table -->
        <div class="container mx-auto overflow-x-auto">

            @if ($feedbacks->isEmpty())
                <p class="text-gray-700 dark:text-white">No feedbacks available.</p>
            @else
                <table class="min-w-full bg-white rounded-t-lg">
                    <thead>
                        <tr class="text-left">
                            <th class="py-4 px-4 border-b border-gray-300">Id</th>
                            <th class="py-4 px-4 border-b border-gray-300">User</th>
                            <th class="py-4 px-4 border-b border-gray-300">Created</th>
                            <th class="py-4 px-4 border-b border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @foreach ($feedbacks as $feedback)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-indigo-50 text-left">
                                <td class="py-4 px-4 border-b border-gray-300">{{ $feedback->id }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $feedback->user_id }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $feedback->created_at }}</td>
                                <td class="py-2 px-4 border-b border-gray-300">
                                <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="text-blue-600 hover:text-blue-800">Show</a>
                                    <form action="{{ route('admin.feedbacks.destroy', $feedback) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="mt-4">
                {{ $feedbacks->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>