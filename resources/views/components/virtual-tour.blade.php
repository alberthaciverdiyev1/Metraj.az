<div class="virtual-tour">
  <h3>360 Virtual Tour</h3>
  <div class="box">
    <img src="{{ $property['virtual_tour']['image'] }}" alt="Virtual Tour Image">

    <svg class="rotate-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" >
      <circle cx="32" cy="32" r="30" stroke="white" stroke-width="3" fill="rgba(255,255,255,0.1)" />
      
      <ellipse cx="32" cy="32" rx="20" ry="8" stroke="white" stroke-width="2" fill="none" transform="rotate(45 32 32)" />
      <ellipse cx="32" cy="32" rx="20" ry="8" stroke="white" stroke-width="2" fill="none" transform="rotate(-45 32 32)" />
      
      <text x="32" y="38" text-anchor="middle" font-size="20" fill="white" font-family="Arial, sans-serif" font-weight="bold">360</text>
    </svg>
  </div>
</div>
