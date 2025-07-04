    // document.addEventListener("DOMContentLoaded", () => {
    //     const btn = document.getElementById('dropdownButton');
    //     const menu = document.getElementById('dropdownMenu');

    //     btn.addEventListener('click', () => {
    //         menu.classList.toggle('hidden');
    //     });

    //     window.addEventListener('click', (e) => {
    //         if (!btn.contains(e.target) && !menu.contains(e.target)) {
    //             menu.classList.add('hidden');
    //         }
    //     });

    //     const dropdownLinks = menu.querySelectorAll('a');

    //     dropdownLinks.forEach(link => {
    //         link.addEventListener('click', () => {
    //             dropdownLinks.forEach(l => {
    //                 l.classList.remove('bg-orange-400', 'text-white');
    //                 l.classList.add('text-gray-700');
    //             });
    //             link.classList.add('bg-orange-400', 'text-white');
    //             link.classList.remove('text-gray-700');
    //         });
    //     });

    //     burgerBtn=document.getElementById('burgerBtn');


    // });


    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        const burgerBtn = document.getElementById('burgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const links=document.querySelectorAll("nav a")
        const linkaddproperty=document.querySelector(".add-property-link")
        const currentPath=window.location.pathname;
        console.log(currentPath);
    
       
        links.forEach(link=>{
            if(link.getAttribute("href")===currentPath){
                link.classList.add("text-orange-400");
                link.classList.add("font-bold");

            }
            else{
                link.classList.add("text-gray-700")

            }
        })
        if(linkaddproperty.getAttribute("href")===currentPath){
            linkaddproperty.classList.add("text-orange-400");
        }
        
    
        btn?.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    
        window.addEventListener('click', (e) => {
            if (!btn?.contains(e.target) && !menu?.contains(e.target)) {
                menu?.classList.add('hidden');
            }
        });
    
        const dropdownLinks = menu?.querySelectorAll('a') || [];
    
        dropdownLinks.forEach(link => {
            link.addEventListener('click', () => {
                dropdownLinks.forEach(l => {
                    l.classList.remove('bg-orange-400', 'text-white');
                    l.classList.add('text-gray-700');
                });
                link.classList.add('bg-orange-400', 'text-white');
                link.classList.remove('text-gray-700');
            });
        });
    
        burgerBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    });
    
