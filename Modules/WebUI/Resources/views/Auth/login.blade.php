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

             <form class="signup-form">

                 <label>Email Address</label>
                 <input type="email" placeholder="Enter Your Email Address">
                 <div class="password-text">
                     <label>Password</label>
                     <a href="{{ route(name:'forgot-password') }}">Forgot Password?</a>
                 </div>

                 <input type="password" placeholder="Enter Password">

               

                 <button type="submit" class="submit-btn">Sign in</button>
             </form>
         </div>
     </div>
 </div>