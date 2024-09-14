<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <x-action-button>
            <a href="{{ url()->previous() }}">
                {{ __('Back') }}
            </a>
            </x-action-button>
        </div>
    </x-slot>

    
<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    @if($expenses->isEmpty())
    <a href="{{route('budgets.create')}}" class="flex space-x-1 px-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>Add a budget to track</span>
    </a>
    @else
    <div x-data="expenseData()">
        <div class="w-full bg-white rounded-xl shadow-lg px-4 py-1">
            <x-input-label for="budget" :value="__('Showing expenses for:')" />
            <select name="budget" id="budget" class="block mt-1 mb-2 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" x-model="selectedBudget" @change="fetchExpenses">
                <option value="" disabled selected>Select a budget</option>
                @foreach($budgets as $budget)
                    <option value="{{ $budget->id }}"> {{ $budget->title }} </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
        </div>

    <!-- Expenses list -->
    <div id="expenses-list" class="mt-4">
        <!-- Display loading message -->
        <template x-if="loading">
            <p>Loading expenses...</p>
        </template>

        <!-- Show expenses dynamically -->
        <template x-if="expenses.length > 0">
            <div class="container mx-auto mb-4 space-y-3">
                <template x-for="expense in expenses" :key="expense.id">
                    <div class="w-full bg-white rounded-xl shadow-lg p-4">
                        <div class="flex flex-col">
                            <span x-text="formatDate(expense.updated_at)"></span>
                            <div class="flex justify-between pt-2">
                                <img src="https://placehold.co/30" alt="receipt-icon">
                                <span x-text="expense.total"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <!-- Show a message if no expenses are found -->
        <template x-if="expenses.length === 0 && !loading">
            <p>No expenses found for the selected budget.</p>
        </template>
    </div>
</div>
@endif
</div>
</x-app-layout>
<script>
    function expenseData() {
        return {
            selectedBudget: '', // Track the selected budget
            expenses: [],       // Store the fetched expenses
            loading: false,     // Track loading state

             // Fetch expenses for the selected budget
             async fetchExpenses() {
                if (this.selectedBudget) {
                    this.loading = true;

                    try {
                        const response = await fetch(`/expenses/${this.selectedBudget}`);
                        const data = await response.json();
                        this.expenses = data.expenses.map(expense => ({
                            ...expense,
                            updated_at: new Date(expense.updated_at) // Convert to JS Date object
                        }));
                    } catch (error) {
                        console.error('Error fetching expenses:', error);
                    }

                    this.loading = false;
                }
            },

            // Format the date similar to Blade's d F format
            formatDate(date) {
                return date.toLocaleDateString('en-US', { day: 'numeric', month: 'long' });
            }
        }
    }
</script>
