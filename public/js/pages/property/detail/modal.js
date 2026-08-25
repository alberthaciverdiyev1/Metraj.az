document.addEventListener("DOMContentLoaded",function(){const d=document.getElementById("btn-advance"),l=document.getElementById("btn-premium"),n=document.getElementById("modal-advance"),o=document.getElementById("modal-premium");function i(e){e&&(e.style.display="flex",setTimeout(()=>{e.classList.remove("invisible")},10))}function t(e){e&&(e.classList.add("invisible"),setTimeout(()=>{e.style.display="none"},200))}d&&d.addEventListener("click",function(){i(n)}),l&&l.addEventListener("click",function(){i(o)}),document.querySelectorAll("[data-close]").forEach(e=>{e.addEventListener("click",function(){const s=this.getAttribute("data-close"),c=document.getElementById(s);t(c)})}),window.addEventListener("click",function(e){e.target===n&&t(n),e.target===o&&t(o)}),document.addEventListener("keydown",function(e){e.key==="Escape"&&(t(n),t(o))}),document.querySelectorAll('input[type="radio"]').forEach(e=>{e.addEventListener("change",function(){this.closest(".flex").parentElement.querySelectorAll("label").forEach(c=>c.classList.remove("bg-blue-50","border-blue-300")),this.checked&&this.closest("label").classList.add("bg-blue-50","border-blue-300")})})});const style=document.createElement("style");style.textContent=`
    #modal-advance,
    #modal-premium {
        display: none;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }
    
    /* Radio button se\xE7imi zaman\u0131 stil */
    .flex label.bg-blue-50 {
        background-color: #eff6ff;
        border-color: #93c5fd;
    }
`,document.head.appendChild(style);
