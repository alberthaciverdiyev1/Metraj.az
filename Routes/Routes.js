import {homePage} from '../Controllers/HomeController.js'
import Property from '../Controllers/PropertyController.js'
import Agency from '../Controllers/AgencyController.js'
import Blog from '../Controllers/BlogController.js'
import Static from '../Controllers/StaticController.js'
import Auth from '../Controllers/AuthController.js'
import Base from '../Controllers/BaseController.js'

export default async function route(fastify, options) {
    fastify.get('/', homePage)
    fastify.get('/property', Property.listView)
    fastify.get('/property/:id', Property.detailsView)
    fastify.get('/properties', Property.listApi)
    fastify.get('/add-property', Property.addView)

    fastify.get('/agencies', Agency.listView)
    fastify.get('/agency/:id', Agency.Details)

    fastify.get('/blog', Blog.listView);
    fastify.get('/blog/:slug', Blog.Details);
    fastify.get('/contact', Static.ContactView);
    fastify.get('/faqs', Static.Faqs);
    fastify.get('/comingSoon', Static.ComingSoon);

    fastify.get('/login', Auth.LoginView);
    fastify.get('/register', Auth.RegisterView);
    fastify.get('/otp', Auth.OtpView);
    fastify.get('/forgot-password', Auth.ForgotPasswordView);
    fastify.get('/reset-password', Auth.ResetPasswordView);

    fastify.get('/subways', Base.Subways);
    fastify.get('/cities', Base.Cities);
    fastify.get('/features', Base.Features);

    fastify.get('/property-types', Base.PropertyTypes);
    fastify.get('/repair-types', Base.RepairTypes);
    fastify.get('/room-count', Base.RoomCount);


}
