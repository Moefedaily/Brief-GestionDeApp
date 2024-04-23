document.addEventListener('DOMContentLoaded', function () {
    fetchData();
});

function fetchData() {
    fetch('/cours/Brief-GestionDeApp/dashboard/data')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            renderCourseCards(data.courses, data.attendanceData, data.userRole);
            renderClasses(data.classes);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function renderClasses(classesData) {
    const classesContainer = document.getElementById('classesContainer');
    classesContainer.innerHTML = '';

    const table = document.createElement('table');
    table.classList.add('min-w-full', 'divide-y', 'divide-gray-200');

    const thead = document.createElement('thead');
    thead.classList.add('bg-gray-50');

    const theadRow = document.createElement('tr');

    const classNameHeader = document.createElement('th');
    classNameHeader.scope = 'col';
    classNameHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    classNameHeader.textContent = 'Class Name';

    const startDateHeader = document.createElement('th');
    startDateHeader.scope = 'col';
    startDateHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    startDateHeader.textContent = 'Start Date';

    const endDateHeader = document.createElement('th');
    endDateHeader.scope = 'col';
    endDateHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    endDateHeader.textContent = 'End Date';

    const availablePlacesHeader = document.createElement('th');
    availablePlacesHeader.scope = 'col';
    availablePlacesHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    availablePlacesHeader.textContent = 'Available Places';

    const actionsHeader = document.createElement('th');
    actionsHeader.scope = 'col';
    actionsHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    actionsHeader.textContent = 'Actions';

    theadRow.appendChild(classNameHeader);
    theadRow.appendChild(startDateHeader);
    theadRow.appendChild(endDateHeader);
    theadRow.appendChild(availablePlacesHeader);
    theadRow.appendChild(actionsHeader);
    thead.appendChild(theadRow);

    const tbody = document.createElement('tbody');
    tbody.classList.add('bg-white', 'divide-y', 'divide-gray-200');

    classesData.forEach(classData => {
        const row = document.createElement('tr');

        const classNameCell = document.createElement('td');
        classNameCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');
        classNameCell.textContent = classData.class_name;

        const startDateCell = document.createElement('td');
        startDateCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
        startDateCell.textContent = classData.class_start_date;

        const endDateCell = document.createElement('td');
        endDateCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
        endDateCell.textContent = classData.class_end_date;

        const availablePlacesCell = document.createElement('td');
        availablePlacesCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
        availablePlacesCell.textContent = classData.places_available;

        const actionsCell = document.createElement('td');
        actionsCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');

        const seeLink = document.createElement('a');
        seeLink.href = '#';
        seeLink.classList.add('text-indigo-600', 'hover:text-indigo-900', 'mr-2');
        seeLink.textContent = 'See';
        seeLink.addEventListener('click', () => showClassDetails(classData.class_id));


        const editLink = document.createElement('a');
        editLink.href = '#';
        editLink.classList.add('text-indigo-600', 'hover:text-indigo-900', 'mr-2');
        editLink.textContent = 'Edit';
        editLink.addEventListener('click', () => showEditForm(classData, table));

        const deleteLink = document.createElement('a');
        deleteLink.href = '#';
        deleteLink.classList.add('text-indigo-600', 'hover:text-indigo-900');
        deleteLink.textContent = 'Delete';
        deleteLink.addEventListener('click', () => deleteClass(classData.class_id));

        actionsCell.appendChild(seeLink);
        actionsCell.appendChild(editLink);
        actionsCell.appendChild(deleteLink);

        row.appendChild(classNameCell);
        row.appendChild(startDateCell);
        row.appendChild(endDateCell);
        row.appendChild(availablePlacesCell);
        row.appendChild(actionsCell);

        tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    const createClassBtn = document.createElement('button');
    createClassBtn.textContent = 'Create Class';
    createClassBtn.classList.add('px-6', 'py-2', 'text-sm', 'text-white', 'bg-indigo-600', 'rounded-md', 'hover:bg-indigo-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500', 'font-medium');
    createClassBtn.addEventListener('click', () => showCreateForm(table));

    classesContainer.appendChild(createClassBtn);
    classesContainer.appendChild(table);

    const editFormContainer = document.createElement('div');
    editFormContainer.id = 'editFormContainer';
    editFormContainer.classList.add('hidden');

    const createFormContainer = document.createElement('div');
    createFormContainer.id = 'createFormContainer';
    createFormContainer.classList.add('hidden');

    classesContainer.appendChild(editFormContainer);
    classesContainer.appendChild(createFormContainer);

}

//////////////////////////Edit Form  //////////////////////////

function showEditForm(classData, table) {
    const editFormContainer = document.getElementById('editFormContainer');
    editFormContainer.innerHTML = '';
    editFormContainer.classList.remove('hidden');
    table.classList.add('hidden');

    const createClassBtn = document.querySelector('#classesContainer > button');
    createClassBtn.classList.add('hidden');

    const editClassForm = document.createElement('form');
    editClassForm.id = 'edit-class-form';
    editClassForm.classList.add('space-y-4');

    const classNameField = createFormField('Class Name', 'text', 'class_name', classData.class_name);
    const startDateField = createFormField('Start Date', 'date', 'class_start_date', classData.class_start_date);
    const endDateField = createFormField('End Date', 'date', 'class_end_date', classData.class_end_date);
    const availablePlacesField = createFormField('Available Places', 'number', 'places_available', classData.places_available);

    editClassForm.appendChild(classNameField);
    editClassForm.appendChild(startDateField);
    editClassForm.appendChild(endDateField);
    editClassForm.appendChild(availablePlacesField);

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('flex', 'justify-end', 'space-x-4', 'mt-6');

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.classList.add('px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:text-gray-900', 'font-medium');
    cancelButton.textContent = 'Cancel';
    cancelButton.addEventListener('click', () => {
        editFormContainer.classList.add('hidden');
        editFormContainer.innerHTML = '';
        table.classList.remove('hidden');
        createClassBtn.classList.remove('hidden');
    });

    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.classList.add('px-6', 'py-2', 'text-sm', 'text-white', 'bg-indigo-600', 'rounded-md', 'hover:bg-indigo-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500', 'font-medium');
    submitButton.textContent = 'Save Changes';

    buttonContainer.appendChild(cancelButton);
    buttonContainer.appendChild(submitButton);

    editClassForm.appendChild(buttonContainer);
    editFormContainer.appendChild(editClassForm);

    editClassForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        formData.append('class_id', classData.class_id);

        fetch(`/cours/Brief-GestionDeApp/dashboard/edit_class/${classData.class_id}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            editFormContainer.classList.add('hidden');
            editFormContainer.innerHTML = '';
            table.classList.remove('hidden');
            fetchData();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
}

//////////////////////////Create Form  //////////////////////////

function showCreateForm(table) {
    const createFormContainer = document.getElementById('createFormContainer');
    createFormContainer.innerHTML = '';
    createFormContainer.classList.remove('hidden');
    table.classList.add('hidden');

    const createClassForm = document.createElement('form');
    createClassForm.id = 'create-class-form';
    createClassForm.classList.add('space-y-4');

    const classNameField = createFormField('Class Name', 'text', 'class_name', '');
    const startDateField = createFormField('Start Date', 'date', 'start_date', '');
    const endDateField = createFormField('End Date', 'date', 'end_date', '');
    const availablePlacesField = createFormField('Available Places', 'number', 'places_available', '');

    createClassForm.appendChild(classNameField);
    createClassForm.appendChild(startDateField);
    createClassForm.appendChild(endDateField);
    createClassForm.appendChild(availablePlacesField);

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('flex', 'justify-end', 'space-x-4', 'mt-6');

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.classList.add('px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:text-gray-900', 'font-medium');
    cancelButton.textContent = 'Cancel';
    cancelButton.addEventListener('click', () => {
        createFormContainer.classList.add('hidden');
        createFormContainer.innerHTML = '';
        table.classList.remove('hidden');
    });

    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.classList.add('px-6', 'py-2', 'text-sm', 'text-white', 'bg-indigo-600', 'rounded-md', 'hover:bg-indigo-700', 'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500', 'font-medium');
    submitButton.textContent = 'Create Class';

    buttonContainer.appendChild(cancelButton);
    buttonContainer.appendChild(submitButton);

    createClassForm.appendChild(buttonContainer);
    createFormContainer.appendChild(createClassForm);

    createClassForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        fetch(`/cours/Brief-GestionDeApp/dashboard/create_class`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            createFormContainer.classList.add('hidden');
            createFormContainer.innerHTML = '';
            table.classList.remove('hidden');
            fetchData();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
}


function createFormField(label, type, name, value) {
    const formGroup = document.createElement('div');
    formGroup.classList.add('mb-4');

    const formLabel = document.createElement('label');
    formLabel.classList.add('block', 'text-sm', 'font-medium', 'text-gray-700');
    formLabel.textContent = label;

    
    const formInput = document.createElement('input');
    formInput.type = type;
    formInput.name = name;
    formInput.value = value;
    formInput.required = true;
    formInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring-indigo-500', 'focus:border-indigo-500', 'sm:text-sm');

    if (type === 'checkbox') {
        formInput.checked = value;
        formInput.value = value ? '1' : '0'; 
        formInput.required = false;

    } else {
        formInput.value = value;
    }


    formGroup.appendChild(formLabel);
    formGroup.appendChild(formInput);

    return formGroup;
}

//////////////////////////Delete Class //////////////////////////

function deleteClass(classId) {
    const confirmDelete = confirm('Are you sure you want to delete this class?');

    if (confirmDelete) {
        fetch(`/cours/Brief-GestionDeApp/dashboard/delete_class/${classId}`, {
            method: 'DELETE'
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            fetchData();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

//////////////////////////Show Class Details Info//////////////////////////

function showClassDetails(classId) {
    const classDetailsContainer = document.getElementById('classDetailsContainer');
    const classesContainer = document.getElementById('classesContainer');
    const courseCardsContainer = document.getElementById('courseCards');

    classDetailsContainer.classList.remove('hidden');
    classesContainer.classList.add('hidden');
    courseCardsContainer.classList.add('hidden');

    fetch(`/cours/Brief-GestionDeApp/dashboard/class/${classId}/students`)
        .then(response => response.json())
        .then(data => {
            console.log(data.classData);
            renderClassGeneralInfo(data.classData, 'studentInfo');
             renderStudentInfo(data.students,data.classData);
            renderAttendanceRecords(data.attendance);
            setupClassDetailsTabsEventListeners(data.classData);
            showClassDetailsTab(null, 'studentInfo');
         })
        .catch(error => {
            console.error('Error:', error);
        });
}

//////////////////////////Class General Info//////////////////////////

function renderClassGeneralInfo(classData, activeTab) {
    console.log(classData);
    const classGeneralInfoDiv = document.getElementById('classGeneralInfo');
    if (classData) {
        if (activeTab === 'studentInfo') {
            classGeneralInfoDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
            <div>
            <h2 class="text-xl mb-2"> Class  ${classData.class_name}</h2>
            <h3 class="text-xl ">General Information of ${classData.class_name}</h3> 
            </div>
            <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="addStudentLink">
            Add Student
                    </a>
                </div>
            `;
            const addStudentLink = document.getElementById('addStudentLink');
            addStudentLink.addEventListener('click', (event) => {
                event.preventDefault();
                showAddStudentForm(classData);
            });

        } else if (activeTab === 'attendanceRecords') {
            classGeneralInfoDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
            <div>
            <h2 class="text-xl  mb-2"> Class  ${classData.class_name}</h2>
            <h3 class="text-xl ">Attendance Table</h3>
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Delay
             </button>
             </div>
            `;
        }
    }
}


function showAddStudentForm(classData) {
    const addStudentButton = document.getElementById('addStudentLink');
    const addStudentFormContainer = document.getElementById('addStudentFormContainer');
    const studentInfoTable = document.getElementById('studentInfo').querySelector('table');

    renderAddStudentForm(classData);

    if (addStudentButton) {
        addStudentButton.style.display = 'none';
    }

    addStudentFormContainer.style.display = 'block';

    if (studentInfoTable) {
        studentInfoTable.style.display = 'none';
    }
}

function hideAddStudentForm(addStudentFormContainer) {
    addStudentFormContainer.style.display = 'none';
    addStudentFormContainer.innerHTML = '';
    const studentInfo = document.getElementById('studentInfo');
    studentInfo.style.display = 'block';
    const studentInfoTable = document.getElementById('studentInfo').querySelector('table');
    if (studentInfoTable) {
        studentInfoTable.style.display = 'table';
    }
    const addStudentButton = document.getElementById('addStudentLink');
    if (addStudentButton) {
        addStudentButton.style.display = 'block';
    }
}

function renderAddStudentForm(classData) {
    const addStudentFormContainer = document.getElementById('addStudentFormContainer');
    addStudentFormContainer.innerHTML = '';

    const addStudentForm = document.createElement('div');
    addStudentForm.classList.add('max-w-md', 'mx-auto', 'bg-white', 'rounded-lg', 'shadow-md', 'p-6');

    const formTitle = document.createElement('h2');
    formTitle.classList.add('text-2xl', 'font-bold', 'mb-6', 'text-center');
    formTitle.textContent = 'Add New Student';

    addStudentForm.appendChild(formTitle);

    const registrationForm = document.createElement('form');
    registrationForm.id = 'registrationForm';
    registrationForm.method = 'POST';
    
 
    const lastNameField = createFormField('Last Name', 'text', 'last_name', '');
    registrationForm.appendChild(lastNameField);

    const firstNameField = createFormField('First Name', 'text', 'first_name', '');
    registrationForm.appendChild(firstNameField);

    const emailField = createFormField('Email Address', 'email', 'email', '');
    registrationForm.appendChild(emailField);

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('flex', 'justify-end', 'mt-6');

    const cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.classList.add('bg-gray-300', 'text-gray-700', 'font-bold', 'py-2', 'px-4', 'rounded-md', 'hover:bg-gray-400', 'focus:outline-none', 'focus:ring-2', 'focus:ring-gray-500', 'mr-2');
    cancelButton.textContent = 'Cancel';
    cancelButton.addEventListener('click', () => hideAddStudentForm(addStudentFormContainer));

    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.classList.add('bg-blue-500', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded-md', 'hover:bg-blue-600', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
    submitButton.textContent = 'Save';

    buttonContainer.appendChild(cancelButton);
    buttonContainer.appendChild(submitButton);

    registrationForm.appendChild(buttonContainer);
    addStudentForm.appendChild(registrationForm);

    addStudentFormContainer.appendChild(addStudentForm);
   
    let formSubmitted = false;

    registrationForm.addEventListener('submit', (event) => {
        event.preventDefault();
        if (formSubmitted) {
            return;
        }

        formSubmitted = true;

        const formData = new FormData(event.target);
        formData.append('class_id', classData.class_id);
        formData.append('activation', '0');

        fetch('/cours/Brief-GestionDeApp/register-student', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
        })
        .then(data => {
            if (data.success) {
                const successMessage = document.createElement('div');
                successMessage.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-4', 'py-3', 'rounded', 'mb-4');
                successMessage.textContent = 'Registration successful!';

                addStudentFormContainer.insertBefore(successMessage, addStudentFormContainer.firstChild);
                console.log('Registration successful');
                registrationForm.reset();
            }
            formSubmitted = false;
        })
            .catch(error => {
                console.error('Error:', error);
                addStudentFormContainer.insertBefore(error, addStudentFormContainer.firstChild);
                formSubmitted = false;
            });
        });
    }

    function deleteStudent(userId,classData) {
        const confirmDelete = confirm('Are you sure you want to delete this student?');
    
        if (confirmDelete) {
            fetch(`/cours/Brief-GestionDeApp/dashboard/delete_student/${userId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                    const classId = classData.class_id;
                    fetch(`/cours/Brief-GestionDeApp/dashboard/class/${classId}/students`)
                        .then(response => response.json())
                        .then(data => {
                            renderStudentInfo(data.students,data.classData);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }


    function renderEditStudentForm(student,table,classData) {
        const addStudentFormContainer = document.getElementById('addStudentFormContainer');
        addStudentFormContainer.innerHTML = '';
        addStudentFormContainer.style.display = 'block';
        table.classList.add('hidden');

        const editStudentForm = document.createElement('div');
        editStudentForm.classList.add('max-w-md', 'mx-auto', 'bg-white', 'rounded-lg', 'shadow-md', 'p-6');
    
        const formTitle = document.createElement('h2');
        formTitle.classList.add('text-2xl', 'font-bold', 'mb-6', 'text-center');
        formTitle.textContent = 'Edit Student';
    
        editStudentForm.appendChild(formTitle);
    
        const editForm = document.createElement('form');
        editForm.id = 'editForm';
        editForm.method = 'POST';
    
        const lastNameField = createFormField('Last Name', 'text', 'last_name', student.last_name);
        editForm.appendChild(lastNameField);
    
        const firstNameField = createFormField('First Name', 'text', 'first_name', student.first_name);
        editForm.appendChild(firstNameField);
    
        const emailField = createFormField('Email Address', 'email', 'email', student.email);
        editForm.appendChild(emailField);
    
        const activationField = createFormField('Activation', 'checkbox', 'activation', student.activation === 1);
        editForm.appendChild(activationField);
    
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('flex', 'justify-end', 'mt-6');
    
        const cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.classList.add('bg-gray-300', 'text-gray-700', 'font-bold', 'py-2', 'px-4', 'rounded-md', 'hover:bg-gray-400', 'focus:outline-none', 'focus:ring-2', 'focus:ring-gray-500', 'mr-2');
        cancelButton.textContent = 'Cancel';
        cancelButton.addEventListener('click', () => hideEditStudentForm());
    
        const submitButton = document.createElement('button');
        submitButton.type = 'submit';
        submitButton.classList.add('bg-blue-500', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded-md', 'hover:bg-blue-600', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-500');
        submitButton.textContent = 'Save';
    
        buttonContainer.appendChild(cancelButton);
        buttonContainer.appendChild(submitButton);
    
        editForm.appendChild(buttonContainer);
        editStudentForm.appendChild(editForm);
    
        addStudentFormContainer.appendChild(editStudentForm);
    
        editForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            formData.append('user_id', student.user_id);
            formData.append('activation', activationField.querySelector('input').checked ? '1' : '0');
    
            fetch(`/cours/Brief-GestionDeApp/dashboard/edit_student/${student.user_id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                    hideEditStudentForm();
                    const classId = classData.class_id;
                    fetch(`/cours/Brief-GestionDeApp/dashboard/class/${classId}/students`)
                        .then(response => response.json())
                        .then(data => {
                            renderStudentInfo(data.students,data.classData);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        
        });
    }
function hideEditStudentForm() {
    const addStudentFormContainer = document.getElementById('addStudentFormContainer');
    addStudentFormContainer.innerHTML = '';
    addStudentFormContainer.style.display = 'none';

    const studentInfoTable = document.getElementById('studentInfo').querySelector('table');
    studentInfoTable.style.display = 'table';
}
//////////////////////////Hide Details Tap Function//////////////////////////

function hideClassDetails() {
    const classDetailsContainer = document.getElementById('classDetailsContainer');
    const classesContainer = document.getElementById('classesContainer');
    const courseCardsContainer = document.getElementById('courseCards');

    classDetailsContainer.classList.add('hidden');
    classesContainer.classList.remove('hidden');
    courseCardsContainer.classList.remove('hidden');
}

const homeTab = document.querySelector('[data-tabs-target="#Welcome"]');
const classesTab = document.querySelector('[data-tabs-target="#classes"]');

homeTab.addEventListener('click', event => {
    hideClassDetails();
    showTab(event, '#Welcome');
});



//////////////////////////Show Tap Function//////////////////////////

function showTab(event, tabTarget) {
    event.preventDefault();

    const tabLinks = document.querySelectorAll('[data-tabs-target]:not(#classDetailsContainer [data-tabs-target])');
    const tabContents = document.querySelectorAll('.tab-content:not(#classDetailsContainer .tab-content)');

    tabLinks.forEach(link => link.classList.remove('active', 'border-blue-600'));
    tabContents.forEach(content => content.classList.remove('active'));

    const targetLink = document.querySelector(`[data-tabs-target="${tabTarget}"]`);
    if (targetLink) {
        targetLink.classList.add('active', 'border-blue-600');
    }

    const targetContent = document.querySelector(tabTarget);
    if (targetContent) {
        targetContent.classList.add('active');
    }
}

function setupClassDetailsTabsEventListeners(classData) {
    const tabLinks = document.querySelectorAll('#classDetailsContainer [data-tab-target]');
    tabLinks.forEach(link => {
        link.addEventListener('click', event => {
            const tabTarget = link.getAttribute('data-tab-target');
            showClassDetailsTab(event, tabTarget, classData);
        });
    });
}

//////////////////////////Details Tap Function//////////////////////////

function showClassDetailsTab(event, tabTarget, classData) {
    if (event) {
        event.preventDefault();
    }

    const tabLinks = document.querySelectorAll('#classDetailsContainer [data-tab-target]');
    const tabContents = document.querySelectorAll('#classDetailsContainer .tab-content');

    tabLinks.forEach(link => link.classList.remove('active', 'border-blue-600'));
    tabContents.forEach(content => content.style.display = 'none');

    const targetLink = document.querySelector(`#classDetailsContainer [data-tab-target="${tabTarget}"]`);
    if (targetLink) {
        targetLink.classList.add('active', 'border-blue-600');
    }

    const targetContent = document.getElementById(tabTarget);
    if (targetContent) {
        targetContent.style.display = 'block';
    }

    renderClassGeneralInfo(classData, tabTarget);
}

//////////////////////////Student Info//////////////////////////
function renderStudentInfo(students,classData) {
    const studentInfoContainer = document.getElementById('studentInfo');
    studentInfoContainer.innerHTML = '';

    const table = document.createElement('table');
    table.classList.add('min-w-full', 'divide-y', 'divide-gray-200');

    const thead = document.createElement('thead');
    thead.classList.add('bg-gray-50');

    const theadRow = document.createElement('tr');

    const nameHeader = document.createElement('th');
    nameHeader.scope = 'col';
    nameHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    nameHeader.textContent = 'Name';

    const emailHeader = document.createElement('th');
    emailHeader.scope = 'col';
    emailHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    emailHeader.textContent = 'Email';

    const roleHeader = document.createElement('th');
    roleHeader.scope = 'col';
    roleHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    roleHeader.textContent = 'Role';

    const actionsHeader = document.createElement('th');
    actionsHeader.scope = 'col';
    actionsHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
    actionsHeader.textContent = 'Actions';

    theadRow.appendChild(nameHeader);
    theadRow.appendChild(emailHeader);
    theadRow.appendChild(roleHeader);
    theadRow.appendChild(actionsHeader);
    thead.appendChild(theadRow);

    const tbody = document.createElement('tbody');
    tbody.classList.add('bg-white', 'divide-y', 'divide-gray-200');

    students.forEach(student => {
        const row = document.createElement('tr');

        const nameCell = document.createElement('td');
        nameCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');
        nameCell.textContent = `${student.first_name} ${student.last_name}`;

        const emailCell = document.createElement('td');
        emailCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
        emailCell.textContent = student.email;

        const roleCell = document.createElement('td');
        roleCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
        roleCell.textContent = student.role_name;

        const actionsCell = document.createElement('td');
        actionsCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');

        const editLink = document.createElement('a');
        editLink.href = '#';
        editLink.classList.add('text-indigo-600', 'hover:text-indigo-900', 'mr-2');
        editLink.textContent = 'Edit';
        editLink.addEventListener('click', () => renderEditStudentForm(student,table,classData));

        const deleteLink = document.createElement('a');
        deleteLink.href = '#';
        deleteLink.classList.add('text-indigo-600', 'hover:text-indigo-900');
        deleteLink.textContent = 'Delete';
        deleteLink.addEventListener('click', () => deleteStudent(student.user_id,classData));

        actionsCell.appendChild(editLink);
        actionsCell.appendChild(deleteLink);

        row.appendChild(nameCell);
        row.appendChild(emailCell);
        row.appendChild(roleCell);
        row.appendChild(actionsCell);

        tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);

    studentInfoContainer.appendChild(table);

    const addStudentFormContainer = document.getElementById('addStudentFormContainer');
    if (!addStudentFormContainer) {
        const addStudentFormContainer = document.createElement('div');
        addStudentFormContainer.id = 'addStudentFormContainer';
        addStudentFormContainer.style.display = 'none';
        studentInfoContainer.appendChild(addStudentFormContainer);
    }
}


//////////////////////////Attendance Record//////////////////////////

    function renderAttendanceRecords(attendance) {
        const attendanceRecordsContainer = document.getElementById('attendanceRecords');
        attendanceRecordsContainer.innerHTML = '';
    
        const table = document.createElement('table');
        table.classList.add('min-w-full', 'divide-y', 'divide-gray-200');
    
        const thead = document.createElement('thead');
        thead.classList.add('bg-gray-50');
    
        const theadRow = document.createElement('tr');
    
        const nameHeader = document.createElement('th');
        nameHeader.scope = 'col';
        nameHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
        nameHeader.textContent = 'Name';
    
        const courseHeader = document.createElement('th');
        courseHeader.scope = 'col';
        courseHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
        courseHeader.textContent = 'Course';
    
        const presenceHeader = document.createElement('th');
        presenceHeader.scope = 'col';
        presenceHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
        presenceHeader.textContent = 'Presence';
    
        const delayHeader = document.createElement('th');
        delayHeader.scope = 'col';
        delayHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
        delayHeader.textContent = 'Delay';
    
        const actionsHeader = document.createElement('th');
        actionsHeader.scope = 'col';
        actionsHeader.classList.add('px-6', 'py-3', 'text-left', 'text-xs', 'font-medium', 'text-gray-500', 'uppercase', 'tracking-wider');
        actionsHeader.textContent = 'Actions';
    
        theadRow.appendChild(nameHeader);
        theadRow.appendChild(courseHeader);
        theadRow.appendChild(presenceHeader);
        theadRow.appendChild(delayHeader);
        theadRow.appendChild(actionsHeader);
        thead.appendChild(theadRow);
    
        const tbody = document.createElement('tbody');
        tbody.classList.add('bg-white', 'divide-y', 'divide-gray-200');
    
        attendance.forEach(record => {
            const row = document.createElement('tr');
    
            const nameCell = document.createElement('td');
            nameCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');
            nameCell.textContent = `${record.first_name} ${record.last_name}`;
    
            const courseCell = document.createElement('td');
            courseCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
            courseCell.textContent = record.course_id;
    
            const presenceCell = document.createElement('td');
            presenceCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
            presenceCell.textContent = record.presence ? 'Present' : 'Absent';
    
            const delayCell = document.createElement('td');
            delayCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-500');
            delayCell.textContent = record.delay ? 'Yes' : 'No';
    
            const actionsCell = document.createElement('td');
            actionsCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'font-medium', 'text-gray-900');
    
            const editLink = document.createElement('a');
            editLink.href = '#';
            editLink.classList.add('text-indigo-600', 'hover:text-indigo-900', 'mr-2');
            editLink.textContent = 'Edit';
    
            const deleteLink = document.createElement('a');
            deleteLink.href = '#';
            deleteLink.classList.add('text-indigo-600', 'hover:text-indigo-900');
            deleteLink.textContent = 'Delete';
    
            actionsCell.appendChild(editLink);
            actionsCell.appendChild(deleteLink);
    
            row.appendChild(nameCell);
            row.appendChild(courseCell);
            row.appendChild(presenceCell);
            row.appendChild(delayCell);
            row.appendChild(actionsCell);
    
            tbody.appendChild(row);
        });
    
        table.appendChild(thead);
        table.appendChild(tbody);
    
        attendanceRecordsContainer.appendChild(table);
}


//////////////////////////Course//////////////////////////
function renderCourseCards(courses, attendanceData, userRole, userId) {
    const courseCardsContainer = document.getElementById('courseCards');
    courseCardsContainer.innerHTML = '';

    if (userRole === 1) {
        // Student dashboard
        const studentCourses = courses.filter(course => {
            return attendanceData.some(data => data.course_id === course.course_id && data.user_id === userId);
        });

        studentCourses.forEach(course => {
            const courseCard = document.createElement('div');
            courseCard.classList.add('bg-white', 'p-4', 'rounded', 'shadow', 'mb-4');

            const classInfo = document.createElement('div');
            classInfo.classList.add('text-lg', 'font-bold', 'mb-2');
            classInfo.textContent = course.class_name;

            const courseDate = document.createElement('div');
            courseDate.classList.add('text-gray-500', 'mb-2');
            courseDate.textContent = course.course_date;

            const attendanceForm = document.createElement('form');
            attendanceForm.classList.add('flex');
            attendanceForm.setAttribute('data-course-id', course.course_id);

            const codeInput = document.createElement('input');
            codeInput.type = 'text';
            codeInput.classList.add('border', 'border-gray-300', 'rounded', 'px-2', 'py-1', 'mr-2');
            codeInput.placeholder = 'Attendance Code';

            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.classList.add('bg-blue-500', 'text-white', 'rounded', 'px-4', 'py-1');
            submitButton.textContent = 'Submit';
            submitButton.setAttribute('id', `submit-button-${course.course_id}`);

            attendanceForm.appendChild(codeInput);
            attendanceForm.appendChild(submitButton);

            attendanceForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const attendanceCode = codeInput.value;
                validateStudentAttendance(course.course_id, attendanceCode);
            });

            courseCard.appendChild(classInfo);
            courseCard.appendChild(courseDate);
            courseCard.appendChild(attendanceForm);

            courseCardsContainer.appendChild(courseCard);
        });
    
    } else if (userRole === 2) {
        const trainerCourses = courses.filter(course => {
            return attendanceData.some(data => data.course_id === course.course_id && data.user_id === userId);
        });

        trainerCourses.forEach(course => {
        const courseCard = document.createElement('div');
        courseCard.classList.add('flex', 'flex-col', 'items-start', 'p-6', 'gap-7.5', 'w-full', 'bg-gray-100', 'rounded');

        const courseInfo = document.createElement('div');
        courseInfo.classList.add('flex', 'justify-between', 'items-start', 'gap-235', 'w-full');

        const classInfo = document.createElement('div');
        classInfo.classList.add('flex', 'flex-col', 'items-start', 'gap-2.5', 'mx-auto');
        classInfo.innerHTML = `
            <div class="text-3xl font-normal text-black">${course.class_name}</div>
            <div class="text-base font-normal text-black">${course.places_available} participants</div>
        `;

        const courseDate = document.createElement('div');
        courseDate.classList.add('text-base', 'font-bold', 'text-black', 'mx-auto');
        courseDate.textContent = course.course_date;

        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('flex', 'flex-col', 'items-end', 'gap-12.5', 'w-full');

        const codeContainer = document.createElement('div');
        codeContainer.classList.add('text-lg', 'font-bold', 'text-black');

        const validateAttendanceButton = document.createElement('button');
        const courseAttendanceData = attendanceData.find(data => data.course_id === course.course_id);

        if (courseAttendanceData && courseAttendanceData.presence) {
            const randomCode = attendanceData.find(data => data.course_id === course.course_id)?.randomCode;
            if (randomCode) {
                codeContainer.textContent = `Code: ${randomCode}`;
            }
            validateAttendanceButton.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded', 'cursor-not-allowed');
            validateAttendanceButton.textContent = 'Attendance Validated';
            validateAttendanceButton.disabled = true;
        } else {
            validateAttendanceButton.classList.add('bg-blue-500', 'text-white', 'px-4', 'py-2', 'rounded');
            validateAttendanceButton.textContent = 'Validate Attendance';
            validateAttendanceButton.addEventListener('click', () => validateTrainerAttendance(course.course_id));
        }

        buttonContainer.appendChild(codeContainer);
        buttonContainer.appendChild(validateAttendanceButton);
/*********************************************************************************** */


        courseInfo.appendChild(classInfo);
        courseInfo.appendChild(courseDate);
        courseCard.appendChild(courseInfo);
        courseCard.appendChild(buttonContainer);
        courseCardsContainer.appendChild(courseCard);
      });}
      else if (userRole === 3) {
        // Responsible dashboard
        courses.forEach(course => {
            const courseCard = document.createElement('div');
            courseCard.classList.add('flex', 'flex-col', 'items-start', 'p-6', 'gap-7.5', 'w-full', 'bg-gray-100', 'rounded');

            const courseInfo = document.createElement('div');
            courseInfo.classList.add('flex', 'justify-between', 'items-start', 'gap-235', 'w-full');

            const classInfo = document.createElement('div');
            classInfo.classList.add('flex', 'flex-col', 'items-start', 'gap-2.5', 'mx-auto');
            classInfo.innerHTML = `
                <div class="text-3xl font-normal text-black">${course.class_name}</div>
                <div class="text-base font-normal text-black">${course.places_available} participants</div>
            `;

            const courseDate = document.createElement('div');
            courseDate.classList.add('text-base', 'font-bold', 'text-black', 'mx-auto');
            courseDate.textContent = course.course_date;

            const buttonContainer = document.createElement('div');
            buttonContainer.classList.add('flex', 'flex-col', 'items-end', 'gap-12.5', 'w-full');

            const button = document.createElement('div');
            button.classList.add('flex', 'justify-center', 'items-center', 'px-3', 'py-1.5', 'gap-2', 'border', 'rounded');

            const codeContainer = document.createElement('div');
            codeContainer.classList.add('text-lg', 'font-bold', 'text-black');

            const courseAttendanceData = attendanceData.find(data => data.course_id === course.course_id);

            if (courseAttendanceData && courseAttendanceData.randomCode) {
                codeContainer.textContent = `Code: ${courseAttendanceData.randomCode}`;
            } else {
                codeContainer.textContent = 'Code: Not Available';
            }

            buttonContainer.appendChild(codeContainer);

            courseInfo.appendChild(classInfo);
            courseInfo.appendChild(courseDate);
            buttonContainer.appendChild(button);
            courseCard.appendChild(courseInfo);
            courseCard.appendChild(buttonContainer);
            courseCardsContainer.appendChild(courseCard);
        });
    }
}
    
function checkAllStudentsAttended(courseId) {
    return fetch(`/cours/Brief-GestionDeApp/dashboard/check_attendance/${courseId}`)
        .then(response => response.json())
        .then(data => {
            return data.allAttended;
        })
        .catch(error => {
            console.error('Error:', error);
            return false;
        });
}

//////////////////////////Attendance Student//////////////////////////

function validateStudentAttendance(courseId, attendanceCode) {
    console.log("Validating attendance for course ID:", courseId);
    console.log("Submitted attendance code:", attendanceCode);

    const submitButton = document.getElementById(`submit-button-${courseId}`);

    fetch(`/cours/Brief-GestionDeApp/dashboard/validStudAttnd/${courseId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ attendanceCode })
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Server response:', text);
                throw new Error('Error validating attendance');
            });
        }
    })
    .then(data => {
        console.log(data);
        if (data.status === 'success') {
            console.log(data.message);

            submitButton.textContent = 'Attendance Validated';
            submitButton.classList.remove('bg-blue-500');
            submitButton.classList.add('bg-green-500', 'cursor-not-allowed');
            submitButton.disabled = true;
        } else {
            alert(data.error || 'Error validating attendance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while validating attendance');
    });
}


//////////////////////////Attendance Trainer//////////////////////////

function validateTrainerAttendance(courseId) {
    console.log("Validating attendance for course ID:", courseId);
    fetch(`/cours/Brief-GestionDeApp/dashboard/validateAttnd/${courseId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Server:', response);
        if (response.ok) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Error:', text);
                throw new Error('Error validating attendance');
            });
        }
    })
    .then(data => {
        if (data.status === 'success') {
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach(courseCard => {
                const courseIdAttribute = courseCard.getAttribute('data-course-id');
                if (courseIdAttribute === courseId.toString()) {
                    const codeContainer = courseCard.querySelector('.code-container');
                    const validateAttendanceButton = courseCard.querySelector('.validate-attendance-button');

                    codeContainer.textContent = `Code: ${data.randomCode}`;
                    validateAttendanceButton.classList.remove('bg-blue-500');
                    validateAttendanceButton.classList.add('bg-green-500', 'cursor-not-allowed');
                    validateAttendanceButton.textContent = 'Attendance Validated';
                    validateAttendanceButton.disabled = true;
                }
            });

            fetchData();
        } else {
            alert('Error validating attendance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        console.error('Error details:', error.message);
        alert('An error occurred while validating attendance. Please try again later.');
    });
}