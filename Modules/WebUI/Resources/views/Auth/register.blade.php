@include('webui::partials.head')

<div class="">
    <div class="register">
        <x-auth.left-section
            logo="https://harnishdesign.net/demo/html/oxyy/images/logo-teal.png"
            subtitle="Looks like you're new here!"
            title="Join the largest Designer community in the world."/>

        <div class="register-right">
            <div class="signin-link">
                <p>Already a member? <a href="{{ route(name:'login') }}">Sign In</a></p>
            </div>
            <h2 class="form-title">Sign up to Metraj.az</h2>

            <div class="social-buttons">
                <a href="#" class="google-btn">
                    <img src="webui/images/googlelogo.png" alt="">
                    <span>Sign in with Google</span>
                </a>
            </div>

            <div class="divider">
                <hr>
                <span>Or with Email</span>
                <hr>
            </div>

            <form class="signup-form" method="POST" action="{{ route('register') }}" id="register-form">
                @csrf
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter Your Name" required>

                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter Your Email Address" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Enter Password" required>

                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Re-type Password" required>

                <div class="checkbox">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.</label>
                </div>

                <p style="color: red; margin-left: 20px" id="error-message"></p>


                <button type="submit" class="submit-btn" id="register-btn">Create Account</button>
            </form>
        </div>
    </div>
</div>


@extends('webui::partials.js')
