import {getData} from "../Helpers/CallApi.js";

async function LoginView(request, reply) {

    const view = {
        title: 'Login Page',
        css: ['registerlogin.css', 'app.css'],
        js: ['gotop.js',
            'auth/login.js'],
        useLayout:false
    };

    return reply.view('Pages/Auth/Login.hbs', view);
}

async function RegisterView(request, reply) {

    const view = {
        title: 'Register Page',
        css: ['registerlogin.css', 'app.css'],
        js: ['gotop.js',
            'auth/login.js'],
        useLayout:false

    };
    return reply.view('Pages/Auth/Register.hbs', view);
}

async function OtpView(request, reply) {

    const view = {
        title: 'Otp Page',
        css: ['registerlogin.css', 'app.css', 'otp.css'],
        js: ['otp.js'],
        useLayout:false

    };
    return reply.view('Pages/Auth/Otp.hbs', view);
}
async function ForgotPasswordView(request, reply) {

    const view = {
        title: 'Forgot Password Page',
        css: ['forgot.css', 'app.css'],
        js: [],
        useLayout:false

    };
    return reply.view('Pages/Auth/ForgotPassword.hbs', view);
}

async function ResetPasswordView(request, reply) {

    const view = {
        title: 'Reset Password Page',
        css: ['reset.css', 'app.css'],
        js: [],
        useLayout:false

    };
    return reply.view('Pages/Auth/ResetPassword.hbs', view);
}


export default {LoginView, RegisterView,ResetPasswordView,ForgotPasswordView,OtpView}


