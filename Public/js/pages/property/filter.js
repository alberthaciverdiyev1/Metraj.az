export function filterModal() {
    return {
        open: false,
        collapsed: false,

        repairs: [
            { label: 'Hamısı', value: '' },
            { label: 'Təmirli', value: 'renovated' },
            { label: 'Təmirsiz', value: 'unrenovated' }
        ],
        dropdownData: {
            districts: ['Binəqədi', 'Nərimanov', 'Nəsimi', 'Xətai', 'Yasamal'],
            metros: ['28 May', 'Gənclik', 'Elmlər', 'İnşaatçılar'],
            landmarks: ['Gənclik Mall', 'Targovu', 'Flame Towers', 'Təhsil Nazirliyi']
        },
        locationLabels: {
            districts: 'Rayon və qəsəbələr',
            metros: 'Metrostansiyalar',
            landmarks: 'Nişangahlar'
        },
        tags: [],

        form: {
            repair: '',
            areaMin: '',
            areaMax: '',
            floorMin: '',
            floorMax: '',
            priceMin: '',
            priceMax: '',
            postId: '',
            extraction: false,
            mortgage: false
        },

        addTag(scope, value) {
            if (this.tags.find(t => t.scope === scope && t.value === value)) return;
            this.tags.push({ scope, value });
        },
        removeTag(idx) {
            this.tags.splice(idx, 1);
        },

        reset() {
            this.form = {
                repair: '', areaMin: '', areaMax: '', floorMin: '', floorMax: '',
                priceMin: '', priceMax: '', postId: '', extraction: false, mortgage: false
            };
            this.tags = [];
            this.collapsed = false;
        },

        submit() {
            const payload = { ...this.form, selected: this.tags };
            console.log('FILTER PAYLOAD:', payload);
            alert('filter payloada yazildi');
            this.open = false;
        }
    }
}
