import {homePage} from '../Controllers/HomeController.js'
import Property from '../Controllers/PropertyController.js'
import Agency from '../Controllers/AgencyController.js'
import Blog from '../Controllers/BlogController.js'
import Static from '../Controllers/StaticController.js'
import Auth from '../Controllers/AuthController.js'
import Base from '../Controllers/BaseController.js'
import i18next from "i18next";
import fastifyCookie from '@fastify/cookie'


export default async function route(fastify, options) {
    fastify.get('/', homePage)
    fastify.get('/property', Property.listView)
    fastify.get('/property/:id', Property.detailsView)
    fastify.get('/properties', Property.listApi)
    fastify.get('/add-property', Property.addView)

    
    fastify.post('/add-property', Property.add)


    fastify.get('/agencies', Agency.listView)
    fastify.get('/agency/:id', Agency.Details)
    fastify.get('/related-properties/:id', Agency.RelatedProperties)

    fastify.get('/blog', Blog.listView);
    fastify.get('/blog/:slug', Blog.Details);
    fastify.get('/contact', Static.ContactView);
    fastify.get('/faqs', Static.Faqs);
    fastify.get('/comingSoon', Static.ComingSoon);
    fastify.get('/about-us', Static.AboutUs);
    fastify.get('/login', Auth.LoginView);
    fastify.get('/register', Auth.RegisterView);
    fastify.post('/register', Auth.Register)
    fastify.post('/login', Auth.Login)
    fastify.get('/logout', Auth.Logout)
    fastify.get('/otp', Auth.OtpView);
    fastify.get('/forgot-password', Auth.ForgotPasswordView);
    fastify.get('/reset-password', Auth.ResetPasswordView);

    fastify.get('/subways', Base.Subways);
    fastify.get('/cities', Base.Cities);
    fastify.get('/features', Base.Features);
    fastify.get('/nearby-objects', Base.NearbyObjects);

    fastify.get('/property-types', Base.PropertyTypes);
    fastify.get('/repair-types', Base.RepairTypes);
    fastify.get('/room-count', Base.RoomCount);
    fastify.get('/setting', Base.Setting);
    fastify.get('/clear-cache', Base.clearAllCache);

    fastify.get('/change-lang/:lang', async (request, reply) => {
        const lang = request.params.lang || 'en'
        reply.setCookie('lang', lang, {
            path: '/',
            httpOnly: false,
            sameSite: 'lax',
        })
        return {success: true}
    })


    fastify.setNotFoundHandler(Base.NotFound);
}
