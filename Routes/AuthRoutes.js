import { guestOnly } from "../Middlewares/AuthMiddleware.js";
import Auth from "../Controllers/AuthController.js";

export default async function authRoutes(fastify, options) {
    fastify.get('/login', { preHandler: guestOnly }, Auth.LoginView);
    fastify.get('/register', { preHandler: guestOnly }, Auth.RegisterView);
    fastify.post('/register', { preHandler: guestOnly }, Auth.Register);
    fastify.post('/login', { preHandler: guestOnly }, Auth.Login);
    fastify.get('/otp', { preHandler: guestOnly }, Auth.OtpView);
    fastify.get('/logout', Auth.Logout);
    fastify.get('/forgot-password', Auth.ForgotPasswordView);
    fastify.get('/reset-password', Auth.ResetPasswordView);
}
