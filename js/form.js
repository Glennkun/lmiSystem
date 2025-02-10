// Add this to your JavaScript file
document.querySelector('input[name="birthdate"]').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    // Add a hidden input for age if it doesn't exist
    let ageInput = document.querySelector('input[name="age"]');
    if (!ageInput) {
        ageInput = document.createElement('input');
        ageInput.type = 'hidden';
        ageInput.name = 'age';
        this.parentNode.appendChild(ageInput);
    }
    ageInput.value = age;
});
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        document.getElementById('dataForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                return;
            }
            
            // Set current date for dateEncoded
            const formData = new FormData(this);
            formData.set('dateEncoded', new Date().toISOString().split('T')[0]);
            
            // Ensure all text data is uppercase before sending
            for (let pair of formData.entries()) {
                if (typeof pair[1] === 'string' && pair[0] !== 'dateEncoded') {
                    formData.set(pair[0], pair[1].toUpperCase());
                }
            }
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Entry has been added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('dataForm').reset();
                            document.getElementById('dataForm').classList.remove('was-validated');
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });


        // new

        function loadLatestEntry() {
            fetch('?action=getLatest')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.data) {
                        updateTable([data.data]);
                    }
                });
        }

        function loadEntriesByDate(date) {
            fetch(`?action=getByDate&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateTable(data.data);
                    }
                });
        }

        function updateTable(data) {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
        
            data.forEach((row, index) => { // Use index to count rows
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${index + 1}</td> <!-- Row number starts from 1 -->
                    <td>${row.firstName}</td>
                    <td>${row.mi}</td>
                    <td>${row.lastName}</td>
                    <td>${row.suffix || 'N/A'}</td>
                    <td>${row.occupation || 'N/A'}</td>
                    <td>${row.birthdate}</td>
                    <td>${row.age}</td>
                    <td>${row.sex}</td>
                    <td>${row.civilStatus}</td>
                    <td>${row.religion || 'N/A'}</td>
                    <td>${row.educational || 'N/A'}</td>
                    <td>${row.course || 'N/A'}</td>
                    <td>${row.vocational || 'N/A'}</td>
                    <td>${row.workExperience || 'N/A'}</td>
                    <td>${row.employmentStatus}</td>
                    <td>${row.purok || 'N/A'}</td>
                    <td>${row.sitio}</td>
                    <td>${row.barangay || 'N/A'}</td>
                    <td>${row.municipality}</td>
                    <td>${row.province}</td>
                    <td>${row.contact || 'N/A'}</td>
                    <td>${row.email || 'N/A'}</td>
                    <td>${row.local_overseas}</td>
                    <td>${row.remarks || ''}</td>
                    <td>${row.dateEncoded}</td>
                    <td>${row.ojt_name}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        }
        

        document.getElementById('showTableBtn').addEventListener('click', function() {
            const date = document.getElementById('dateFilter').value;
            loadEntriesByDate(date);
        });

        document.getElementById('dateFilter').addEventListener('change', function() {
            loadEntriesByDate(this.value);
        });

        document.getElementById('exportBtn').addEventListener('click', function() {
            const date = document.getElementById('dateFilter').value;
            const table = document.querySelector('table');
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Data');
            XLSX.writeFile(wb, `LMI ${date}.xlsx`);
        });

        // Add event delegation for edit and delete buttons
document.getElementById('tableBody').addEventListener('click', async function(e) {
    const editBtn = e.target.closest('.edit-btn');
    const deleteBtn = e.target.closest('.delete-btn');
    
    if (editBtn) {
        const id = editBtn.dataset.id;
        try {
            const response = await fetch(`?action=getById&id=${id}`);
            const result = await response.json();
            
            if (result.status === 'success') {
                // Populate the form with the data
                const form = document.getElementById('dataForm');
                const data = result.data;
                
                // Populate each form field
                for (const key in data) {
                    const input = form.elements[key];
                    if (input) {
                        input.value = data[key];
                    }
                }
                
                // Calculate age based on birthdate
                const birthDate = new Date(data.birthdate);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                // Add hidden input for ID to track which record we're editing
                let idInput = form.querySelector('input[name="id"]');
                if (!idInput) {
                    idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'id';
                    form.appendChild(idInput);
                }
                idInput.value = id;
                
                // Change form submission method to edit
                let actionInput = form.querySelector('input[name="action"]');
                if (!actionInput) {
                    actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    form.appendChild(actionInput);
                }
                actionInput.value = 'edit';
                
                // Change button text to indicate editing
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Entry';
                
                // Scroll to the form
                form.scrollIntoView({ behavior: 'smooth' });
                
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: result.message || 'Failed to load data',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong while loading the data',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    } else if (deleteBtn) {
        // Existing delete functionality remains the same
        const id = deleteBtn.dataset.id;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        loadEntriesByDate(document.getElementById('dateFilter').value);
                        Swal.fire('Deleted!', 'Entry has been deleted.', 'success');
                    }
                });
            }
        });
    }
});

// Modify the form submit handler to handle both insert and edit
document.getElementById('dataForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!this.checkValidity()) {
        this.classList.add('was-validated');
        return;
    }
    
    const formData = new FormData(this);
    const isEdit = formData.get('action') === 'edit';
    
    // Set current date for dateEncoded if it's a new entry
    if (!isEdit) {
        formData.set('dateEncoded', new Date().toISOString().split('T')[0]);
    }
    
    // Ensure all text data is uppercase before sending
    for (let pair of formData.entries()) {
        if (typeof pair[1] === 'string' && pair[0] !== 'dateEncoded') {
            formData.set(pair[0], pair[1].toUpperCase());
        }
    }
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Success!',
                text: isEdit ? 'Entry has been updated successfully.' : 'Entry has been added successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset form and state if it was an edit
                    if (isEdit) {
                        // Remove action and id inputs
                        this.querySelector('input[name="action"]').remove();
                        this.querySelector('input[name="id"]').remove();
                        
                        // Reset button text
                        const submitBtn = this.querySelector('button[type="submit"]');
                        submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Entry';
                    }
                    
                    // Clear form and validation states
                    this.reset();
                    this.classList.remove('was-validated');
                    
                    // Refresh table data
                    loadEntriesByDate(document.getElementById('dateFilter').value);
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Something went wrong.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
});

        // Load the latest entry after form submission
        const originalFormSubmit = document.getElementById('dataForm').onsubmit;
        document.getElementById('dataForm').onsubmit = function(e) {
            const originalHandler = originalFormSubmit.call(this, e);
            if (originalHandler !== false) {
                loadLatestEntry();
            }
        };

        // Load the latest entry on page load
        loadLatestEntry();

        function updateRowNumbers() {
            let table = document.getElementById("myTable"); // Change "myTable" to your table's ID
            let rows = table.getElementsByTagName("tr");
        
            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
                let cell = rows[i].getElementsByTagName("td")[0]; // Assuming the first column is for numbering
                if (cell) {
                    cell.textContent = i;
                }
            }
        }