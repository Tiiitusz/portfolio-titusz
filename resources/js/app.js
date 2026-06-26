import './projectPagination';

const projectForm = document.querySelector('[data-project-form]');

if (projectForm) {
    const uploadRows = Array.from(projectForm.querySelectorAll('.admin-upload-control'));
    const galleryPreview = projectForm.querySelector('.admin-gallery-grid');
    const submitUploadButton = projectForm.querySelector('[data-submit-project-form]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    uploadRows.forEach((control) => {
        const input = control.querySelector('[data-upload-input]');
        const trigger = control.querySelector('[data-upload-trigger]');
        const label = control.querySelector('[data-upload-name]');

        if (input && trigger) {
            trigger.addEventListener('click', () => {
                input.click();
            });
        }

        if (input && label) {
            input.addEventListener('change', () => {
                if (!input.files || input.files.length === 0) {
                    label.textContent = 'No file selected';
                    return;
                }

                if (input.multiple) {
                    label.textContent = `${input.files.length} file${input.files.length === 1 ? '' : 's'} selected`;
                } else {
                    label.textContent = input.files[0].name;
                }
            });
        }
    });

    if (galleryPreview) {
        galleryPreview.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-image]');

            if (!removeButton) {
                return;
            }

            const galleryItem = removeButton.closest('[data-gallery-item]');

            if (!galleryItem) {
                return;
            }

            const imageName = galleryItem.dataset.imageName;
            const removalInput = document.createElement('input');
            removalInput.type = 'hidden';
            removalInput.name = 'remove_images[]';
            removalInput.value = imageName;
            projectForm.appendChild(removalInput);

            galleryItem.remove();
        });
    }

    if (submitUploadButton) {
        submitUploadButton.addEventListener('click', async () => {
            const uploadUrl = submitUploadButton.dataset.uploadUrl;
            const imageInput = projectForm.querySelector('input[type="file"][name="images[]"]');

            if (!uploadUrl || !imageInput || !imageInput.files || imageInput.files.length === 0) {
                return;
            }

            const formData = new FormData();

            Array.from(imageInput.files).forEach((file) => {
                formData.append('images[]', file);
            });

            submitUploadButton.disabled = true;
            submitUploadButton.textContent = 'Uploading...';

            try {
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Upload failed');
                }

                const data = await response.json();
                const galleryGrid = projectForm.querySelector('[data-gallery-grid]');
                const galleryEmpty = projectForm.querySelector('[data-gallery-empty]');

                if (galleryEmpty) {
                    galleryEmpty.remove();
                }

                if (galleryGrid && Array.isArray(data.images)) {
                    data.images.forEach((image) => {
                        const galleryItem = document.createElement('div');
                        galleryItem.className = 'admin-gallery-item';
                        galleryItem.dataset.galleryItem = '';
                        galleryItem.dataset.imageName = image.name;
                        galleryItem.innerHTML = `
                            <img src="${image.url}" alt="Gallery image" loading="lazy">
                            <button type="button" class="admin-gallery-remove-button" data-remove-image>Delete</button>
                        `;
                        galleryGrid.appendChild(galleryItem);
                    });
                }

                imageInput.value = '';
                const uploadName = submitUploadButton.parentElement?.querySelector('[data-upload-name]');
                if (uploadName) {
                    uploadName.textContent = 'No files selected';
                }
            } catch (error) {
                console.error(error);
                alert('The images could not be uploaded.');
            } finally {
                submitUploadButton.disabled = false;
                submitUploadButton.textContent = 'Upload images';
            }
        });
    }
}

const boat = document.querySelector('.balaton-boat');

let scrollProgress = 0;

function readScroll() {
    const doc = document.documentElement;
    const scrollTop = window.scrollY || doc.scrollTop || 0;
    const maxScroll = Math.max(doc.scrollHeight - window.innerHeight, 1);
    scrollProgress = Math.min(Math.max(scrollTop / maxScroll, 0), 1);
}

window.addEventListener('scroll', readScroll, { passive: true });
window.addEventListener('resize', readScroll);

readScroll();


let maxX = window.innerWidth - (boat ? boat.offsetWidth : 0);
let boatX = -500;
let lastTimestamp = 0;

function animate(timestamp) {
    let deltaTime = timestamp - lastTimestamp;
    lastTimestamp = timestamp;
    if (boat) {
        maxX = window.innerWidth - boat.offsetWidth+500;
        const XNeedsToBe = scrollProgress * Math.max(maxX, -500)-500;
        let deltaX = 0;
        let x = 0;
        if (boatX - XNeedsToBe < 1 && boatX - XNeedsToBe > -1) {
            x = XNeedsToBe;
            boatX = x;
        }
        else {
            deltaX = (XNeedsToBe - boatX) * deltaTime/1000;
            x = boatX + deltaX;
            boatX = x;
        }
        const y = Math.sin(timestamp / 900) * 6;
    
        boat.style.transform = `translate(${x}px, ${y}px)`;
    }
    requestAnimationFrame(animate);
}
requestAnimationFrame(animate);


