<style>
.switch {
    position: relative;
    display: inline-block;
    width: 30px;  /* Reduced width */
    height: 12px;  /* Reduced height */
}

.switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 20px; /* Rounding the slider for smaller size */
}

.slider:before {
    position: absolute;
    content: "";
    height: 20px;   /* Reduced knob height */
    width: 20px;    /* Reduced knob width */
    left: 0px;
    bottom: -3px;
    background-color: #FAFAFA; /* Default knob color (unchecked state) */
    transition: .4s;
    border-radius: 50%; /* Round knob */
}

input:checked + .slider {
    background-color: #3B82F6; /* Background when checked */
}

input:checked + .slider:before {
    transform: translateX(10px); /* Adjust this based on the new width */
    background-color: #3B82F6; /* Change the knob color to blue when checked */
}

input:focus + .slider {
    box-shadow: 0 0 1px #3B82F6;
}

</style>
<!-- <div class="div"> 
    <label class="switch">
    <input type="checkbox" checked>
    <span class="slider round"></span>
    </label>
</div> -->