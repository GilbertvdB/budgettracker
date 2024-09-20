@if(session('success'))
<div id="alertMessage" class="slide-in-right">
    <div class="bg-blue-500 px-2 py-1 rounded-xl fixed right-4 top-4 z-50 w-72 space-y-2 mt-4" role="alert">
        <div class="flex justify-between items-center">
            <div class="text-white px-4 py-3">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            <span onclick="closeAlert()">
                <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 5.652a1 1 0 010 1.414L11.414 10l2.934 2.934a1 1 0 11-1.414 1.414L10 11.414l-2.934 2.934a1 1 0 01-1.414-1.414L8.586 10 5.652 7.066a1 1 0 111.414-1.414L10 8.586l2.934-2.934a1 1 0 011.414 0z"/>
                </svg>
            </span>
        </div>
    </div>
</div>  
<style>
  html, body {
    overflow-x: hidden;
  }
  /* Slide-in-right animation */
  @keyframes slide-in-right {
    0% {
    transform: translateX(100%);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slide-out-right {
  0% {
    transform: translateX(0);
    opacity: 1;
  }
  100% {
    transform: translateX(100%);
    opacity: 0;
  }
}

/* Apply the animation */
.slide-in-right {
  animation: slide-in-right 0.5s ease-out forwards;
}

/* Apply this when closing */
.slide-out-right {
  animation: slide-out-right 0.5s ease-in forwards;
}
</style>
<script>
  // Auto-close after 4 seconds
  setTimeout(() => {
    closeAlert();
  }, 4000);
  
  function closeAlert() {
    const alert = document.getElementById('alertMessage');
    // Add the slide-out-right animation
    alert.classList.add('slide-out-right');
    
    // Remove the element from the DOM after the animation completes
    setTimeout(() => {
      alert.remove();
    }, 500); // 500ms to match the animation duration
  }
</script>
@endif
