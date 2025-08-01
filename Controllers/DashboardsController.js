
import {css,js} from '../Helpers/assets.js';
export async function DashboardView(request,reply){
    const view = {
        title:'Dashboard',
        css:css([]),
        js:js([]),
    };
    
    return reply.view('Pages/Profile/Dashboards.hbs',view);
}