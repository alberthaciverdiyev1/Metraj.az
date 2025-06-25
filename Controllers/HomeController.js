import axios from 'axios';
import {css, js} from "../Helpers/assets.js";

export async function homePage(request, reply) {
    try {
        const response = await axios.get('https://api.porfolio.space/api/city');
        // console.log('API response:', response.data);

        const cities = response?.data?.data || [];

        const view = {
            title: 'Home Page',
            css: css(['home.css', 'app.css', 'components.css']),
            js: js(['home.js', 'app.js']),
            cities
        };

        return reply.view('Pages/Home.hbs', view);
    } catch (error) {
        console.error('API yükləmə zamanı xəta:', error);
        return reply.view('Pages/Home.hbs', { title: 'Home Page', cities: [] });
    }
}
