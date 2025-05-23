@extends('webui::layout')



@section('content')
<div>
    <x-settings-icon />
    <x-scroll-to-top />
    <section id="navigation" class="py-3">
        <div class="container mx-auto px-4 flex items-center gap-2">
            <a href="{{ route('home') }}" class="text-[color:var(--primary)] font-bold flex items-center hover:text-black">
                Home
            </a>
            <span class="text-gray-400">›</span>
            <a href="{{ route('listing') }}" class="text-gray-600">
                Property Listing
            </a>
        </div>
    </section>


</div>
<main>
    <section>
        <div class="container mx-auto px-4">
            <div class="side">
                <div class="left-side">
                    <h2>Frequently Asked Questions</h2>

                    <div class="accordion-top">
                        <h3>Overview
                        </h3>
                        <div class="accordion">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <span>Why Should I Use Your Services?</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="accordion-content">
                                    Once your account is set up and you've familiarized yourself with the platform, you are ready to start using our services. Whether it's accessing specific features, making transactions, or utilizing our tools, you'll find everything you need at your fingertips.
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <span>Why Should I Use Your Services?</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="accordion-content">
                                    Our platform offers a unique combination of tools, support, and ease of use that helps you get things done efficiently and securely.
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <span>How Secure Are Your Services?</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="accordion-content">
                                    We use state-of-the-art encryption and security practices to keep your data and transactions safe.
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <span>Is There Customer Support Available?</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="accordion-content">
                                    Yes, our support team is available 24/7 to assist with any questions or issues you may have.
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <span>How Can I Update My Account Information?</span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="accordion-content">
                                    You can update your account information anytime from your profile settings page.
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="side-right">
                <div class="sticky">
                    <div class="contact-card">
                        <h3>Contact Sellers</h3>
                        <div class="flex avatar-info">
                            <img src="https://themesflat.co/html/proty/images/avatar/seller.jpg" alt="Shara Conner" class="avatar">
                            <div class="avatar-info-text">
                                <h4>Shara Conner</h4>
                                <p><a href="tel:1-333-345-6868" class="phone"><i class="bi bi-telephone"></i> 1-333-345-6868</a></p>
                                <p><a href="mailto:themesflat@gmail.com" class="email">
                                        <i class="bi bi-envelope"></i> themesflat@gmail.com</a></p>
                            </div>

                        </div>



                        <form id="contact-form">
                            <input type="text" id="name" name="name" placeholder="Full Name" required>
                            <textarea id="message" name="message" placeholder="How can an agent help" required></textarea>
                            <button
                                class="send-message-btn all-btn button-hover"
                                type="submit">Send message</button>
                        </form>
                    </div>
                    <x-connect-agent />

                </div>





            </div>
            </div>
            
        </div>

    </section>

</main>

</div>
@endsection