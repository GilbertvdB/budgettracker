<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>

        <div class="relative border">
            @if(auth()->user()->is_premium)
                <!-- Show Add Budget button for premium users -->
                <a href="{{ route('budgets.create') }}" class="border bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    {{ __('Add Budgets') }}
                </a>
            @else
                <!-- Overlay button and disable it for non-premium users -->
                <div class="relative">
                    <a href="javascript:void(0);" class="border bg-blue-500 text-white px-4 py-2 rounded cursor-not-allowed opacity-50">
                        {{ __('Add Budgets') }}
                    </a>

                    <!-- Overlay and SVG for non-premium users -->
                    <div class="absolute left-0 bg-gray-800 bg-opacity-30 flex justify-center items-center">
                        <div class="flex items-center text-amber-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                                <path d="M11.983 1.907a.75.75 0 0 0-1.292-.657l-8.5 9.5A.75.75 0 0 0 2.75 12h6.572l-1.305 6.093a.75.75 0 0 0 1.292.657l8.5-9.5A.75.75 0 0 0 17.25 8h-6.572l1.305-6.093Z" />
                            </svg>
                            <strong>Premium</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

<!-- display container -->
<div class="container mx-auto">

    <div class="max-w-2xl mx-auto">
    @empty($budgets)
        <p class="text-gray-700 mb-4">No budgets available.</p>
    @else
        @foreach ($budgets as $budget)
            @include('budgets.partials.budget-container')
        @endforeach
    @endempty
    </div>

@if(!$sharedBudgets->isEmpty())
    <div class="max-w-2xl mx-auto">
        <div class="w-full bg-white rounded-xl shadow-lg p-2 mb-4">
            <span>Shared Budgets</span>
        </div>

        @foreach ($sharedBudgets as $budget)
            @include('budgets.partials.budget-container')
        @endforeach
    </div>
@endif

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
<script>
    // Function to automatically submit the form when a radio button is changed
    function submitForm(budgetId) {
        document.getElementById('active-form-' + budgetId).submit();
    }
</script>
