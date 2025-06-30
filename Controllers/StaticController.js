import {css, js} from "../Helpers/assets.js";

async function ContactView(request, reply) {
    const view = {
        title: 'Contact Page',
        css:  css(['contact.css', 'app.css', 'components.css', 'agencies.css']),
        js:  js(['contact.js', 'gotop.js','app.js']),
    };

    return reply.view('Pages/Static/Contact.hbs', view);
}
async function Faqs(request, reply) {
    const view = {
        title: 'Faqs Page',
        css:  css(['faqs.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css']),
        js:  js(['faqs.js', 'gotop.js','app.js']),
    };

    return reply.view('Pages/Static/Faq.hbs', view);
}
async function ComingSoon(request, reply) {
    const view = {
        useLayout: false,
        title: 'Contact Page',
        css:  css(['coming-soon.css', 'app.css','app.css']),
        js:  js(['coming-soon.js']),
    };

    return reply.view('Pages/Static/ComingSoon.hbs', view);
}


async function AboutUs(request, reply) {
    const view = {
        title: 'About Us',
        css:  css([ 'app.css', 'components.css', 'agencies.css','about-us.css']),
        js:  js(['gotop.js','app.js']),
    };

    return reply.view('Pages/Static/AboutUs.hbs', view);

}
export default {ContactView,Faqs,ComingSoon,AboutUs}


