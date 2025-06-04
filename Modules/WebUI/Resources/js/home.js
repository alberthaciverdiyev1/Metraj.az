function propertyTypeComponent() {
    return {
        propertyTypes: [],
        async fetchPropertyTypes() {
            const res = await fetch('/property-types');
            this.propertyTypes = await res.json();
        }
    }
}
