function formatNumber(value) {
    if (!value) return "";
    return value.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function parseNumber(value) {
    if (!value) return "";
    return value.toString().replace(/\D/g, "");
}

window.formatNumber = formatNumber;
window.parseNumber = parseNumber;

document.addEventListener("input", (e) => {
    const el = e.target;
    if (!el.matches("input[data-type='number']")) return;

    const cursorPosition = el.selectionStart;
    const rawValue = parseNumber(el.value);

    el.value = formatNumber(rawValue);

    const diff = el.value.length - rawValue.length;
    el.selectionStart = el.selectionEnd = cursorPosition + diff;
});

// console.log("numberFormat.js yükləndi", window.parseNumber); 