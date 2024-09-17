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
                    <span>{{ $expense->updated_at->format('d F') }}</span>
                    <div class="flex justify-between pt-2">
                        <img src="https://placehold.co/30" alt="receipt-icon">
                        <span>{{ $expense->total }}</span>
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
