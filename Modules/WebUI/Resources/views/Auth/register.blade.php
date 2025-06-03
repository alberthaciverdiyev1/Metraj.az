 @include('webui::partials.head')

 <div class="">
     <div class="register">
         <x-auth.left-section
             logo="https://harnishdesign.net/demo/html/oxyy/images/logo-teal.png"
             subtitle="Looks like you're new here!

"
             title="Join the largest Designer community in the world." />

         <div class="register-right">
             <div class="signin-link">
                 <p>Already a member? <a href="{{ route(name:'login') }}">Sign In</a></p>
             </div>
             <h2 class="form-title">Sign up to Oxyy</h2>

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

             <div class="divider">
                 <hr>
                 <span>Or with Email</span>
                 <hr>
             </div>

             <form class="signup-form">
                 <label>Full Name</label>
                 <input type="text" placeholder="Enter Your Name">

                 <label>Email Address</label>
                 <input type="email" placeholder="Enter Your Email Address">

                 <label>Password</label>
                 <input type="password" placeholder="Enter Password">

                 <div class="checkbox">
                     <input type="checkbox">
                     <p>I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.</p>
                 </div>

                 <button type="submit" class="submit-btn">Create Account</button>
             </form>
         </div>
     </div>
 </div>
