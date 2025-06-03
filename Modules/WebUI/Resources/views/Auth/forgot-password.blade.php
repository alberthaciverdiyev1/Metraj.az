@include('webui::partials.head')

<div class="">
    <div class="login">
        <x-auth.left-section
            logo="https://harnishdesign.net/demo/html/oxyy/images/logo-teal.png"
            subtitle="Don't worry,"
            title="We are here help you to recover your password." />

        <div class="login-right">
            <div class="signin-link">
                <p>Return to<a href="/login">Sign in</a></p>
            </div>
            <h2 class="form-title">Forgot Password?</h2>




            <form class="signup-form">
                <p class="text-[var(--grey-text)]">Enter the email address or mobile number associated with your account.</p>
                <label>Email Address</label>
                <input type="email" placeholder="Enter Your Email Address">

                <button type="submit" class="submit-btn mt-0" >Continue</button>
            </form>
            <div class="divider">
                <hr>
                <span>Or with Email</span>
                <hr>
            </div>

            <div class="social-buttons">
                <button class="google-btn">
                    <i class="bi bi-google"></i>
                    <span>Sign in with Google</span>
                </button>

                <button class="social-btn facebook">
                    <i class="bi bi-facebook"></i>
                </button>
                <button class="social-btn twitter"><i class="bi bi-twitter"></i></button>
                <button class="social-btn linkedin"><i class="bi bi-linkedin"></i></button>
                <button class="social-btn apple"><i class="bi bi-apple"></i></button>
            </div>

        </div>
    </div>
</div>