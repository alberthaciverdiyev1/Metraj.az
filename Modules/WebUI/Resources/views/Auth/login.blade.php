 @include('webui::partials.head')

 <div class="container login">
     <div class="login">
         <x-auth.left-section
             logo="https://harnishdesign.net/demo/html/oxyy/images/logo-teal.png"
             subtitle="We are glad to see you again!"
             title="Join the largest Designer community in the world." />


         <div class="login-right">
             <div class="signin-link">
                 <p>Not a member? <a href="/register">Sign Up</a></p>
             </div>
             <h2 class="form-title">Sign in to Oxyy</h2>

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

                 <label>Email Address</label>
                 <input type="email" placeholder="Enter Your Email Address">
                 <div class="password-text">
                     <label>Password</label>
                     <a href="">Forgot Password?</a>
                 </div>

                 <input type="password" placeholder="Enter Password">

               

                 <button type="submit" class="submit-btn">Sign in</button>
             </form>
         </div>
     </div>
 </div>