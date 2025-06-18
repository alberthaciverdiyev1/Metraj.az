function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
    if (match) return match[2]
    return null
}

window.addEventListener('DOMContentLoaded', () => {
    const lang = getCookie('lang') || 'en'
    const select = document.getElementById('lang-select')
    select.value = lang
})

function changeLang(lang) {
    console.log(lang);
    fetch(`/change-lang/${lang}`)
        .then(() => location.reload())
}