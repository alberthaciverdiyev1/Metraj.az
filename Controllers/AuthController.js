import {getData, postData, setData} from "../Helpers/CallApi.js";

async function LoginView(request, reply) {

    const view = {
        title: 'Login Page',
        css: ['registerlogin.css', 'app.css'],
        js: ['gotop.js',
            'auth/login.js'],
        useLayout: false
    };

    return reply.view('Pages/Auth/Login.hbs', view);
}

async function Login(request, reply) {
    const {email, password} = request.body

    const response = await postData('/login', {email, password})

    if (response.token) {
        request.session.set('jwt_token', response.token)
        request.session.set('user', response.user)

        return reply.code(201).send({
            status: 201,
            message: 'Login successfully!',
            route: '/',
        })
    } else {
        return reply.code(response.status || 400).send(response)
    }
}

async function RegisterView(request, reply) {

    const view = {
        title: 'Register Page',
        css: ['registerlogin.css', 'app.css'],
        js: ['gotop.js',
            'auth/register.js'],
        useLayout: false

    };
    return reply.view('Pages/Auth/Register.hbs', view);
}

async function Register(request, reply) {
    const {name, email, password, password_confirmation} = request.body;
    console.log({name, email, password, password_confirmation});
    const response = await postData('/register', {name, email, password, password_confirmation})

    if (response.token) {
        request.session.set('jwt_token', response.token)
        request.session.set('user', response.user)

        return reply.code(201).send({
            status: 201,
            message: 'Register successfully!',
            route: '/',
        })
    } else {
        return reply.code(response.status || 400).send(response)
    }
}


async function OtpView(request, reply) {

    const view = {
        title: 'Otp Page',
        css: ['registerlogin.css', 'app.css', 'otp.css'],
        js: ['otp.js'],
        useLayout: false

    };
    return reply.view('Pages/Auth/Otp.hbs', view);
}

async function ForgotPasswordView(request, reply) {

    const view = {
        title: 'Forgot Password Page',
        css: ['forgot.css', 'app.css'],
        js: [],
        useLayout: false

    };
    return reply.view('Pages/Auth/ForgotPassword.hbs', view);
}

async function ResetPasswordView(request, reply) {

    const view = {
        title: 'Reset Password Page',
        css: ['reset.css', 'app.css'],
        js: [],
        useLayout: false

    };
    return reply.view('Pages/Auth/ResetPassword.hbs', view);
}

async function Logout(req, res) {
    await req.session.destroy();
    res.redirect('/');
}


export default {
    LoginView,
    RegisterView,
    ResetPasswordView,
    ForgotPasswordView,
    OtpView,
    Register,
    Login,
    Logout
}


