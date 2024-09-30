<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <x-action-button>
            <a href="{{ url()->previous() }}">
                {{ __('Back') }}
            </a>
            </x-action-button>
        </div>
    </x-slot>

    
<div class="max-w-2xl mx-auto text-gray-800 dark:text-gray-300 p-4 sm:p-6 lg:p-8">
    @if($expenses->isEmpty())
        <span>No expenses found for the selected budget.</span>
    @else
        @foreach($expenses as $expense)
        <div class="container mx-auto mb-4 space-y-3">
            <div class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
                <div class="flex flex-col">
                    <div class="flex justify-between">
                        <span class="text-lg">{{ $expense->updated_at->format('d F') }}</span>
                        @if($expense->total_verified !== 1)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        @endif
                    </div>
                    <small>by {{ $expense->user->email }}</small>
                    <div class="flex justify-between pt-2">
                        <img src="https://placehold.co/30" alt="receipt-icon">
                        <span class="text-xl">{{ $expense->total }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
