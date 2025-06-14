 @include('webui::partials.head')

 <div class="">
     <div class="login">
         <x-auth.left-section
             logo="https://harnishdesign.net/demo/html/oxyy/images/logo-teal.png"
             subtitle="We are glad to see you again!"
             title="Join the largest Designer community in the world." />


         <div class="login-right">
             <div class="signin-link">
                 <p>Not a member? <a href="{{ route(name:'register') }}">Sign Up</a></p>
             </div>
             <h2 class="form-title">Sign in to Oxyy</h2>

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

             <form class="signup-form" method="POST" action="{{ route('login') }}" id="login-form">
                 @csrf
                 <label>Email Address</label>
                 <input type="email" name="email" placeholder="Enter Your Email Address" required>

                 <div class="password-text">
                     <label>Password</label>
                     <a href="{{ route(name:'forgot-password') }}">Forgot Password?</a>
                 </div>

                 <input type="password" name="password" placeholder="Enter Password">

                 <p style="color: red; margin-left: 20px" id="error-message"></p>

                 <button type="submit" class="submit-btn" id="login-btn">Sign in</button>
             </form>
         </div>
     </div>
 </div>

 @extends('webui::partials.js')
