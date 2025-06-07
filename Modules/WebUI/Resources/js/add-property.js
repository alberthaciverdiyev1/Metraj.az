    document.addEventListener("DOMContentLoaded", () => {
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
    });


    


    //FLOORRR
    let floorCount = 1;


    function addFloor() {
        floorCount++;
        const container = document.getElementById('floors-container');
        const floor = document.createElement('div');
        floor.className = 'bg-gray-100 border border-gray-200 p-4 rounded-lg relative mt-6';
        floor.innerHTML = `
            <button onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-2xl text-gray-500 hover:text-red-500 w-8 h-8 flex items-center justify-center">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Floor ${floorCount}:</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Floor Name:</label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Price Postfix:</label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Floor Price (Only Digits):</label>
                    <input type="number" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Size Postfix:</label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Floor Size (Only Digits):</label>
                    <input type="number" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Bedrooms:</label>
                    <input type="number" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Bathrooms:</label>
                    <input type="number" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
                <div>
                    <label class="block font-medium mb-1">Description:</label>
                    <textarea class="w-full border border-gray-300 rounded p-2 bg-white"></textarea>
                </div>
                <div>
                    <label class="block font-medium mb-1">Floor Image:</label>
                    <input type="file" class="w-full border border-gray-300 rounded p-2 bg-white" />
                </div>
            </div>
        `;
        container.appendChild(floor);
    }

    