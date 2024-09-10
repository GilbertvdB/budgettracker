<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Budgets') }}
            </h2>
            <a href="{{ route('budgets.create') }}" class="border bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                {{ __('Add Budgets') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

<!-- display table -->
<div class="container mx-auto">

    @if ($budgets->isEmpty())
        <p class="text-gray-700">No budgets available.</p>
    @else
        <table class="w-full bg-white rounded-t-xl shadow-lg">
            <thead>
                <tr class="text-left">
                    <th class="py-4 px-4 border-b border-gray-300">Title</th>
                    <th class="py-4 px-4 border-b border-gray-300">Active</th>
                    <th class="py-4 px-4 border-b border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="border">
                @foreach ($budgets as $budget)
                    <tr class="odd:bg-gray-50 even:bg-white hover:bg-indigo-50 text-left">
                        <td class="py-4 px-4 border-b border-gray-300">{{ $budget->title }}</td>
                        <td class="py-4 px-4 border-b border-gray-300">{{ ($budget->active == 1) ? 'Active' : '' }}</td>
                        <td class="py-2 px-4 border-b border-gray-300">
                            <a href="#" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 ml-2" onclick="return confirm('Are you sure you want to delete this budget?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

</div>
</x-app-layout>
