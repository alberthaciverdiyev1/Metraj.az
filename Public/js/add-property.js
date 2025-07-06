import {getPropertyTypes} from "./components/propertyTypes.js";
import {getFeatures} from "./components/features.js";
import {getCities} from "./components/cities.js";
import {getSubways} from "./components/subways.js";
import {getNearbyObjects} from "./components/nearbyObjects.js";

let cityArray = [];
let subwayArray = [];
let district_id = null;
let city_id = null;

document.addEventListener("DOMContentLoaded", async () => {
    await Promise.all([propertyTypes(), featureList(), cityList(), subwayList(), nearbyObjectsList()]);
    const addBtn       = document.getElementById('add-property-btn'); 
    const termsCheckbox = document.getElementById('terms');
  
    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const gallery = document.getElementById("gallery");
    let images = [];
    let coverIndex = null;

    fileInput.addEventListener("change", handleFiles);

     addBtn.disabled = !termsCheckbox.checked;    
  addBtn.classList.toggle('opacity-50', !termsCheckbox.checked);
  addBtn.classList.toggle('cursor-not-allowed', !termsCheckbox.checked);
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

    // function handleFiles(e) {
    //     const files = Array.from(e.target.files).slice(0, 10 - images.length);
    //     files.forEach(file => {
    //         const reader = new FileReader();
    //         reader.onload = () => {
    //             images.push(reader.result);
    //             renderGallery();
    //         };
    //         reader.readAsDataURL(file);
    //     });
    // }
    function handleFiles(e) {
        const files = Array.from(e.target.files).slice(0, 10 - images.length);
        files.forEach(file => {
            images.push(file);
            const reader = new FileReader();
            reader.onload = () => {
                renderGallery();
            };
            reader.readAsDataURL(file);
        });
    }


    // function renderGallery() {
    //     gallery.innerHTML = "";
    //     images.forEach((src, index) => {
    //         const div = document.createElement("div");
    //         div.className = "relative group";
    //
    //         const img = document.createElement("img");
    //         img.src = src;
    //         img.className = "rounded-lg w-full h-auto object-cover";
    //
    //         const delBtn = document.createElement("button");
    //         delBtn.className = "absolute top-1 right-1 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full transition";
    //         delBtn.innerHTML = '<i class="bi bi-trash"></i>';
    //         delBtn.onclick = () => {
    //             images.splice(index, 1);
    //             if (coverIndex === index) coverIndex = null;
    //             else if (coverIndex > index) coverIndex--;
    //             renderGallery();
    //         };
    //
    //         if (coverIndex === index) {
    //             const coverIcon = document.createElement("div");
    //             coverIcon.className = "absolute bottom-1 left-1 bg-blue-500 text-white p-1 rounded-full text-xs";
    //             coverIcon.innerHTML = '<i class="bi bi-check-lg"></i>';
    //             div.appendChild(coverIcon);
    //         }
    //
    //         div.onclick = () => {
    //             coverIndex = index;
    //             renderGallery();
    //         };
    //
    //         div.appendChild(img);
    //         div.appendChild(delBtn);
    //         gallery.appendChild(div);
    //     });
    // }

    function renderGallery() {
        gallery.innerHTML = "";
        images.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = () => {
                const div = document.createElement("div");
                div.className = "relative group";

                const img = document.createElement("img");
                img.src = reader.result;
                img.className = "rounded-lg w-full h-auto object-cover";

                const delBtn = document.createElement("button");
                delBtn.className = "absolute top-1 right-1 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full transition";
                delBtn.innerHTML = '<i class="bi bi-trash"></i>';
                delBtn.onclick = () => {
                    images.splice(index, 1);
                    renderGallery();
                };

                div.appendChild(img);
                div.appendChild(delBtn);
                gallery.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    async function propertyTypes() {
        const selectElement = document.getElementById('building-type');
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
                <input type="checkbox" name="features[]" value="${feature.id}" class="form-checkbox text-blue-600">
                <span>${feature.name}</span>
            </label>`;
        });

        element.innerHTML = h;
    }

    async function nearbyObjectsList() {
        const element = document.getElementById('nearby-objects');
        const objects = await getNearbyObjects();

        let h = '';
        objects.forEach(object => {
            h += `
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="nearby-objects[]" value="${object.id}" class="form-checkbox text-blue-600">
                <span>${object.name}</span>
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
            h += `<option value="${subway.id}">${subway.name}</option>`;
        });

        element.innerHTML = `<option value="">Select Subway</option>` + h;
    }

    document.getElementById("add-type").addEventListener("change", async (e) => {
        const value = e.target.value;
        console.log(value);

        if (value === 'rent') {
            console.log(value);
            document.getElementById("property-period").closest('div').classList.remove('d-none');
        } else {
            document.getElementById("property-period").closest('div').classList.add('d-none');
        }
    })

    document.getElementById('building-type').addEventListener('change', async function (e) {
        if (this.value === 'LAND') {
            document.getElementById('area').classList.add('d-none');
            document.getElementById('field-area').classList.remove('d-none');
        } else {
            document.getElementById('area').classList.remove('d-none');
            document.getElementById('field-area').classList.add('d-none');
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

    termsCheckbox.addEventListener('change', e => {
        const accepted = e.target.checked;
        addBtn.disabled = !accepted;
        addBtn.classList.toggle('opacity-50', !accepted);
        addBtn.classList.toggle('cursor-not-allowed', !accepted);
    });
    addBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        if (!termsCheckbox.checked) {
            alert('Elanı yerləşdirməzdən əvvəl istifadəçi razılaşmasını qəbul etməlisiniz.');
            return;
          }

        const fileInput = document.getElementById('fileInput');
        const videoInput = document.getElementById('video');
        const documentInput = document.getElementById('document');

        const formData = new FormData();
        console.log(document.getElementById('building-type').value);

        formData.append('buildingType', document.getElementById('building-type').value);
        formData.append('addType', document.getElementById('add-type').value);
        formData.append('propertyCondition', document.getElementById('repair-type').value);
        formData.append('price', document.getElementById('price').value);
        formData.append('propertyPeriod', document.getElementById('property-period').value);
        formData.append('roomCount', document.getElementById('room-count').value);
        formData.append('floorCount', document.getElementById('floor-count').value);
        formData.append('locatedFloor', document.getElementById('located-floor').value);
        formData.append('isCredit', document.getElementById('is-credit').checked ? 1 : 0);
        formData.append('description', document.getElementById('description').value);
        formData.append('noteToAdmin', document.getElementById('note-to-admin').value);
        formData.append('cityId', document.getElementById('city').value);
        formData.append('area', document.querySelector('#area input')?.value || null);
        formData.append('fieldArea', document.querySelector('#field-area input')?.value || null);
        formData.append('districtId', document.getElementById('district').value);
        formData.append('townId', document.getElementById('town').value);
        formData.append('subwayId', document.getElementById('subway').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('map', document.getElementById('map').value);
        formData.append('advertiser', document.getElementById('advertiser')?.value || '');
        formData.append('advertiserName', document.getElementById('advertiser-name')?.value || '');
        formData.append('phone1', document.getElementById('phone_1').value);
        formData.append('phone2', document.getElementById('phone_2').value);
        formData.append('phone3', document.getElementById('phone_3').value);
        formData.append('phone4', document.getElementById('phone_4').value);
        formData.append('email', document.getElementById('email').value);

        Array.from(document.querySelectorAll('#features input[name="features[]"]:checked')).forEach((cb, i) => {
            formData.append(`features[${i}]`, cb.value);
        });

        Array.from(document.querySelectorAll('#nearby-objects input[name="nearby-objects[]"]:checked')).forEach((cb, i) => {
            formData.append(`nearbyObjects[${i}]`, cb.value);
        });

        images.forEach((file, index) => {
            formData.append(`media[${index}][type]`, 'image');
            formData.append(`media[${index}][file]`, file);
        });

        if (videoInput.files.length > 0) {
            const index = images.length;
            formData.append(`media[${index}][type]`, 'video');
            formData.append(`media[${index}][file]`, videoInput.files[0]);
        }

        if (documentInput.files.length > 0) {
            const index = images.length + (videoInput.files.length ? 1 : 0);
            formData.append(`media[${index}][type]`, 'document');
            formData.append(`media[${index}][file]`, documentInput.files[0]);
        }
console.log({formData});
        try {
            const response = await axios.post('/add-property', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            console.log(' Uğurla göndərildi:', response.data);
        } catch (error) {
            console.error('Error:', error.response?.data || error.message);
        }
    });

    const nearbyObjectsContainer = document.getElementById('nearby-objects-container');
    const toggleButton = document.getElementById('toggle-nearby-objects');
    const fadeOverlay = nearbyObjectsContainer.querySelector('.fade-overlay');

    nearbyObjectsContainer.classList.add('collapsed');

    toggleButton.addEventListener('click', () => {
        if (nearbyObjectsContainer.classList.contains('collapsed')) {
            nearbyObjectsContainer.classList.remove('collapsed');
            nearbyObjectsContainer.classList.add('expanded');
            fadeOverlay.classList.add('hidden'); 
            toggleButton.textContent = 'Daha az göstər';
        } else {
            nearbyObjectsContainer.classList.remove('expanded');
            nearbyObjectsContainer.classList.add('collapsed');
            fadeOverlay.classList.remove('hidden'); 
            toggleButton.textContent = 'Daha çox göstər';
        }
    });
});


