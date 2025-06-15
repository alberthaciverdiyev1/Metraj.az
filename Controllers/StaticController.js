
async function ContactView(request, reply) {
    const view = {
        title: 'Contact Page',
        css:  ['contact.css', 'app.css', 'components.css', 'agencies.css'],
        js:  ['contact.js', 'gotop.js','app.js'],
    };

    return reply.view('Pages/Static/Contact.hbs', view);
}
async function Faqs(request, reply) {
    const view = {
        title: 'Contact Page',
        css:  ['faqs.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css'],
        js:  ['faqs.js', 'gotop.js','app.js'],
    };

    return reply.view('Pages/Static/Faq.hbs', view);
}
async function ComingSoon(request, reply) {
    const view = {
        useLayout: false,
        title: 'Contact Page',
        css:  ['coming-soon.css', 'app.css','app.css'],
        js:  ['coming-soon.js'],
    };

    return reply.view('Pages/Static/ComingSoon.hbs', view);
}


export default {ContactView,Faqs,ComingSoon}


