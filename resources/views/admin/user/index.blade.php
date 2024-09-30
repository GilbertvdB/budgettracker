<x-admin-layout>
<x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Users') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Add User') }}
            </a>
        </div>
    </x-slot>
    
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">

        <!-- display table -->
        <div class="container mx-auto overflow-x-auto">

            @if ($users->isEmpty())
                <p class="text-gray-700 dark:text-white">No users available.</p>
            @else
                <table class="min-w-full bg-white rounded-t-lg">
                    <thead>
                        <tr class="text-left">
                            <th class="py-4 px-4 border-b border-gray-300">Name</th>
                            <th class="py-4 px-4 border-b border-gray-300">Email</th>
                            <th class="py-4 px-4 border-b border-gray-300">Premium</th>
                            <th class="py-4 px-4 border-b border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border">
                        @foreach ($users as $user)
                            <tr class="odd:bg-gray-50 even:bg-white hover:bg-indigo-50 text-left">
                                <td class="py-4 px-4 border-b border-gray-300">{{ $user->name }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $user->email }}</td>
                                <td class="py-4 px-4 border-b border-gray-300">{{ $user->is_premium }}</td>
                                <td class="py-2 px-4 border-b border-gray-300">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>