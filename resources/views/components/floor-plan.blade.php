@props(['plans'])

<div class="floor-plan">
    <div class="accordion-container">
        <h2>Floor Plans</h2>

        @foreach($plans as $plan)
            <div class="accordion-item">
                <div class="accordion-header">
                    <div class="accordion-title">
                        <i class="fa-solid fa-chevron-down"></i>
                        {{ $plan['floor'] }}
                    </div>
                    <div class="accordion-icons">
                        <div><i class="fa-solid fa-bed"></i> {{ $plan['bedrooms'] }} Bedroom</div>
                        <div><i class="fa-solid fa-bath"></i> {{ $plan['bathrooms'] }} Bathroom</div>
                    </div>
                </div>
                <div class="accordion-body">
                    <img src="{{ $plan['image'] }}" alt="{{ $plan['floor'] }} Plan">
                </div>
            </div>
        @endforeach
    </div>
</div>
