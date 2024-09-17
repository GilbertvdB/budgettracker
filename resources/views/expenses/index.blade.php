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
        @foreach($expenses as $expense)
        <!-- Expenses list -->
        <div>

            <!-- Show expenses dynamically -->
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
        </div>
        @endforeach
        <!-- Show a message if no expenses are found -->
        <p>No expenses found for the selected budget.</p>
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
