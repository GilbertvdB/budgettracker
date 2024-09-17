<x-app-layout>
<div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="w-full text-center bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl shadow-lg p-4 mb-4">
        <strong>{{$budget->title}}</strong>    
        <p>Choose email to ushare.</p>
        <form method="POST" action="{{ route('budgets.unshareBudget', $budget) }}">
            @csrf
            <!-- Email Address -->
            <div class="mt-1">
                <select name="email" id="email" class="block mt-1 mb-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                @foreach($budget->users as $user)
                    <option value="{{ $user->id }}"> {{ $user->email }} </option>
                @endforeach
                </select>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-secondary-button class="ms-4">
                <a href="{{ route('dashboard') }}">
                    {{ __('Cancel') }}
                </a>
                </x-secondary-button>
                <x-primary-button class="ms-4">
                    {{ __('Unshare') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>