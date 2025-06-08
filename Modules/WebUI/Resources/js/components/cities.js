function citiesComponent() {
    return {
        cities: [],
        loading: true,

        async fetchCities() {
            try {
                const res = await fetch('/cities', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('API error');

                this.cities = await res.json() ?? [];
            } catch (error) {
                console.error('Xüsusiyyətlər alınamadı:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
