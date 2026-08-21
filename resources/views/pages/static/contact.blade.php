@extends('layouts.app')

@section('content')
<div>
    @include('components.scroll-top')
    <div class="contact-header">
        <div id="map"></div>

        <section id="contact-form-section" class="">
            <div class="contact-form-container mx-auto py-3">
                <div class="contact-form-box">
                    <h2 class="contact-form-title">We Would Love to Hear From You</h2>
                    <p class="contact-form-subtitle">We'll get to know you to understand your selling goals, explain the selling process so you know what to expect.</p>

                    <form action="{{ route('inquiries.contact') }}" method="post" id="contact-form" class="contact-form">
                        @csrf
                        <div class="contact-form-grid">
                            <div class="contact-form-group">
                                <label for="name" class="contact-label">Name:</label>
                                <input type="text" id="name" name="name" class="contact-input" placeholder="Your name">
                            </div>
                            <div class="contact-form-group">
                                <label for="email" class="contact-label">Email:</label>
                                <input type="email" id="email" name="email" class="contact-input" placeholder="Email">
                            </div>
                            <div class="contact-form-group">
                                <label for="phone" class="contact-label">Phone number:</label>
                                <input type="text" id="phone" name="phone" class="contact-input" placeholder="Your phone number">
                            </div>
                            <div class="contact-form-group">
                                <label for="interest" class="contact-label">What are you interested in?</label>
                                <select id="interest" name="interest" class="contact-select">
                                    <option value="">Select</option>
                                    <option value="buy">Buying</option>
                                    <option value="sell">Selling</option>
                                </select>
                            </div>
                        </div>
                        <div class="contact-form-group contact-form-fullwidth">
                            <label for="message" class="contact-label">Your Message:</label>
                            <textarea id="message" name="message" class="contact-textarea" placeholder="Message"></textarea>
                        </div>
                        <button type="submit" class="button-hover all-btn contact-submit-btn">Contact our experts</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <main>
        <div class="re-container px-4">
            <div class="re-content">
                <h1 class="re-title">We provide the most suitable and quality real estate.</h1>
                <p class="re-description">Estimate your payment with our easy-to-use loan calculator. Then get pre-qualified to buy by a local lender.</p>

                <div class="re-contact-info">

                    <div class="re-contact-item">
                        <div class="re-icon-box">
                            <svg class="re-icon" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div class="re-contact-text">
                            <span class="re-contact-label">Office phone</span>

                            @if($setting->phone ?? false)
                            <span class="re-contact-phone">{{ $setting->phone }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="re-contact-item">
                        <div class="re-icon-box">
                            <svg class="re-icon" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="re-contact-text">
                            <span class="re-contact-label">Office email</span>
                            @if($setting->email ?? false)
                            <span class="re-contact-email">{{ $setting->email }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="re-image">
                <img src="https://themesflat.co/html/proty/images/section/section-contact-2.jpg" alt="">
            </div>
        </div>
        <div class="work-together">

            <div class="mx-auto py-3 container">
                <div class="title">
                    <h2>Let's Work Together</h2>
                    <p>Thousands of luxury home enthusiasts just like you visit our website.</p>
                </div>
                <div class="carousels">
                    <div class="carousel">
                        <div class="carousel-track">
                            <div class="partner-item"><img src="/images/spark.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Hover"></div>
                        </div>

                    </div>

                    <div class="carousel">
                        <div class="carousel-track reverse">
                            <div class="partner-item"><img src="/images/quora.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Hover"></div>
                            <div class="partner-item"><img src="/images/quora.svg" alt="Normal"></div>
                            <div class="partner-item"><img src="/images/spark.svg" alt="Hover"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

</div>

<script>
    // Əlaqə forması — JS fetch ilə göndərilir
    document.addEventListener('DOMContentLoaded', function () {
        const contactForm = document.getElementById('contact-form');
        if (!contactForm) return;

        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = contactForm.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.6';
            }

            const { ok, status, data } = await window.Metraj.post(
                contactForm.action,
                new FormData(contactForm)
            );

            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
            }

            if (ok) {
                window.Metraj.toast(data.message || 'Mesajınız göndərildi ✅');
                contactForm.reset();
            } else {
                let msg = data.message || 'Xəta baş verdi, yenidən cəhd edin';
                if (status === 422 && data.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    if (firstKey) msg = data.errors[firstKey][0];
                }
                window.Metraj.toast(msg, 'error');
            }
        });
    });
</script>
@endsection
