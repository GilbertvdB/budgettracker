<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Budgets') }}
        </h2>
        <x-action-button>
            <a href="{{ route('budgets.create') }}" class="flex space-x-1 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg> 
            <span>{{ __('Add') }}</span>
            </a>
        </x-action-button>
    </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8" x-data="{ activeTab: 'owned' }">

<!-- Tab Toggle -->
<div class="max-w-2xl mx-auto">
    <div class="flex justify-evenly bg-white dark:bg-gray-800 rounded-xl shadow-lg p-2 mb-4">
        <!-- Tab Button for 'My Budgets' -->
        <span 
            @click="activeTab = 'owned'" 
            :class="{'text-blue-500': activeTab === 'owned', 'text-gray-500': activeTab !== 'owned'}" 
            class="cursor-pointer font-semibold"
        >
            My Budgets
        </span>
        <span class="border"></span>
        <!-- Tab Button for 'Shared Budgets' -->
        <span 
            @click="activeTab = 'shared'" 
            :class="{'text-blue-500': activeTab === 'shared', 'text-gray-500': activeTab !== 'shared'}" 
            class="cursor-pointer font-semibold"
        >
            Shared Budgets
        </span>
    </div>
</div>

<!-- Display Container -->
<div class="container mx-auto">

    <!-- Owned Budgets Section -->
    <div class="max-w-2xl mx-auto" x-show="activeTab === 'owned'">
        @if($budgets->isEmpty())
        <a href="{{route('budgets.create')}}" class="flex space-x-1 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Add a budget to track</span></a>
        @else
            <div class="max-w-2xl mx-auto">
                @foreach ($budgets as $budget)
                    @include('budgets.partials.budget-container')
                @endforeach
            </div>
        @endif
    </div>

    <!-- Shared Budgets Section -->
    <div class="max-w-2xl mx-auto" x-show="activeTab === 'shared'">
        @if($sharedBudgets->isEmpty())
            <p class="px-2">No shared budgets available.</p>
        @else
            <div class="max-w-2xl mx-auto">
                @foreach ($sharedBudgets as $budget)
                    @include('budgets.partials.budget-container')
                @endforeach
            </div>
        @endif
    </div>
</div>
</x-app-layout>
<script>
    // Function to automatically submit the form when a radio button is changed
    function submitForm(budgetId) {
        document.getElementById('active-form-' + budgetId).submit();
    }

    function tabData() {
        return {
            activeTab: 'owned', // Set default tab
        };
    }
</script>
