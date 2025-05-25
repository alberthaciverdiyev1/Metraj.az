
@props([
    'title' => 'Guest Reviews',
    'formTitle' => 'Add Review',
])

<div class="reviews-container">
    <h2>{{ $title }}</h2>

    @foreach ($reviews as $review)
    <div class="review-card  ">
        <img src="{{ $review['avatar'] }}" alt="User" class="avatar">
        <div class="
             ">
            <div class="user-info ">

                <div>
                    <strong>{{ $review['name'] }}</strong>
                    <p class="date">{{ $review['date'] }}</p>
                </div>
                <div class="stars">{!! $review['stars'] !!}</div>
            </div>
            <p class="review-text">
                {{ $review['text'] }}
            </p>

            @if (!empty($review['images']))
            <div class="review-images">
                @foreach ($review['images'] as $img)
                <img src="{{ $img }}" alt="">
                @endforeach
            </div>
            @endif

            <div class="feedback">
                <span><i class="fa-regular fa-thumbs-up"></i> Useful</span>
                <span><i class="fa-regular fa-thumbs-down"></i> Not helpful</span>
            </div>
        </div>
    </div>

    @endforeach

    <button class=" view-btn ">View all review <i class="fa-solid fa-arrow-right"></i></button>


    <div class="review-form">

        <h2>{{ $formTitle }}</h2>
    <p>Your email address will not be published</p>
    <form action="mailto:arzuuimammadova@gmail.com" method="POST" enctype="text/plain">
      <div class="input-row">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="Name" placeholder="Your Name*" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="Email" placeholder="Your Email*" required>
        </div>
      </div>
      <div class="form-group checkbox">
        <input class="check-box" type="checkbox" id="save" name="Save Info">
        <label class="check-text" for="save">Save your name, email for the next time review</label>
      </div>
      <div class="form-group">
        <label for="review">Review</label>
        <textarea id="review" name="Review" placeholder="Your review" rows="5" required></textarea>
      </div>
      <button type="submit" class="all-btn button-hover post-comment-btn">Post Comment <span>&#8250;</span></button>
    </form>
  </div>
</div>