document.addEventListener('DOMContentLoaded', function () {
    fetchCourses();
});

function fetchCourses() {
    fetch('/cours/Brief-GestionDeApp/dashboard/data')
       .then(response => response.json())
        .then(data => {
            console.log(data);
            renderCourseCards(data.courses, data.attendanceData, data.userRole);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
function renderCourseCards(courses, attendanceData, userRole, userId) {
    const courseCardsContainer = document.getElementById('courseCards');
    courseCardsContainer.innerHTML = '';

    if (userRole === 0) {
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

            const codeInput = document.createElement('input');
            codeInput.type = 'text';
            codeInput.classList.add('border', 'border-gray-300', 'rounded', 'px-2', 'py-1', 'mr-2');
            codeInput.placeholder = 'Attendance Code';

            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.classList.add('bg-blue-500', 'text-white', 'rounded', 'px-4', 'py-1');
            submitButton.textContent = 'Submit';

            attendanceForm.addEventListener('submit', (event) => {
                event.preventDefault();
                const attendanceCode = codeInput.value;
                validateStudentAttendance(course.course_id, attendanceCode);
            });

            attendanceForm.appendChild(codeInput);
            attendanceForm.appendChild(submitButton);

            courseCard.appendChild(classInfo);
            courseCard.appendChild(courseDate);
            courseCard.appendChild(attendanceForm);

            courseCardsContainer.appendChild(courseCard);
        });
    } else if (userRole === 1) {
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
      
        /******************************************************* */
        const codeContainer = document.createElement('div');
        codeContainer.classList.add('text-lg', 'font-bold', 'text-black');

            const validateAttendanceButton = document.createElement('button');
            const attendanceStatus = attendanceData.find(data => data.course_id === course.course_id)?.attendanceStatus;

            if (attendanceStatus === 'present') {
                validateAttendanceButton.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded', 'cursor-not-allowed');
                validateAttendanceButton.textContent = 'Attendance Validated';
                validateAttendanceButton.disabled = true;

                const randomCode = attendanceData.find(data => data.course_id === course.course_id)?.randomCode;
                if (randomCode) {
                    codeContainer.textContent = `Code: ${randomCode}`;
                }
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
        buttonContainer.appendChild(button);
        courseCard.appendChild(courseInfo);
        courseCard.appendChild(buttonContainer);
        courseCardsContainer.appendChild(courseCard);
      });}
    
}

function validateStudentAttendance(courseId, attendanceCode) {
    console.log("Validating attendance for course ID:", courseId);
    console.log("Submitted attendance code:", attendanceCode);
    
    fetch(`/cours/Brief-GestionDeApp/dashboard/validStudAttnd/${courseId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ attendanceCode })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.status === 'success') {
            console.log(data);
          //  alert(data.message);
            fetchCourses();
        } else {
            alert('From Where You Got This attendance code');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while validating attendance');
    });
}

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
            const randomCodeContainer = document.getElementById('randomCodeContainer');
            const randomCodeElement = document.getElementById('randomCode');
            randomCodeElement.textContent = data.randomCode;
            randomCodeContainer.classList.remove('hidden');

            const closeButton = document.getElementById('closeRandomCodeButton');
            closeButton.addEventListener('click', () => {
                randomCodeContainer.classList.add('hidden');
            });

            fetchCourses();
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