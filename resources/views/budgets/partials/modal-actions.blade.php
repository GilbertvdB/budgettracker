<div x-data="{ isOpen: false }">
    <!-- Button from above -->
    <button @click="isOpen = true" class="focus:outline-none">
        <!-- Three Dot SVG Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
        </svg>
    </button>
    
    <!-- Modal -->
    <div
        x-show="isOpen"
        @click.away="isOpen = false"
        class="fixed inset-0 flex justify-center items-end bg-gray-800 bg-opacity-50"
        x-cloak
    >
        <!-- Modal content -->
        <div class="bg-white dark:bg-gray-700 w-full p-4 rounded-t-lg">
            <div class="flex justify-between">
                <strong>Manage budget</strong>
                <!-- Close button -->
                <button @click="isOpen = false" class="text-gray-600 float-right mb-4 rounded-full bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal Options -->
            <ul class="space-y-4 text-left">
            <li class="hover:bg-blue-100 dark:hover:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700">
                <!-- Expenses -->
                <a href="{{ route('expenses.show', $budget) }}" class="flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                    <span>{{ __('Expenses')}}</span>
                </a>
            </li>
            <li class="hover:bg-blue-100 dark:hover:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700">
                    <!-- Edit Button -->
                    <a href="{{ route('budgets.edit', $budget)}}" class="flex space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        <span>{{ __('Edit')}}</span>
                    </a>
                </li>
                <li class="hover:bg-blue-100 dark:hover:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700">
                    <!-- Share Button -->
                    <a href="{{ route('budgets.share', $budget)}}" class="flex space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        <span>{{ __('Share')}}</span>
                    </a>
                </li>
                @if(!$budget->users->isEmpty())
                <li class="hover:bg-blue-100 dark:hover:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700">
                    <!-- Unshare Button -->
                    <a href="{{ route('budgets.unshare', $budget)}}" class="flex space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        <span>{{ __('Unshare')}}</span>
                    </a>
                </li>
                @endif
                <li class="hover:bg-blue-100 dark:hover:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-700 dark:focus:text-gray-300 dark:focus:border-gray-700">
                    <!-- Delete Button -->
                    <form action="{{ route('budgets.destroy', $budget)}}" method="POST" class="inline text-red-500">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this budget?')" class="flex space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                            <span>{{ __('Delete')}}</span>
                    </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<style>
.modal-content {
    position: fixed;
    bottom: -100%; /* Start off-screen */
    left: 0;
    right: 0;
    width: 100%;
    background-color: white;
    transition: bottom 0.4s ease; /* Smooth transition effect */
}

.modal-content.open {
    bottom: 0; /* Slide up into view */
    transition: bottom 5s ease;
}

[x-cloak] {
    display: none;
}
</style>
