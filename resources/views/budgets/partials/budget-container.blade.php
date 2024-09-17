<div class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg pl-1 pr-2 pt-2 pb-1 mb-4 {{ $budget->active == 0 ? 'opacity-50' : '' }}">
    <div class="flex">
    <div>
        <form method="POST" action="{{ route('budgets.updatePinnedStatus', $budget->id) }}" id="pinned-form-{{ $budget->id }}">
        @csrf
        @method('PATCH')
            <!-- pin -->
            <button type="submit" form="pinned-form-{{ $budget->id }}" class="{{ $budget->pinned_by_user == true ? 'text-blue-500' : '' }}">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve" width="22px" height="22px" fill="currentColor" transform="matrix(-1, 0, 0, 1, 0, 0)rotate(0)">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <polygon points="13.4,20 12,18.6 3,27.6 3,29 4.4,29 "></polygon> 
                    <path fill="none" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" d="M28,11.5L20.5,4C19.5,5.1,19,6.5,19,7.9l-4.6,4.6 c-2.3-0.6-4.9,0-6.7,1.8l9.9,9.9c2-2,2.5-4.8,1.7-7.2l4.2-4.2C25.1,13.2,26.8,12.7,28,11.5z"></path>
                </g>
            </svg>
            </button>
        </form>
    </div>
        <div class="flex flex-col w-full">
                <div class="flex justify-between items-center py-1">
                <strong>{{ $budget->title }}</strong>
                    <div>
                    <form method="POST" action="{{ route('budgets.updateActiveStatus', $budget->id) }}" id="active-form-{{ $budget->id }}">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-center">
                            <label class="switch">
                                <!-- Bind checkbox to Alpine's isActive variable -->
                                <input type="checkbox" {{ $budget->active == 1 ? 'checked' : '' }} onchange="submitForm({{ $budget->id }})">
                                <span class="slider round"></span>
                            </label>
                        </div>

                        @include('budgets.partials.slider')

                        <!-- Hidden input to send the active state in the form -->
                        <input type="hidden" name="active" :value="{{ $budget->active == 1 ? '0' : '1' }}">
                    </form>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div class="flex w-full justify-between space-x-2 pr-2">
                        @php
                            $percentage = ($budget->rest_amount / $budget->amount) * 100;
                        @endphp
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 rounded-full h-4 mt-1">
                            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $percentage }}%;"></div>
                        </div>
                        <span>{{ $budget->rest_amount }}/{{ $budget->amount }}</span>
                    </div>
                    <div class="flex pl-2 space-x-4">
                        <!-- Edit Button -->
                        <!-- <a href="{{ route('budgets.edit', $budget)}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg> 
                        </a> -->
                        <!-- Delete Button -->
                        <form action="{{ route('budgets.destroy', $budget)}}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this budget?')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                        </form>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div class="flex space-x-2">
                    <a href="{{ route('budgets.shareBudget', $budget)}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                    </a>
                    @if($budget->shared_users_email)
                        <small>{{ $budget->shared_users_email }}</small>
                    @endif
                    </div>
                </div>
            </div>
    </div>
</div>