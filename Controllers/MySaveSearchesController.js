import {css,js} from "../Helpers/assets.js";
export async function MySaveSearchesView(request,reply){
    const view={
        title:"My Save Searches",
        css:css([]),
        js:js(['mysavesearches.js']),

    }
    return reply.view('Pages/Profile/MySaveSearches.hbs',view);
}