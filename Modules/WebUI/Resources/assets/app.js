const jsModules = import.meta.glob('./js/**/*.js');
const cssModules = import.meta.glob('./css/**/**/*.css');

Object.keys(jsModules).forEach((modulePath) => {
    jsModules[modulePath]().then((module) => {
        console.log(`JS Module loaded: ${modulePath}`);
    });
});

Object.keys(cssModules).forEach((modulePath) => {
    cssModules[modulePath]().then(() => {
        console.log(`CSS Module loaded: ${modulePath}`);
    });
});

