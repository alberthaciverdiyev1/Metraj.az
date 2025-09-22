import { getPropertyTypes } from "./components/propertyTypes.js";
import { getFeatures } from "./components/features.js";
import { getCities } from "./components/cities.js";
import { getSubways } from "./components/subways.js";
import { getNearbyObjects } from "./components/nearbyObjects.js";

let cityArray = [];
let subwayArray = [];
let district_id = null;
let city_id = null;

document.addEventListener("DOMContentLoaded", async () => {
  await Promise.all([
    propertyTypes(),
    featureList(),
    cityList(),
    subwayList(),
    nearbyObjectsList(),
  ]);
  const addBtn = document.getElementById("add-property-btn");
  const termsCheckbox = document.getElementById("terms");

  const dropzone = document.getElementById("dropzone");
  const fileInput = document.getElementById("fileInput");
  const gallery = document.getElementById("gallery");
  let images = [];
  let coverIndex = null;

  fileInput.addEventListener("change", handleFiles);

  addBtn.disabled = !termsCheckbox.checked;
  addBtn.classList.toggle("opacity-50", !termsCheckbox.checked);
  addBtn.classList.toggle("cursor-not-allowed", !termsCheckbox.checked);
  ["dragenter", "dragover"].forEach((event) => {
    dropzone.addEventListener(event, (e) => {
      e.preventDefault();
      dropzone.classList.add("border-blue-400");
    });
  });

  ["dragleave", "drop"].forEach((event) => {
    dropzone.addEventListener(event, (e) => {
      e.preventDefault();
      dropzone.classList.remove("border-blue-400");
    });
  });

  dropzone.addEventListener("drop", (e) => {
    const files = Array.from(e.dataTransfer.files);
    handleFiles({
      target: {
        files,
      },
    });
  });

  function handleFiles(e) {
    const files = Array.from(e.target.files).slice(0, 10 - images.length);
    files.forEach((file) => {
      images.push(file);
      const reader = new FileReader();
      reader.onload = () => {
        renderGallery();
        saveImagesToLocalStorage();
      };
      reader.readAsDataURL(file);
    });
  }

function renderGallery() {
  gallery.innerHTML = "";
  images.forEach((file, index) => {
    const div = document.createElement("div");
    div.className = "relative group";

    const img = document.createElement("img");
    img.className = "rounded-lg w-full h-auto object-cover";

    if (typeof file === "string") {
      img.src = file;
    } else {
      const reader = new FileReader();
      reader.onload = () => img.src = reader.result;
      reader.readAsDataURL(file);
    }

    const delBtn = document.createElement("button");
    delBtn.className =
      "absolute top-1 right-1 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full transition";
    delBtn.innerHTML = '<i class="bi bi-trash"></i>';
    delBtn.onclick = () => {
      images.splice(index, 1);
      renderGallery();
      saveImagesToLocalStorage();
    };

    div.appendChild(img);
    div.appendChild(delBtn);
    gallery.appendChild(div);
  });
}


  function saveImagesToLocalStorage() {
    const imagePromises = images.map(file => {
      return new Promise((resolve) => {
        const reader = new FileReader();
        reader.onload = () => resolve({
          name: file.name,
          type: file.type,
          size: file.size,
          data: reader.result
        });
        reader.readAsDataURL(file);
      });
    });

    Promise.all(imagePromises).then(imageData => {
      let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
      data.images = imageData;
      localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
      console.log('Images saved to localStorage:', imageData.length);
    });
  }

async function loadImagesFromLocalStorage() {
  const data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
  if (!data.images) return;

  const loaded = await Promise.all(
    data.images.map(img =>
      fetch(img.data)
        .then(res => res.blob())
        .then(blob => new File([blob], img.name, { type: img.type }))
    )
  );

  images = loaded;
  renderGallery();
}


  async function propertyTypes() {
    const selectElement = document.getElementById("building-type");
    const propertyTypes = await getPropertyTypes();

    let h = "";
    propertyTypes.forEach((property) => {
      h += `<option value="${property.key}">${property.label}</option>`;
    });
    selectElement.innerHTML = `<option disabled selected>Building Type</option>` + h;
  }

  async function featureList() {
    const element = document.getElementById("features");
    const features = await getFeatures();

    let h = "";
    features.forEach((feature) => {
      h += `
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="features[]" value="${feature.id}" class="form-checkbox text-blue-600">
                <span>${feature.name}</span>
            </label>`;
    });

    element.innerHTML = h;
  }

  async function nearbyObjectsList() {
    const element = document.getElementById("nearby-objects");
    const objects = await getNearbyObjects();

    let h = "";
    objects.forEach((object) => {
      h += `
            <label class="flex items-center space-x-2 mb-2">
                <input type="checkbox" name="nearby-objects[]" value="${object.id}" class="form-checkbox text-blue-600">
                <span>${object.name}</span>
            </label>`;
    });

    element.innerHTML = h;
  }

  async function cityList() {
    const element = document.getElementById("city");

    if (cityArray.length === 0) {
      cityArray = await getCities();
    }

    let h = "";
    cityArray.forEach((city) => {
      h += `<option value="${city.id}">${city.name}</option>`;
    });

    element.innerHTML = `<option disabled selected value="">City</option>` + h;
  }

  async function subwayList() {
    const element = document.getElementById("subway");

    if (subwayArray.length === 0) {
      subwayArray = await getSubways();
    }

    let h = "";
    subwayArray.forEach((subway) => {
      h += `<option value="${subway.id}">${subway.name}</option>`;
    });

    element.innerHTML = `<option disabled selected value="">Subway</option>` + h;
  }

  document.getElementById("add-type").addEventListener("change", async (e) => {
    const value = e.target.value;
    console.log(value);

    if (value === "rent") {
      console.log(value);
      document
        .getElementById("property-period")
        .closest("div")
        .classList.remove("d-none");
    } else {
      document
        .getElementById("property-period")
        .closest("div")
        .classList.add("d-none");
    }
  });

  document
    .getElementById("building-type")
    .addEventListener("change", async function (e) {
      if (this.value === "LAND") {
        document.getElementById("area").classList.add("d-none");
        document.getElementById("field-area").classList.remove("d-none");
      } else {
        document.getElementById("area").classList.remove("d-none");
        document.getElementById("field-area").classList.add("d-none");
      }
    });

  document
    .getElementById("city")
    .addEventListener("change", async function (e) {
      city_id = e.target.value;

      const cityIdNumber = Number(city_id);
      const selectedCity = cityArray.find((city) => city.id === cityIdNumber);

      if (selectedCity.districts.length > 0) {
        document
          .getElementById("district")
          .closest("div")
          .classList.remove("d-none");
        let h = "";
        selectedCity.districts.forEach((district) => {
          h += `<option value="${district.id}">${district.name}</option>`;
        });
        document.getElementById("district").innerHTML =
          `<option disabled selected value="">District</option>` + h;
      } else {
        document.getElementById("town").closest("div").classList.add("d-none");
        document
          .getElementById("district")
          .closest("div")
          .classList.add("d-none");
      }
    });

  document
    .getElementById("district")
    .addEventListener("change", async function (e) {
      district_id = e.target.value;

      const districtIdNumber = Number(district_id);
      const cityIdNumber = Number(city_id);

      const selectedCity = cityArray.find((city) => city.id === cityIdNumber);
      const selectedDistrict = selectedCity.districts.find(
        (district) => district.id === districtIdNumber
      );

      if (selectedDistrict.towns.length > 0) {
        document
          .getElementById("town")
          .closest("div")
          .classList.remove("d-none");
        let h = "";
        selectedDistrict.towns.forEach((town) => {
          h += `<option value="${town.id}">${town.name}</option>`;
        });
        document.getElementById("town").innerHTML =
          `<option value="">Select Town</option>` + h;
      } else {
        document.getElementById("town").closest("div").classList.add("d-none");
      }
    });

  termsCheckbox.addEventListener("change", (e) => {
    const accepted = e.target.checked;
    addBtn.disabled = !accepted;
    addBtn.classList.toggle("opacity-50", !accepted);
    addBtn.classList.toggle("cursor-not-allowed", !accepted);
  });
  
  addBtn.addEventListener("click", async function (e) {
    e.preventDefault();
    if (!termsCheckbox.checked) {
      alert(
        "Elanı yerləşdirməzdən əvvəl istifadəçi razılaşmasını qəbul etməlisiniz."
      );
      return;
    }

    const fileInput = document.getElementById("fileInput");
    const videoInput = document.getElementById("video");
    const documentInput = document.getElementById("document");

    const formData = new FormData();
    console.log(document.getElementById("building-type").value);

    formData.append(
      "buildingType",
      document.getElementById("building-type").value
    );
    formData.append("addType", document.getElementById("add-type").value);
    formData.append(
      "propertyCondition",
      document.getElementById("repair-type").value
    );
    formData.append("price", document.getElementById("price").value);
    formData.append(
      "propertyPeriod",
      document.getElementById("property-period").value
    );
    formData.append("roomCount", document.getElementById("room-count").value);
    formData.append("floorCount", document.getElementById("floor-count").value);
    formData.append(
      "locatedFloor",
      document.getElementById("located-floor").value
    );
    formData.append(
      "isCredit",
      document.getElementById("is-credit").checked ? 1 : 0
    );
    formData.append(
      "description",
      document.getElementById("description").value
    );
    formData.append(
      "noteToAdmin",
      document.getElementById("note-to-admin").value
    );
    formData.append("cityId", document.getElementById("city").value);
    formData.append(
      "area",
      document.querySelector("#area input")?.value || null
    );
    formData.append(
      "fieldArea",
      document.querySelector("#field-area input")?.value || null
    );
    formData.append("districtId", document.getElementById("district").value);
    formData.append("townId", document.getElementById("town").value);
    formData.append("subwayId", document.getElementById("subway").value);
    formData.append("address", document.getElementById("address").value);
    formData.append("map", document.getElementById("map").value);
    formData.append(
      "advertiser",
      document.getElementById("advertiser")?.value || ""
    );
    formData.append(
      "advertiserName",
      document.getElementById("advertiser-name")?.value || ""
    );
    formData.append("phone1", document.getElementById("phone_1").value);
    formData.append("phone2", document.getElementById("phone_2").value);
    formData.append("phone3", document.getElementById("phone_3").value);
    formData.append("phone4", document.getElementById("phone_4").value);
    formData.append("email", document.getElementById("email").value);

    Array.from(
      document.querySelectorAll('#features input[name="features[]"]:checked')
    ).forEach((cb, i) => {
      formData.append(`features[${i}]`, cb.value);
    });

    Array.from(
      document.querySelectorAll(
        '#nearby-objects input[name="nearby-objects[]"]:checked'
      )
    ).forEach((cb, i) => {
      formData.append(`nearbyObjects[${i}]`, cb.value);
    });

    images.forEach((file, index) => {
      formData.append(`media[${index}][type]`, "image");
      formData.append(`media[${index}][file]`, file);
    });

    if (videoInput.files.length > 0) {
      const index = images.length;
      formData.append(`media[${index}][type]`, "video");
      formData.append(`media[${index}][file]`, videoInput.files[0]);
    }

    if (documentInput.files.length > 0) {
      const index = images.length + (videoInput.files.length ? 1 : 0);
      formData.append(`media[${index}][type]`, "document");
      formData.append(`media[${index}][file]`, documentInput.files[0]);
    }
    console.log({ formData });
    try {
      const response = await axios.post("/add-property", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      console.log(" Uğurla göndərildi:", response.data);
      localStorage.removeItem('unsavedPropertyData');
    } catch (error) {
      console.error("Error:", error.response?.data || error.message);
    }
  });

  const nearbyObjectsContainer = document.getElementById(
    "nearby-objects-container"
  );
  const toggleButton = document.getElementById("toggle-nearby-objects");
  const fadeOverlay = nearbyObjectsContainer.querySelector(".fade-overlay");

  nearbyObjectsContainer.classList.add("collapsed");

  toggleButton.addEventListener("click", () => {
    if (nearbyObjectsContainer.classList.contains("collapsed")) {
      nearbyObjectsContainer.classList.remove("collapsed");
      nearbyObjectsContainer.classList.add("expanded");
      fadeOverlay.classList.add("hidden");
      toggleButton.innerHTML =
        'Daha az göstər <i class="bi bi-chevron-up"></i>';
    } else {
      nearbyObjectsContainer.classList.remove("expanded");
      nearbyObjectsContainer.classList.add("collapsed");
      fadeOverlay.classList.remove("hidden");
      toggleButton.innerHTML =
        'Daha çox göstər <i class="bi bi-chevron-down"></i>';
    }
  });
  
  const featuresContainer = document.getElementById("features-container");
  const toggleFeaturesButton = document.getElementById("toggle-features");
  const featuresFadeOverlay = featuresContainer.querySelector(".fade-overlay");

  if (featuresContainer && toggleFeaturesButton && featuresFadeOverlay) {
    featuresContainer.classList.add("collapsed");

    toggleFeaturesButton.addEventListener("click", () => {
      if (featuresContainer.classList.contains("collapsed")) {
        featuresContainer.classList.remove("collapsed");
        featuresContainer.classList.add("expanded");
        featuresFadeOverlay.classList.add("hidden");
        toggleFeaturesButton.innerHTML =
          'Daha az göstər <i class="bi bi-chevron-up"></i> ';
      } else {
        featuresContainer.classList.remove("expanded");
        featuresContainer.classList.add("collapsed");
        featuresFadeOverlay.classList.remove("hidden");
        toggleFeaturesButton.innerHTML =
          'Daha çox göstər <i class="bi bi-chevron-down"></i>';
      }
    });
  }

  function initCustomSelect(config) {
    const container = document.getElementById(config.containerId);
    const button = document.getElementById(config.buttonId);
    const optionsList = document.getElementById(config.optionsId);
    const textSpan = document.getElementById(config.textId);
    const inputField = document.getElementById(config.inputId);
    const icon = document.getElementById(config.iconId);

    button.addEventListener("click", function () {
      const isHidden = optionsList.classList.contains("hidden");
      if (isHidden) {
        optionsList.classList.remove("hidden");
        optionsList.classList.add("fade-in");
        icon.classList.add("rotate-180");
      } else {
        optionsList.classList.add("hidden");
        optionsList.classList.remove("fade-in");
        icon.classList.remove("rotate-180");
      }
    });

    optionsList.addEventListener("click", function (event) {
      const selectedOption = event.target.closest("li");
      if (selectedOption) {
        const value = selectedOption.dataset.value;
        const text = selectedOption.textContent.trim();

        textSpan.textContent = text;
        inputField.value = value;

        optionsList.classList.add("hidden");
        optionsList.classList.remove("fade-in");
        icon.classList.remove("rotate-180");
        
        saveToLocalStorage(inputField);
        
        const addTypeSelect = document.getElementById('add-type');
        if (addTypeSelect) {
          addTypeSelect.value = value;
          addTypeSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }
      }
    });

    document.addEventListener("click", function (event) {
      if (!container.contains(event.target)) {
        optionsList.classList.add("hidden");
        optionsList.classList.remove("fade-in");
        icon.classList.remove("rotate-180");
      }
    });
  }

  initCustomSelect({
    containerId: "customTypeContainer",
    buttonId: "customTypeButton",
    optionsId: "customTypeOptions",
    textId: "customTypeText",
    inputId: "selectedTypeInput",
    iconId: "typeIcon",
  });
  
  
  const addPropertyBtn = document.getElementById('add-property-btn');
  const propertyForm = document.getElementById('propertyForm');
  const unsavedModal = document.getElementById('unsavedDataModal');
  const modalYes = document.getElementById('modalYes');
  const modalNo = document.getElementById('modalNo');

  setTimeout(() => {
    if (localStorage.getItem('unsavedPropertyData') && unsavedModal) {
      showModal();
    }
  }, 1000);

  if (addPropertyBtn) {
    addPropertyBtn.addEventListener('click', (e) => {
      if (e.target.closest('form') || addBtn === e.target) {
        return; 
      }
      
      const isLoggedIn = localStorage.getItem('isLoggedIn');
      if (!isLoggedIn) {
        alert('Əvvəlcə login olun!');
        return;
      }

      if (localStorage.getItem('unsavedPropertyData') && unsavedModal) {
        showModal();
      } else {
        openAddPropertyForm();
      }
    });
  }

  if (modalYes) {
    modalYes.addEventListener('click', async () => {
      try {
        const savedData = JSON.parse(localStorage.getItem('unsavedPropertyData'));
        console.log('Saved data from localStorage:', savedData);
        
        if (savedData) {
          await waitForDynamicElements();
          
          if (savedData.images) {
            loadImagesFromLocalStorage();
          }
          
          const allFormInputs = document.querySelectorAll('#propertyForm input, #propertyForm select, #propertyForm textarea');
          console.log('Found form inputs:', allFormInputs.length);
          
          allFormInputs.forEach(input => {
            const key = input.name || input.id;
            if (!key || savedData[key] === undefined) return;

            console.log(`Setting ${key} = ${savedData[key]}`);
            
            if (input.type === 'checkbox') {
              input.checked = savedData[key] === true || savedData[key] === 'true' || savedData[key] === '1';
            } else if (input.type === 'radio') {
              if (input.value === savedData[key]) {
                input.checked = true;
              }
            } else {
              input.value = savedData[key];
            }

            input.dispatchEvent(new Event('change', { bubbles: true }));
          });

          if (savedData.areaValue) {
            const areaInput = document.querySelector("#area input");
            if (areaInput) {
              areaInput.value = savedData.areaValue;
              console.log('Set area value:', savedData.areaValue);
            }
          }

          if (savedData.fieldAreaValue) {
            const fieldAreaInput = document.querySelector("#field-area input");
            if (fieldAreaInput) {
              fieldAreaInput.value = savedData.fieldAreaValue;
              console.log('Set field area value:', savedData.fieldAreaValue);
            }
          }

          if (savedData.type || savedData.selectedTypeInput) {
            const typeValue = savedData.type || savedData.selectedTypeInput;
            setTimeout(() => updateCustomTypeDisplay(typeValue), 100);
          }

          if (savedData.city) {
            setTimeout(() => {
              const citySelect = document.getElementById('city');
              if (citySelect) {
                citySelect.value = savedData.city;
                citySelect.dispatchEvent(new Event('change', { bubbles: true }));
                
                if (savedData.district) {
                  setTimeout(() => {
                    const districtSelect = document.getElementById('district');
                    if (districtSelect && districtSelect.options.length > 1) {
                      districtSelect.value = savedData.district;
                      districtSelect.dispatchEvent(new Event('change', { bubbles: true }));
                      
                      if (savedData.town) {
                        setTimeout(() => {
                          const townSelect = document.getElementById('town');
                          if (townSelect && townSelect.options.length > 1) {
                            townSelect.value = savedData.town;
                          }
                        }, 300);
                      }
                    }
                  }, 300);
                }
              }
            }, 200);
          }

          setTimeout(() => {
            document.querySelectorAll('input[name="features[]"]').forEach(checkbox => {
              checkbox.checked = false; 
            });
            
            if (savedData.selectedFeatures && Array.isArray(savedData.selectedFeatures)) {
              savedData.selectedFeatures.forEach(value => {
                const checkbox = document.querySelector(`input[name="features[]"][value="${value}"]`);
                if (checkbox) {
                  checkbox.checked = true;
                  console.log('Selected feature:', value);
                }
              });
            }
            
            document.querySelectorAll('input[name="nearby-objects[]"]').forEach(checkbox => {
              checkbox.checked = false; 
            });
            
            if (savedData.selectedNearbyObjects && Array.isArray(savedData.selectedNearbyObjects)) {
              savedData.selectedNearbyObjects.forEach(value => {
                const checkbox = document.querySelector(`input[name="nearby-objects[]"][value="${value}"]`);
                if (checkbox) {
                  checkbox.checked = true;
                  console.log('Selected nearby object:', value);
                }
              });
            }
          }, 500);
        }
        
        hideModal();
        openAddPropertyForm();
      } catch (error) {
        console.error("Error loading saved data:", error);
        localStorage.removeItem('unsavedPropertyData');
        hideModal();
        openAddPropertyForm();
      }
    });
  }

  if (modalNo) {
    modalNo.addEventListener('click', () => {
      localStorage.removeItem('unsavedPropertyData');
      hideModal();
      openAddPropertyForm();
    });
  }

  setTimeout(() => {
    attachInputListeners();
  }, 2000);

  function saveFeaturesToLocalStorage() {
    let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
    const selectedFeatures = [];
    
    document.querySelectorAll('input[name="features[]"]:checked').forEach(cb => {
      selectedFeatures.push(cb.value);
    });
    
    data.selectedFeatures = selectedFeatures;
    localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
    console.log('Saved features:', selectedFeatures);
  }

  function saveNearbyObjectsToLocalStorage() {
    let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
    const selectedNearbyObjects = [];
    
    document.querySelectorAll('input[name="nearby-objects[]"]:checked').forEach(cb => {
      selectedNearbyObjects.push(cb.value);
    });
    
    data.selectedNearbyObjects = selectedNearbyObjects;
    localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
    console.log('Saved nearby objects:', selectedNearbyObjects);
  }

  function attachInputListeners() {
    const allInputs = document.querySelectorAll('#propertyForm input, #propertyForm select, #propertyForm textarea');
    console.log('Attaching listeners to', allInputs.length, 'inputs');
    
    allInputs.forEach(input => {
      ['input', 'change'].forEach(eventType => {
        input.addEventListener(eventType, () => {
          saveToLocalStorage(input);
        });
      });
    });

    setTimeout(() => {
      const areaInput = document.querySelector("#area input");
      const fieldAreaInput = document.querySelector("#field-area input");

      if (areaInput) {
        areaInput.addEventListener('input', () => {
          let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
          data.areaValue = areaInput.value;
          localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
          console.log('Saved area value:', areaInput.value);
        });
      }

      if (fieldAreaInput) {
        fieldAreaInput.addEventListener('input', () => {
          let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
          data.fieldAreaValue = fieldAreaInput.value;
          localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
          console.log('Saved field area value:', fieldAreaInput.value);
        });
      }

      const featuresCheckboxes = document.querySelectorAll('input[name="features[]"]');
      const nearbyCheckboxes = document.querySelectorAll('input[name="nearby-objects[]"]');
      
      console.log('Found features checkboxes:', featuresCheckboxes.length);
      console.log('Found nearby checkboxes:', nearbyCheckboxes.length);
      
      featuresCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
          console.log('Feature checkbox changed:', checkbox.value, checkbox.checked);
          saveFeaturesToLocalStorage();
        });
      });

      nearbyCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
          console.log('Nearby object checkbox changed:', checkbox.value, checkbox.checked);
          saveNearbyObjectsToLocalStorage();
        });
      });
    }, 500);
  }

  function saveToLocalStorage(input) {
    let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
    const key = input.name || input.id;
    
    if (!key) return;
    
    if (input.type === 'checkbox') {
      data[key] = input.checked;
    } else if (input.type === 'radio') {
      if (input.checked) {
        data[key] = input.value;
      }
    } else {
      data[key] = input.value;
    }
    
    localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
    console.log(`Saved ${key}:`, data[key]);
  }

  function waitForDynamicElements() {
    return new Promise(resolve => {
      let attempts = 0;
      const maxAttempts = 100;
      
      const checkInterval = setInterval(() => {
        attempts++;
        const cityOptions = document.getElementById('city')?.options?.length || 0;
        const featuresLoaded = document.getElementById('features')?.children?.length || 0;
        const nearbyObjectsLoaded = document.getElementById('nearby-objects')?.children?.length || 0;
        
        console.log(`Attempt ${attempts}: cities=${cityOptions}, features=${featuresLoaded}, nearby=${nearbyObjectsLoaded}`);
        
        if (cityOptions > 1 && featuresLoaded > 0 && nearbyObjectsLoaded > 0) {
          clearInterval(checkInterval);
          console.log('All dynamic elements loaded successfully');
          resolve();
        } else if (attempts >= maxAttempts) {
          clearInterval(checkInterval);
          console.log('Timeout: proceeding with available elements');
          resolve();
        }
      }, 100);
    });
  }

  function updateCustomTypeDisplay(value) {
    const customTypeText = document.getElementById('customTypeText');
    const selectedTypeInput = document.getElementById('selectedTypeInput');
    const addTypeSelect = document.getElementById('add-type');
    
    console.log('Updating custom type display:', value);
    
    if (value === 'sale') {
      if (customTypeText) customTypeText.textContent = 'For Sale';
    } else if (value === 'rent') {
      if (customTypeText) customTypeText.textContent = 'For Rent';
    }
    
    if (selectedTypeInput) selectedTypeInput.value = value;
    if (addTypeSelect) {
      addTypeSelect.value = value;
      addTypeSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }
  }

  function openAddPropertyForm() {
    if (propertyForm) {
      propertyForm.classList.remove('hidden');
      propertyForm.classList.add('flex');
    }
  }

  function showModal() {
    if (unsavedModal) {
      unsavedModal.classList.remove('hidden');
      unsavedModal.style.display = 'flex';
      unsavedModal.style.alignItems = 'center';
      unsavedModal.style.justifyContent = 'center';
      unsavedModal.style.backgroundColor = 'rgba(0,0,0,0.4)';
    }
  }

  function hideModal() {
    if (unsavedModal) {
      unsavedModal.classList.add('hidden');
      unsavedModal.style.display = 'none';
    }
  }

  function clearFeatureSelections() {
    let data = JSON.parse(localStorage.getItem('unsavedPropertyData')) || {};
    data.selectedFeatures = [];
    data.selectedNearbyObjects = [];
    localStorage.setItem('unsavedPropertyData', JSON.stringify(data));
    console.log('Cleared feature selections');
  }

  // Features search
    document.getElementById("featureSearch").addEventListener("keyup", function () {
        let searchValue = this.value.toLowerCase().trim();
        let items = document.querySelectorAll("#features label");

        items.forEach(function (item) {
            let text = item.textContent.toLowerCase().trim();
            item.style.display = text.startsWith(searchValue) ? "" : "none";
        });
    });

    // Nearby objects search
    document.getElementById("nearbySearch").addEventListener("keyup", function () {
        let searchValue = this.value.toLowerCase().trim();
        let items = document.querySelectorAll("#nearby-objects label");

        items.forEach(function (item) {
            let text = item.textContent.toLowerCase().trim();
            item.style.display = text.startsWith(searchValue) ? "" : "none";
        });
    });
});