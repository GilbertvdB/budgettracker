<!-- @foreach (['success' => 'green', 'message' => 'blue', 'error' => 'red'] as $msgType => $color)
    @if(session($msgType))
        <div class="max-w-6xl mx-auto bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 dark:text-grey-300 px-4 py-1 rounded-lg relative mt-4 alert" role="alert">
            <strong class="font-bold">{{ ucfirst($msgType) }}!</strong>
            <span class="block sm:inline">{{ session($msgType) }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-{{ $color }}-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title>
                <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/></svg>
            </span>
        </div>
    @endif
@endforeach -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 3000); // 3 seconds
    });
</script>