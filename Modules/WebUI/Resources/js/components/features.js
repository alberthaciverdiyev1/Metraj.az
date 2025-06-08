function featuresComponent() {
    return {
        features: [],
        loading: true,

        async fetchFeatures() {
            try {
                const res = await fetch('/features', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('API error');

                this.features = await res.json() ?? [];
            } catch (error) {
                console.error('Xüsusiyyətlər alınamadı:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
