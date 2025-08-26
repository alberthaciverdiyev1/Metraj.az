import { css, js } from "../Helpers/assets.js";

export async function CompareView(request, reply) {
   
    const view = {
        title: 'Compare Products',
        css: css([]),
        js: js(['compare.js']),
      
    };

    return reply.view('Pages/Compare/Compare.hbs', view);
}
