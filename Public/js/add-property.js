import {getPropertyTypes} from "./components/propertyTypes.js";
import {getFeatures} from "./components/features.js";
import {getCities} from "./components/cities.js";
import {getSubways} from "./components/subways.js";

let cityArray = [];
let subwayArray = [];
let district_id = null;
let city_id = null;

document.addEventListener("DOMContentLoaded", async () => {
    await Promise.all([propertyTypes(), featureList(), cityList(),subwayList()]);

    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const gallery = document.getElementById("gallery");
    let images = [];
    let coverIndex = null;

    fileInput.addEventListener("change", handleFiles);

    ["dragenter", "dragover"].forEach(event => {
        dropzone.addEventListener(event, e => {
            e.preventDefault();
            dropzone.classList.add("border-blue-400");
        });
    });

    ["dragleave", "drop"].forEach(event => {
        dropzone.addEventListener(event, e => {
            e.preventDefault();
            dropzone.classList.remove("border-blue-400");
        });
    });

    dropzone.addEventListener("drop", e => {
        const files = Array.from(e.dataTransfer.files);
        handleFiles({
            target: {
                files
            }
        });
    });

    function handleFiles(e) {
        const files = Array.from(e.target.files).slice(0, 10 - images.length);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = () => {
                images.push(reader.result);
                renderGallery();
            };
            reader.readAsDataURL(file);
        });
    }

    function renderGallery() {
        gallery.innerHTML = "";
        images.forEach((src, index) => {
            const div = document.createElement("div");
            div.className = "relative group";

            const img = document.createElement("img");
            img.src = src;
            img.className = "rounded-lg w-full h-auto object-cover";

            const delBtn = document.createElement("button");
            delBtn.className = "absolute top-1 right-1 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full transition";
            delBtn.innerHTML = '<i class="bi bi-trash"></i>';
            delBtn.onclick = () => {
                images.splice(index, 1);
                if (coverIndex === index) coverIndex = null;
                else if (coverIndex > index) coverIndex--;
                renderGallery();
            };

            if (coverIndex === index) {
                const coverIcon = document.createElement("div");
                coverIcon.className = "absolute bottom-1 left-1 bg-blue-500 text-white p-1 rounded-full text-xs";
                coverIcon.innerHTML = '<i class="bi bi-check-lg"></i>';
                div.appendChild(coverIcon);
            }

            div.onclick = () => {
                coverIndex = index;
                renderGallery();
            };

            div.appendChild(img);
            div.appendChild(delBtn);
            gallery.appendChild(div);
        });
    }

    async function propertyTypes() {
        const selectElement = document.getElementById('property-type');
        const propertyTypes = await getPropertyTypes();

        let h = '';
        propertyTypes.forEach(property => {
            h += `<option value="${property.key}">${property.label}</option>`;
        });
        selectElement.innerHTML = `<option disabled selected>Choose</option>` + h;
    }

    async function featureList() {
        const element = document.getElementById('features');
        const features = await getFeatures();

        let h = '';
        features.forEach(feature => {
            h += `
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="amenities[]" value="${feature.id}" class="form-checkbox text-blue-600">
                <span>${feature.name}</span>
            </label>`;
        });

        element.innerHTML = h;
    }

    async function cityList() {
        const element = document.getElementById('city');

        if (cityArray.length === 0) {
            cityArray = await getCities();
        }

        let h = '';
        cityArray.forEach(city => {
            h += `<option value="${city.id}">${city.name}</option>`;
        });

        element.innerHTML = `<option value="">Select City</option>` + h;
    }
    async function subwayList() {
        const element = document.getElementById('subway');

        if (subwayArray.length === 0) {
            subwayArray = await getSubways();
        }

        let h = '';
        subwayArray.forEach(subway => {
            h += `<option value="${subway.slug}">${subway.name}</option>`;
        });

        element.innerHTML = `<option value="">Select Subway</option>` + h;
    }


    document.getElementById("property-status").addEventListener("change", async (e) => {
        const value = e.target.value;
        console.log(value);

        if (value === 'rent') {
            console.log(value);
            document.getElementById("property-period").closest('div').classList.remove('d-none');
        } else {
            document.getElementById("property-period").closest('div').classList.add('d-none');
        }
    })

    document.getElementById('property-type').addEventListener('change', async function (e) {
        if (this.value === 'LAND') {
            document.getElementById('size').classList.add('d-none');
            document.getElementById('land-area').classList.remove('d-none');
        } else {
            document.getElementById('size').classList.remove('d-none');
            document.getElementById('land-area').classList.add('d-none');
        }
    });

    document.getElementById('city').addEventListener('change', async function (e) {
        city_id = e.target.value;

        const cityIdNumber = Number(city_id);
        const selectedCity = cityArray.find(city => city.id === cityIdNumber);

        if (selectedCity.districts.length > 0) {
            document.getElementById("district").closest('div').classList.remove('d-none');
            let h = '';
            selectedCity.districts.forEach((district) => {
                h += `<option value="${district.id}">${district.name}</option>`;
            })
            document.getElementById('district').innerHTML = `<option value="">Select District</option>` + h;
        } else {
            document.getElementById("town").closest('div').classList.add('d-none');
            document.getElementById("district").closest('div').classList.add('d-none');
        }
    });

    document.getElementById('district').addEventListener('change', async function (e) {
        district_id = e.target.value;

        const districtIdNumber = Number(district_id);
        const cityIdNumber = Number(city_id);

        const selectedCity = cityArray.find(city => city.id === cityIdNumber);
        const selectedDistrict = selectedCity.districts.find(district => district.id === districtIdNumber);

        if (selectedDistrict.towns.length > 0) {
            document.getElementById("town").closest('div').classList.remove('d-none');
            let h = '';
            selectedDistrict.towns.forEach((town) => {
                h += `<option value="${town.id}">${town.name}</option>`;
            })
            document.getElementById('town').innerHTML = `<option value="">Select Town</option>` + h;

        } else {
            document.getElementById("town").closest('div').classList.add('d-none');
        }
    });



});

